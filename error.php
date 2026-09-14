<?php
require_once __DIR__ . '/config/db.php';

$errorMessage = trim($_GET['msg'] ?? 'Ismeretlen hiba történt a művelet során.');
$errorAction = trim($_GET['action'] ?? 'auth');
$backUrl = trim($_GET['back'] ?? 'index.php');

// Biztonsági ellenőrzés a visszatérési URL-re (csak belső relatív linkek engedélyezettek)
if (preg_match('/^(https?:\/\/)/i', $backUrl) && !str_starts_with($backUrl, 'http://localhost') && !str_starts_with($backUrl, 'http://127.0.0.1')) {
    $backUrl = 'index.php';
}

$isUserNotFound = stripos($errorMessage, 'Nem található regisztrált') !== false || stripos($errorMessage, 'előbb regisztrálj') !== false;
$isEmailExists = stripos($errorMessage, 'már létezik') !== false || stripos($errorMessage, 'már regisztráltak') !== false;
$isPasswordError = stripos($errorMessage, 'jelszó') !== false;
$isDbError = stripos($errorMessage, 'adatbázis') !== false || stripos($errorMessage, 'MySQL') !== false;
?>
<!DOCTYPE html>
<html lang="hu" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiba történt | Home Vision</title>
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
        .error-page-wrapper {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 20px 60px 20px;
            background: radial-gradient(circle at 50% 20%, rgba(239, 68, 68, 0.08), transparent 70%);
        }

        .error-card {
            background: var(--nav-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            max-width: 580px;
            width: 100%;
            text-align: center;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.15), 0 0 30px rgba(239, 68, 68, 0.1);
            position: relative;
            overflow: hidden;
            animation: errorCardPop 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes errorCardPop {
            0% { opacity: 0; transform: scale(0.92) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        .error-badge-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(185, 28, 28, 0.25));
            border: 2px solid rgba(239, 68, 68, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            margin: 0 auto 24px auto;
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.2);
        }

        .error-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 12px;
        }

        .error-desc-box {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 12px;
            padding: 16px 20px;
            margin: 20px 0 28px 0;
            color: #ef4444;
            font-size: 1.02rem;
            font-weight: 500;
            line-height: 1.5;
        }

        .error-tips {
            text-align: left;
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 30px;
            font-size: 0.92rem;
            color: var(--text-color);
        }

        .error-tips h4 {
            margin-bottom: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-color);
        }

        .error-tips ul {
            padding-left: 20px;
            margin: 0;
            line-height: 1.6;
            opacity: 0.9;
        }

        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            justify-content: center;
        }

        .error-actions a, .error-actions button {
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-retry {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 15px rgba(255, 84, 77, 0.35);
        }

        .btn-retry:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 84, 77, 0.5);
        }

        .btn-secondary-action {
            background: var(--bg-color);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .btn-secondary-action:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
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
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php" class="btn-primary" style="text-decoration: none;" id="loginBtn"><?= hv_icon('user') ?> <span class="i18n-text" data-i18n="profile_btn">Profil</span></a>
            <?php else: ?>
                <div class="auth-dropdown-wrapper" id="authDropdownWrapper">
                    <button id="authChoiceBtn" class="btn-primary auth-choice-btn" aria-haspopup="true" aria-expanded="false">
                        <span data-i18n="login_register">Bejelentkezés / Regisztráció</span>
                        <span class="auth-btn-arrow"><?= hv_icon('arrow_down', 'hv-icon-sm') ?></span>
                    </button>
                    <div class="auth-choice-menu" id="authChoiceMenu" style="display: none;">
                        <a href="login.php" class="auth-choice-card">
                            <span class="auth-choice-icon"><?= hv_icon('key') ?></span>
                            <div class="auth-choice-info">
                                <span class="auth-choice-title" data-i18n="dropdown_login">Bejelentkezés</span>
                                <span class="auth-choice-desc" data-i18n="dropdown_login_desc">Lépj be meglévő fiókodba</span>
                            </div>
                            <span class="auth-choice-arrow"><?= hv_icon('arrow_right', 'hv-icon-sm') ?></span>
                        </a>
                        <div class="auth-choice-divider"></div>
                        <a href="register.php" class="auth-choice-card">
                            <span class="auth-choice-icon"><?= hv_icon('edit') ?></span>
                            <div class="auth-choice-info">
                                <span class="auth-choice-title" data-i18n="dropdown_register">Regisztráció</span>
                                <span class="auth-choice-desc" data-i18n="dropdown_register_desc">Új fiók létrehozása</span>
                            </div>
                            <span class="auth-choice-arrow"><?= hv_icon('arrow_right', 'hv-icon-sm') ?></span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            <button id="mobileMenuBtn" class="hamburger-btn" aria-label="Menü megnyitása">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </nav>
    <div class="mobile-nav-backdrop"></div>

    <!-- Hibaoldal Központi Tartalom -->
    <main class="error-page-wrapper">
        <div class="error-card">
            <div class="error-badge-icon">
                <?php if ($isDbError): ?>
                    <?= hv_icon('warning', 'hv-icon-xl') ?>
                <?php elseif ($isUserNotFound): ?>
                    <?= hv_icon('user', 'hv-icon-xl') ?>
                <?php elseif ($isEmailExists): ?>
                    <?= hv_icon('mail', 'hv-icon-xl') ?>
                <?php else: ?>
                    <?= hv_icon('error', 'hv-icon-xl') ?>
                <?php endif; ?>
            </div>

            <h1 class="error-title">
                <?php if ($isDbError): ?>
                    Adatbázis hiba
                <?php elseif ($isUserNotFound): ?>
                    Nincs ilyen fiók
                <?php elseif ($isEmailExists): ?>
                    Már regisztrált email
                <?php elseif ($errorAction === 'login'): ?>
                    Sikertelen bejelentkezés
                <?php elseif ($errorAction === 'register'): ?>
                    Sikertelen regisztráció
                <?php else: ?>
                    Hiba történt
                <?php endif; ?>
            </h1>

            <div class="error-desc-box">
                <?= htmlspecialchars($errorMessage) ?>
            </div>

            <div class="error-tips">
                <h4><?= hv_icon('bulb') ?> <span data-i18n="error_suggested_actions">Javasolt teendők:</span></h4>
                <ul>
                    <?php if ($isUserNotFound): ?>
                        <li>Nem találtuk ezt az emailt az adatbázisban. Kattints a lenti <strong>Regisztráció indítása</strong> gombra új fiók létrehozásához.</li>
                        <li>Ellenőrizd az elgépeléseket az email címedben.</li>
                    <?php elseif ($isEmailExists): ?>
                        <li>Ezzel az email címmel már regisztráltál korábban! Kattints a <strong>Bejelentkezés</strong> gombra és add meg a jelszavad.</li>
                    <?php elseif ($isPasswordError): ?>
                        <li>Győződj meg róla, hogy nincs bekapcsolva a <code>Caps Lock</code> billentyű.</li>
                        <li>A jelszó pontosan megkülönbözteti a kis- és nagybetűket.</li>
                    <?php elseif ($isDbError): ?>
                        <li>Ellenőrizd a <strong>XAMPP Control Panelben</strong>, hogy a MySQL modul zölden fut-e (Start gomb).</li>
                        <li>Nyisd meg az <a href="api/status.php" target="_blank" style="color:var(--primary-color);">api/status.php</a> oldalt az adatbázis állapotának ellenőrzéséhez.</li>
                    <?php else: ?>
                        <li>Kérjük, ellenőrizd az űrlapon megadott adatok helyességét.</li>
                        <li>Ha a hiba továbbra is fennáll, próbáld meg újra betölteni az oldalt.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="error-actions">
                <?php if ($isUserNotFound): ?>
                    <a href="register.php" class="btn-retry">
                        <?= hv_icon('edit') ?> <span class="i18n-text" data-i18n="error_btn_register">Regisztráció indítása</span>
                    </a>
                    <a href="login.php" class="btn-secondary-action">
                        <?= hv_icon('key') ?> <span class="i18n-text" data-i18n="error_btn_retry_login">Másik bejelentkezés</span>
                    </a>
                <?php elseif ($isEmailExists): ?>
                    <a href="login.php" class="btn-retry">
                        <?= hv_icon('key') ?> <span class="i18n-text" data-i18n="error_btn_retry_login">Bejelentkezés</span>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn-retry">
                        <?= hv_icon('refresh') ?> <span class="i18n-text" data-i18n="error_btn_retry">Újrapróbálkozás</span>
                    </a>
                <?php endif; ?>
                <a href="index.php" class="btn-secondary-action">
                    <?= hv_icon('home') ?> <span class="i18n-text" data-i18n="error_btn_home">Vissza a főoldalra</span>
                </a>
            </div>
        </div>
    </main>

    <!-- Bejelentkezési Modál -->
    <div id="authModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2 id="modalTitle" data-i18n="login_title">Bejelentkezés</h2>
            <div id="authAlert" style="display:none; padding: 10px; margin-bottom: 12px; border-radius: 8px; font-size: 0.9rem; text-align: center;"></div>
            <form id="authForm" action="api/auth.php" method="POST">
                <input type="hidden" name="action" id="authActionField" value="login">
                <div id="registerFields" style="display: none;">
                    <input type="text" id="authName" name="full_name" placeholder="Teljes név" data-i18n-placeholder="name">
                    <input type="tel" id="authPhone" name="phone" placeholder="Telefonszám (pl. +36 30 123 4567)">
                </div>
                <input type="email" id="authEmail" name="email" placeholder="Email cím" required data-i18n-placeholder="email">
                <input type="password" id="authPassword" name="password" placeholder="Jelszó" required data-i18n-placeholder="password">
                <div id="registerConfirmField" style="display: none;">
                    <input type="password" id="authPasswordConfirm" name="password_confirm" placeholder="Jelszó megerősítése">
                </div>
                <button type="submit" class="btn-primary auth-submit" data-i18n="login_submit">Belépés</button>
            </form>
            <p class="switch-auth">
                <span data-i18n="no_account">Nincs még fiókod?</span>
                <a href="#" id="switchMode" data-i18n="register_link">Regisztrálj!</a>
            </p>
        </div>
    </div>

    <!-- Lábléc -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-bottom">
                <p>© 2026 Home Vision Kft. Minden jog fenntartva.</p>
            </div>
        </div>
    </footer>

    <script src="js/script.js?v=<?= filemtime(__DIR__ . '/js/script.js') ?>"></script>
</body>

</html>
