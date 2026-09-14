<?php
// Munkamenet indítása
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';

// Kérés típusának és adatainak detektálása
$rawInput = file_get_contents('php://input');
$jsonInput = !empty($rawInput) ? json_decode($rawInput, true) : null;
$input = $jsonInput ?: $_POST;

$isAjax = !empty($jsonInput) 
    || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

/**
 * Egységes válaszadó segédfüggvény (AJAX JSON vagy klasszikus Form átirányítás)
 */
function sendAuthResponse($success, $message, $extra = []) {
    global $isAjax, $action;
    
    if ($isAjax) {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        $response = array_merge([
            'success' => $success,
            'message' => $message,
            'action'  => $action ?? 'auth'
        ], $extra);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        // Hagyományos form beküldés (fallback)
        if ($success) {
            $redirect = $extra['redirect'] ?? '../profile.php';
            header("Location: {$redirect}");
            exit;
        } else {
            $back = $_SERVER['HTTP_REFERER'] ?? '../index.php';
            $errorUrl = '../error.php?msg=' . urlencode($message) . '&action=' . urlencode($action ?? 'auth') . '&back=' . urlencode($back);
            header("Location: {$errorUrl}");
            exit;
        }
    }
}

// 0. Adatbázis elérhetőség ellenőrzése
if (!isDBConnected()) {
    $detail = !empty($db_error) ? " (" . $db_error . ")" : "";
    sendAuthResponse(
        false, 
        'Adatbázis-kapcsolódási hiba! Kérjük, győződj meg róla, hogy a MySQL fut a XAMPP Control Panelben.' . $detail,
        ['error_type' => 'db_connection_failed']
    );
}

$action = $input['action'] ?? 'login';
$db = getDB();

// -------------------------------------------------------------
// 1. REGISZTRÁCIÓ (REGISTER)
// -------------------------------------------------------------
if ($action === 'register') {
    $fullName = trim($input['full_name'] ?? '');
    $email = trim($input['email'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $password = trim($input['password'] ?? '');
    $passwordConfirm = trim($input['password_confirm'] ?? '');

    if (empty($email) || empty($password)) {
        sendAuthResponse(false, 'Az email cím és a jelszó megadása kötelező!', ['field' => 'email']);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendAuthResponse(false, 'Érvénytelen email cím formátum!', ['field' => 'email']);
    }

    if (strlen($password) < 6) {
        sendAuthResponse(false, 'A jelszónak legalább 6 karakter hosszúnak kell lennie!', ['field' => 'password']);
    }

    if (!empty($passwordConfirm) && $password !== $passwordConfirm) {
        sendAuthResponse(false, 'A megadott két jelszó nem egyezik meg!', ['field' => 'password_confirm']);
    }

    // Email egyediség ellenőrzése
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        sendAuthResponse(
            false, 
            'Ezzel az email címmel már létezik fiók! Kérjük, jelentkezz be helyette.',
            ['code' => 'email_exists', 'suggest_login' => true]
        );
    }

    // Felhasználónév előállítása a teljes névből vagy emailből
    if (!empty($fullName)) {
        $translit = @iconv('UTF-8', 'ASCII//TRANSLIT', $fullName);
        $usernameBase = preg_replace('/[^a-zA-Z0-9]/', '', $translit ?: $fullName);
        $username = strtolower($usernameBase ?: explode('@', $email)[0]);
    } else {
        $username = explode('@', $email)[0];
    }

    // Felhasználónév ütközés elkerülése
    $stmt = $db->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $username .= '_' . rand(100, 999);
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $displayName = !empty($fullName) ? $fullName : $username;

    $insertStmt = $db->prepare('
        INSERT INTO users (username, full_name, email, phone, password_hash, role, last_login) 
        VALUES (?, ?, ?, ?, ?, "user", NOW())
    ');
    
    if ($insertStmt->execute([$username, $displayName, $email, $phone, $hash])) {
        $newUserId = (int)$db->lastInsertId();

        // Automatikus bejelentkeztetés
        $_SESSION['user_id'] = $newUserId;
        $_SESSION['username'] = $username;
        $_SESSION['full_name'] = $displayName;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['role'] = 'user';

        sendAuthResponse(true, 'Sikeres regisztráció! Üdvözlünk, ' . htmlspecialchars($displayName) . '!', [
            'redirect' => 'profile.php',
            'user' => [
                'id' => $newUserId,
                'username' => $username,
                'full_name' => $displayName,
                'email' => $email,
                'role' => 'user'
            ]
        ]);
    } else {
        sendAuthResponse(false, 'Nem sikerült elmenteni a regisztrációt az adatbázisba. Kérjük, próbáld újra!');
    }
}

// -------------------------------------------------------------
// 2. BEJELENTKEZÉS (LOGIN)
// -------------------------------------------------------------
if ($action === 'login') {
    $email = trim($input['email'] ?? '');
    $password = trim($input['password'] ?? '');

    if (empty($email) || empty($password)) {
        sendAuthResponse(false, 'Kérjük, add meg az email címedet és a jelszavadat!');
    }

    $stmt = $db->prepare('
        SELECT id, username, full_name, email, phone, city, address, password_hash, role 
        FROM users 
        WHERE email = ? OR username = ?
    ');
    $stmt->execute([$email, $email]);
    $user = $stmt->fetch();

    if (!$user) {
        sendAuthResponse(
            false, 
            'Nem található regisztrált felhasználó ezzel az email címmel vagy felhasználónévvel! Kérjük, előbb regisztrálj.',
            ['code' => 'user_not_found', 'suggest_register' => true]
        );
    }

    if (!password_verify($password, $user['password_hash'])) {
        sendAuthResponse(
            false, 
            'Hibás jelszó! Kérjük, ellenőrizd a megadott jelszót (figyelj a kis- és nagybetűkre).',
            ['code' => 'invalid_password']
        );
    }

    // Utolsó belépés frissítése
    $updateStmt = $db->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
    $updateStmt->execute([$user['id']]);

    // Munkamenet beállítása
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['full_name'] = $user['full_name'] ?: $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['phone'] = $user['phone'] ?? '';
    $_SESSION['city'] = $user['city'] ?? '';
    $_SESSION['address'] = $user['address'] ?? '';
    $_SESSION['role'] = $user['role'];

    sendAuthResponse(true, 'Sikeres bejelentkezés! Üdvözlünk újra, ' . htmlspecialchars($_SESSION['full_name']) . '!', [
        'redirect' => 'profile.php',
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'full_name' => $_SESSION['full_name'],
            'email' => $user['email'],
            'role' => $user['role']
        ]
    ]);
}

// -------------------------------------------------------------
// 3. PROFIL MÓDOSÍTÁS (UPDATE_PROFILE)
// -------------------------------------------------------------
if ($action === 'update_profile') {
    if (!isset($_SESSION['user_id'])) {
        sendAuthResponse(false, 'A profil módosításához be kell jelentkezned!');
    }

    $userId = (int)$_SESSION['user_id'];
    $fullName = trim($input['full_name'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $city = trim($input['city'] ?? '');
    $address = trim($input['address'] ?? '');

    $currentPass = trim($input['current_password'] ?? '');
    $newPass = trim($input['new_password'] ?? '');

    if (empty($fullName)) {
        sendAuthResponse(false, 'A teljes név nem lehet üres!');
    }

    // Jelszóváltoztatási igény esetén
    if (!empty($newPass)) {
        if (strlen($newPass) < 6) {
            sendAuthResponse(false, 'Az új jelszónak legalább 6 karakterből kell állnia!');
        }

        // Régi jelszó ellenőrzése
        $checkStmt = $db->prepare('SELECT password_hash FROM users WHERE id = ?');
        $checkStmt->execute([$userId]);
        $currHash = $checkStmt->fetchColumn();

        if (!$currHash || !password_verify($currentPass, $currHash)) {
            sendAuthResponse(false, 'A jelenlegi jelszó helytelen!');
        }

        $newHash = password_hash($newPass, PASSWORD_DEFAULT);
        $updateStmt = $db->prepare('
            UPDATE users 
            SET full_name = ?, phone = ?, city = ?, address = ?, password_hash = ? 
            WHERE id = ?
        ');
        $success = $updateStmt->execute([$fullName, $phone, $city, $address, $newHash, $userId]);
    } else {
        $updateStmt = $db->prepare('
            UPDATE users 
            SET full_name = ?, phone = ?, city = ?, address = ? 
            WHERE id = ?
        ');
        $success = $updateStmt->execute([$fullName, $phone, $city, $address, $userId]);
    }

    if ($success) {
        $_SESSION['full_name'] = $fullName;
        $_SESSION['phone'] = $phone;
        $_SESSION['city'] = $city;
        $_SESSION['address'] = $address;

        sendAuthResponse(true, 'Profilod adatai sikeresen frissítve!');
    } else {
        sendAuthResponse(false, 'Nem sikerült elmenteni a változtatásokat.');
    }
}

sendAuthResponse(false, 'Érvénytelen vagy ismeretlen művelet!');
