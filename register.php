<?php
require_once __DIR__ . '/config/db.php';

// Ha a felhasználó már be van lépve, azonnal a profil oldalra irányítjuk
if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

$error = null;
$suggestLogin = false;
$fullName = '';
$email = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $passwordConfirm = trim($_POST['password_confirm'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Az email cím és a jelszó megadása kötelező!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Érvénytelen email cím formátum!';
    } elseif (strlen($password) < 6) {
        $error = 'A jelszónak legalább 6 karakter hosszúnak kell lennie!';
    } elseif (!empty($passwordConfirm) && $password !== $passwordConfirm) {
        $error = 'A megadott két jelszó nem egyezik meg!';
    } elseif (!isDBConnected()) {
        $error = 'Adatbázis-kapcsolódási hiba! Kérjük, győződj meg róla, hogy a MySQL fut a XAMPP Control Panelben.';
    } else {
        $db = getDB();
        
        // Ellenőrizzük, hogy foglalt-e az email
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Ezzel az email címmel már létezik regisztráció!';
            $suggestLogin = true;
        } else {
            // Felhasználónév előállítása
            if (!empty($fullName)) {
                $translit = @iconv('UTF-8', 'ASCII//TRANSLIT', $fullName);
                $usernameBase = preg_replace('/[^a-zA-Z0-9]/', '', $translit ?: $fullName);
                $username = strtolower($usernameBase ?: explode('@', $email)[0]);
            } else {
                $username = explode('@', $email)[0];
            }

            // Ütközés elkerülése
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

                header('Location: profile.php');
                exit;
            } else {
                $error = 'Nem sikerült elmenteni az új felhasználót az adatbázisba. Kérjük, próbáld újra!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció | Home Vision</title>
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime(__DIR__ . '/css/style.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        (function () {
            var theme = localStorage.getItem('homevision_theme');
            if (theme) document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <style>
        .auth-page-wrapper {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 20px 60px 20px;
            background: radial-gradient(circle at 50% 20%, rgba(255, 84, 77, 0.08), transparent 70%);
        }

        .auth-page-card {
            background: var(--nav-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.18), 0 0 30px var(--card-glow);
            position: relative;
            animation: authPopIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes authPopIn {
            0% { opacity: 0; transform: scale(0.94) translateY(18px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        .auth-page-header {
            text-align: center;
            margin-bottom: 26px;
        }

        .auth-page-icon {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255, 84, 77, 0.15), rgba(255, 84, 77, 0.28));
            border: 2px solid rgba(255, 84, 77, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 16px auto;
            box-shadow: 0 8px 20px rgba(255, 84, 77, 0.25);
        }

        .auth-page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 6px;
        }

        .auth-page-subtitle {
            font-size: 0.92rem;
            color: var(--text-color);
            opacity: 0.75;
        }

        .auth-form-group {
            margin-bottom: 16px;
            text-align: left;
        }

        .auth-form-group label {
            display: block;
            font-size: 0.86rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text-color);
        }

        .auth-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .auth-input-wrapper input {
            width: 100%;
            padding: 12px 16px;
            padding-left: 42px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: var(--bg-color);
            color: var(--text-color);
            font-size: 0.95rem;
            font-family: inherit;
            transition: border-color 0.25s, box-shadow 0.25s;
        }

        .auth-input-wrapper input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(255, 84, 77, 0.15);
        }

        .auth-input-icon {
            position: absolute;
            left: 14px;
            font-size: 1.1rem;
            opacity: 0.65;
            pointer-events: none;
        }

        .toggle-password-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            opacity: 0.6;
            transition: opacity 0.2s;
            padding: 4px;
        }

        .toggle-password-btn:hover {
            opacity: 1;
        }

        .auth-error-alert {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #ef4444;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.92rem;
            line-height: 1.45;
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .auth-error-alert a {
            color: #ef4444;
            font-weight: 700;
            text-decoration: underline;
        }

        .auth-submit-btn {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: #ffffff;
            border: none;
            font-size: 1.02rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.25s, box-shadow 0.25s;
            box-shadow: 0 8px 20px rgba(255, 84, 77, 0.35);
            margin-top: 10px;
        }

        .auth-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(255, 84, 77, 0.5);
        }

        .auth-footer-link {
            text-align: center;
            margin-top: 22px;
            font-size: 0.92rem;
            color: var(--text-color);
            opacity: 0.85;
        }

        .auth-footer-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            margin-left: 4px;
        }

        .auth-footer-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <!-- Navigációs sáv -->
    <nav class="navbar">
        <a href="index.php" class="logo" style="display: flex; align-items: center;" aria-label="HomeVision Főoldal">
            <img src="images/logo.png" alt="HomeVision" class="logo-img logo-light">
            <img src="images/logo-dark.png" alt="HomeVision" class="logo-img logo-dark">
        </a>

        <ul class="nav-links">
            <li><a href="index.php" data-i18n="home">Főoldal</a></li>
            <li><a href="pages/tervek.php" data-i18n="plans">Tervek</a></li>
            <li><a href="pages/megvalositas.php" data-i18n="implementation">Megvalósítás</a></li>
            <li><a href="pages/media.php" data-i18n="media">Média</a></li>
            <li><a href="pages/rolunk.php" data-i18n="about">Rólunk</a></li>
            <li><a href="pages/kapcsolat.php" data-i18n="contact">Kapcsolat</a></li>
        </ul>

        <div class="nav-controls">
            <button id="themeToggle" class="btn-icon" aria-label="Téma váltás"><?= hv_icon('theme') ?></button>
            <button id="langToggle" class="btn-text" aria-label="Nyelv váltás">EN</button>
            <a href="login.php" class="btn-primary" style="text-decoration: none;"><?= hv_icon('key') ?> <span class="i18n-text" data-i18n="dropdown_login">Bejelentkezés</span></a>
            <button id="mobileMenuBtn" class="hamburger-btn" aria-label="Menü megnyitása">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </nav>
    <div class="mobile-nav-backdrop"></div>

    <!-- Regisztrációs Űrlap Kártya -->
    <main class="auth-page-wrapper">
        <div class="auth-page-card">
            <div class="auth-page-header">
                <div class="auth-page-icon"><?= hv_icon('edit', 'hv-icon-xl') ?></div>
                <h1 class="auth-page-title" data-i18n="register_page_title">Fiók Regisztráció</h1>
                <p class="auth-page-subtitle" data-i18n="register_page_subtitle">Csatlakozz a Home Vision prémium közösségéhez!</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="auth-error-alert">
                    <div><?= hv_icon('warning') ?> <?= htmlspecialchars($error) ?></div>
                    <?php if ($suggestLogin): ?>
                        <div>
                            <a href="login.php">Kattints ide a bejelentkezéshez →</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="auth-form-group">
                    <label for="regName" data-i18n="name_label">Teljes név</label>
                    <div class="auth-input-wrapper">
                        <span class="auth-input-icon"><?= hv_icon('user') ?></span>
                        <input type="text" id="regName" name="full_name" value="<?= htmlspecialchars($fullName) ?>" placeholder="Kovács Péter" autofocus>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label for="regEmail"><span data-i18n="email_label">Email cím</span> <span style="color:var(--primary-color);">*</span></label>
                    <div class="auth-input-wrapper">
                        <span class="auth-input-icon"><?= hv_icon('mail') ?></span>
                        <input type="email" id="regEmail" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="kovacs.peter@email.hu" required>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label for="regPhone" data-i18n="phone_optional_label">Telefonszám (opcionális)</label>
                    <div class="auth-input-wrapper">
                        <span class="auth-input-icon"><?= hv_icon('mobile') ?></span>
                        <input type="tel" id="regPhone" name="phone" value="<?= htmlspecialchars($phone) ?>" placeholder="+36 30 123 4567">
                    </div>
                </div>

                <div class="auth-form-group">
                    <label for="regPass"><span data-i18n="password_label">Jelszó (legalább 6 karakter)</span> <span style="color:var(--primary-color);">*</span></label>
                    <div class="auth-input-wrapper">
                        <span class="auth-input-icon"><?= hv_icon('lock') ?></span>
                        <input type="password" id="regPass" name="password" placeholder="••••••••" required minlength="6">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('regPass')" aria-label="Jelszó felfedése"><?= hv_icon('eye') ?></button>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label for="regPassConfirm"><span data-i18n="password_confirm_label">Jelszó megerősítése</span> <span style="color:var(--primary-color);">*</span></label>
                    <div class="auth-input-wrapper">
                        <span class="auth-input-icon"><?= hv_icon('lock') ?></span>
                        <input type="password" id="regPassConfirm" name="password_confirm" placeholder="••••••••" required minlength="6">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('regPassConfirm')" aria-label="Jelszó felfedése"><?= hv_icon('eye') ?></button>
                    </div>
                </div>

                <button type="submit" class="auth-submit-btn" data-i18n="register_submit">Fiók létrehozása</button>
            </form>

            <div class="auth-footer-link">
                <span data-i18n="has_account">Már van fiókod?</span> <a href="login.php" data-i18n="login_here">Lépj be itt!</a>
            </div>
        </div>
    </main>

    <!-- Lábléc -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-bottom">
                <p>© 2026 Home Vision Kft. Minden jog fenntartva.</p>
            </div>
        </div>
    </footer>

    <script src="js/script.js?v=<?= filemtime(__DIR__ . '/js/script.js') ?>"></script>
    <script>
        function togglePasswordVisibility(id) {
            var input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</body>

</html>
