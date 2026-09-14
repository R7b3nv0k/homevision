<?php
require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="hu" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Vision | Rólunk</title>
    <link rel="stylesheet" href="../css/style.css?v=<?= filemtime(__DIR__ . '/../css/style.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        (function () {
            var theme = localStorage.getItem('homevision_theme');
            if (theme) document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>

<body>

    <nav class="navbar">
        <!-- ITT VAN AZ ÚJ BEÉPÍTETT LOGÓ -->
        <a href="../index.php" class="logo" style="display: flex; align-items: center;" aria-label="HomeVision Főoldal">
            <img src="../images/logo.png" alt="HomeVision" class="logo-img logo-light">
            <img src="../images/logo-dark.png" alt="HomeVision" class="logo-img logo-dark">
        </a>
        <!-- LOGÓ VÉGE -->
        <ul class="nav-links">
            <li><a href="../index.php" data-i18n="home">Főoldal</a></li>
            <li><a href="tervek.php" data-i18n="plans">Tervek</a></li>
            <li><a href="megvalositas.php" data-i18n="implementation">Megvalósítás</a></li>
            <li><a href="media.php" data-i18n="media">Média</a></li>
            <li><a href="rolunk.php" class="active" data-i18n="about">Rólunk</a></li>
            <li><a href="kapcsolat.php" data-i18n="contact">Kapcsolat</a></li>
        </ul>
        <div class="nav-controls">
            <button id="themeToggle" class="btn-icon" aria-label="Téma váltás"><?= hv_icon('theme') ?></button>
            <button id="langToggle" class="btn-text" aria-label="Nyelv váltás">EN</button>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../profile.php" class="btn-primary" style="text-decoration: none;" id="loginBtn"><?= hv_icon('user') ?> <span class="i18n-text" data-i18n="profile_btn">Profil</span></a>
            <?php else: ?>
                <div class="auth-dropdown-wrapper" id="authDropdownWrapper">
                    <button id="authChoiceBtn" class="btn-primary auth-choice-btn" aria-haspopup="true" aria-expanded="false">
                        <span data-i18n="login_register">Bejelentkezés / Regisztráció</span>
                        <span class="auth-btn-arrow"><?= hv_icon('arrow_down', 'hv-icon-sm') ?></span>
                    </button>
                    <div class="auth-choice-menu" id="authChoiceMenu" style="display: none;">
                        <a href="../login.php" class="auth-choice-card">
                            <span class="auth-choice-icon"><?= hv_icon('key') ?></span>
                            <div class="auth-choice-info">
                                <span class="auth-choice-title" data-i18n="dropdown_login">Bejelentkezés</span>
                                <span class="auth-choice-desc" data-i18n="dropdown_login_desc">Lépj be meglévő fiókodba</span>
                            </div>
                            <span class="auth-choice-arrow"><?= hv_icon('arrow_right', 'hv-icon-sm') ?></span>
                        </a>
                        <div class="auth-choice-divider"></div>
                        <a href="../register.php" class="auth-choice-card">
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

    <main class="page-container">
        <h1 class="page-title" data-i18n="about_title">Kik vagyunk mi?</h1>
        <div class="contact-wrapper" style="text-align: center; max-width: 800px;">
            <p data-i18n="about_text_1" style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 1rem;">
                A Home Vision egy fiatal, innovatív csapat, amelynek célja, hogy a modern és élhető házterveket mindenki
                számára könnyen elérhetővé tegye.
            </p>
            <p data-i18n="about_text_2" style="font-size: 1.1rem; line-height: 1.8;">
                Hisszük, hogy a letisztult design és a funkcionális terek ötvözése teremti meg a tökéletes otthont.
                Terveinket úgy alkottuk meg, hogy azonnal építhetőek legyenek, időt és pénzt spórolva a jövőbeli
                építtetőknek.
            </p>
        </div>
    </main>

    <!-- Lábléc (Footer) -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- 1. Cég bemutatkozás & Logó -->
                <div class="footer-col">
                    <a href="../index.php" class="logo" style="margin-bottom: 1rem; display: inline-flex;" aria-label="HomeVision Főoldal">
                        <img src="../images/logo.png" alt="HomeVision" class="logo-img logo-light">
                        <img src="../images/logo-dark.png" alt="HomeVision" class="logo-img logo-dark">
                    </a>
                    <p class="footer-desc" data-i18n="footer_desc">
                        Prémium minőségű, azonnal megvásárolható és építhető modern családi háztervek tervezőirodája.
                    </p>
                </div>

                <!-- 2. Hivatalos Cégadatok -->
                <div class="footer-col">
                    <h4 class="footer-heading" data-i18n="footer_company_info">Hivatalos Cégadatok</h4>
                    <ul class="footer-info-list">
                        <li><strong>Cégnév:</strong> Home Vision Kft.</li>
                        <li><strong data-i18n="footer_hq">Székhely:</strong> 1054 Budapest, Szabadság tér 7.</li>
                        <li><strong data-i18n="footer_tax">Adószám:</strong> 28741923-2-41</li>
                        <li><strong data-i18n="footer_reg">Cégjegyzékszám:</strong> 01-09-389142</li>
                        <li><strong data-i18n="footer_bank">Bankszámla:</strong> 11705008-20451829 (OTP)</li>
                    </ul>
                </div>

                <!-- 3. Gyorslinkek -->
                <div class="footer-col">
                    <h4 class="footer-heading" data-i18n="footer_quicklinks">Gyorslinkek</h4>
                    <ul class="footer-links">
                        <li><a href="../index.php" data-i18n="home">Főoldal</a></li>
                        <li><a href="tervek.php" data-i18n="plans">Tervek</a></li>
                        <li><a href="megvalositas.php" data-i18n="implementation">Megvalósítás</a></li>
                        <li><a href="media.php" data-i18n="media">Média</a></li>
                        <li><a href="rolunk.php" class="active" data-i18n="about">Rólunk</a></li>
                        <li><a href="kapcsolat.php" data-i18n="contact">Kapcsolat</a></li>
                    </ul>
                </div>

                <!-- 4. Kapcsolat & Ügyfélszolgálat -->
                <div class="footer-col">
                    <h4 class="footer-heading" data-i18n="footer_contact">Elérhetőségek</h4>
                    <ul class="footer-info-list">
                        <li><?= hv_icon('phone') ?> <a href="tel:+3612345678">+36 (1) 234-5678</a></li>
                        <li><?= hv_icon('mobile') ?> <a href="tel:+36309876543">+36 (30) 987-6543</a></li>
                        <li><?= hv_icon('mail') ?> <a href="mailto:info@homevision.hu">info@homevision.hu</a></li>
                        <li><?= hv_icon('clock') ?> <span data-i18n="footer_hours">H-P: 09:00 - 17:00</span></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p data-i18n="footer_rights">© 2026 Home Vision Kft. Minden jog fenntartva.</p>
                <p class="footer-credit" style="font-size: 0.78rem; opacity: 0.55; margin-top: 6px;" data-i18n="footer_credit">Web oldalt készítette:Katona Ruben(Térmágus KFT.)</p>
            </div>
        </div>
    </footer>

    <div id="authModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2 id="modalTitle" data-i18n="login_title">Bejelentkezés</h2>
            <div id="authAlert" style="display:none; padding: 10px; margin-bottom: 12px; border-radius: 8px; font-size: 0.9rem; text-align: center;"></div>
            <form id="authForm" action="../api/auth.php" method="POST">
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

    <script src="../js/script.js?v=<?= filemtime(__DIR__ . '/../js/script.js') ?>"></script>
</body>

</html>
