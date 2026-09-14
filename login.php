<?php
require_once __DIR__ . '/config/db.php';

// Ha a felhasználó már be van lépve, azonnal a profil oldalra irányítjuk
if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

$error = null;
$suggestRegister = false;
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Kérjük, add meg az email címedet és a jelszavadat!';
    } elseif (!isDBConnected()) {
        $error = 'Adatbázis-kapcsolódási hiba! Kérjük, győződj meg róla, hogy a MySQL fut a XAMPP Control Panelben.';
    } else {
        $db = getDB();
        $stmt = $db->prepare('SELECT id, username, full_name, email, phone, city, address, password_hash, role FROM users WHERE email = ? OR username = ?');
        $stmt->execute([$email, $email]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = 'Nem található regisztrált felhasználó ezzel az email címmel vagy felhasználónévvel!';
            $suggestRegister = true;
        } elseif (!password_verify($password, $user['password_hash'])) {
            $error = 'Hibás jelszó! Kérjük, ellenőrizd a megadott jelszót (figyelj a kis- és nagybetűkre).';
        } else {
            // Utolsó belépés frissítése
            $updateStmt = $db->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
            $updateStmt->execute([$user['id']]);

            // Munkamenet mentése
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'] ?: $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phone'] = $user['phone'] ?? '';
            $_SESSION['city'] = $user['city'] ?? '';
            $_SESSION['address'] = $user['address'] ?? '';
            $_SESSION['role'] = $user['role'];

            header('Location: profile.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés | Home Vision</title>
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
            max-width: 440px;
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
            margin-bottom: 28px;
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
            margin-bottom: 18px;
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
            padding: 13px 16px;
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
            margin-bottom: 22px;
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
            margin-top: 24px;
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

        .auth-demo-credentials {
            margin-top: 20px;
            padding: 12px;
            border-radius: 8px;
            background: var(--bg-color);
            border: 1px dashed var(--border-color);
            font-size: 0.82rem;
            text-align: left;
            opacity: 0.85;
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
            <a href="register.php" class="btn-primary" style="text-decoration: none;"><?= hv_icon('edit') ?> <span class="i18n-text" data-i18n="dropdown_register">Regisztráció</span></a>
            <button id="mobileMenuBtn" class="hamburger-btn" aria-label="Menü megnyitása">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </nav>
    <div class="mobile-nav-backdrop"></div>

    <!-- Bejelentkezési Űrlap Kártya -->
    <main class="auth-page-wrapper">
        <div class="auth-page-card">
            <div class="auth-page-header">
                <div class="auth-page-icon"><?= hv_icon('key', 'hv-icon-xl') ?></div>
                <h1 class="auth-page-title" data-i18n="login_page_title">Bejelentkezés</h1>
                <p class="auth-page-subtitle" data-i18n="login_page_subtitle">Üdvözlünk újra a Home Vision rendszerében!</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="auth-error-alert">
                    <div><?= hv_icon('warning') ?> <?= htmlspecialchars($error) ?></div>
                    <?php if ($suggestRegister): ?>
                        <div>
                            <a href="register.php">Kattints ide az új fiók azonnali regisztrálásához →</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="auth-form-group">
                    <label for="loginEmail" data-i18n="email_label">Email cím vagy felhasználónév</label>
                    <div class="auth-input-wrapper">
                        <span class="auth-input-icon"><?= hv_icon('mail') ?></span>
                        <input type="text" id="loginEmail" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="pelda@email.com" required autofocus>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label for="loginPassword" data-i18n="password_label">Jelszó</label>
                    <div class="auth-input-wrapper">
                        <span class="auth-input-icon"><?= hv_icon('lock') ?></span>
                        <input type="password" id="loginPassword" name="password" placeholder="••••••••" required>
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('loginPassword')" aria-label="Jelszó felfedése"><?= hv_icon('eye') ?></button>
                    </div>
                </div>

                <button type="submit" class="auth-submit-btn" data-i18n="login_submit">Belépés a fiókba</button>

                <div class="auth-demo-credentials">
                    <?= hv_icon('bulb') ?> <strong data-i18n="test_admin_title">Teszt Admin Fiók:</strong><br>
                    Email: <code>info@homevision.hu</code><br>
                    Jelszó: <code>admin123</code>
                </div>
            </form>

            <div class="auth-footer-link">
                <span data-i18n="no_account">Nincs még fiókod?</span> <a href="register.php" data-i18n="register_here">Regisztrálj itt!</a>
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
