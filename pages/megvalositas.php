<?php
require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="hu" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Vision | Megvalósítás</title>
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
        <!-- BEÉPÍTETT LOGÓ -->
        <a href="../index.php" class="logo" style="display: flex; align-items: center;" aria-label="HomeVision Főoldal">
            <img src="../images/logo.png" alt="HomeVision" class="logo-img logo-light">
            <img src="../images/logo-dark.png" alt="HomeVision" class="logo-img logo-dark">
        </a>

        <ul class="nav-links">
            <li><a href="../index.php" data-i18n="home">Főoldal</a></li>
            <li><a href="tervek.php" data-i18n="plans">Tervek</a></li>
            <li><a href="megvalositas.php" class="active" data-i18n="implementation">Megvalósítás</a></li>
            <li><a href="media.php" data-i18n="media">Média</a></li>
            <li><a href="rolunk.php" data-i18n="about">Rólunk</a></li>
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
        <!-- Fejléc / Hero szakasz -->
        <div style="text-align: center; max-width: 860px; margin: 0 auto 3rem;">
            <span class="tag" style="background: rgba(255, 84, 77, 0.1); color: var(--primary-color); border-color: rgba(255, 84, 77, 0.25); font-weight: 600; margin-bottom: 1rem;" data-i18n="impl_badge">
                Prémium Kivitelezés & Munkapartner
            </span>
            <h1 class="page-title" style="margin-bottom: 1rem;" data-i18n="impl_title">A Tervektől a Kulcsátadásig</h1>
            <p style="font-size: 1.15rem; line-height: 1.8; color: var(--text-color); opacity: 0.85;" data-i18n="impl_lead">
                Házterveink nem csupán papíron léteznek — a Térmágus Kft.-vel karöltve valósággá váltjuk álmaid otthonát prémium minőségben, fix határidőkkel és garanciával.
            </p>
        </div>

        <!-- Kiemelt Partner Kártya (Térmágus Kft.) -->
        <div class="contact-wrapper" style="margin-bottom: 4rem; position: relative; overflow: hidden; border: 1px solid var(--border-color); background: var(--card-bg); box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.2rem; border-bottom: 1px solid var(--border-color);">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 52px; height: 52px; border-radius: 12px; background: linear-gradient(135deg, var(--primary-color), #ff8a85); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.6rem; font-weight: 700; box-shadow: 0 4px 14px var(--primary-glow);">
                        ✦
                    </div>
                    <div>
                        <h2 style="font-size: 1.6rem; margin: 0; color: var(--text-color);" data-i18n="partner_title">Kiemelt Munkapartnerünk: Térmágus Kft.</h2>
                        <span style="font-size: 0.88rem; color: var(--primary-color); font-weight: 600;" data-i18n="partner_badge">Hivatalos Kivitelező Partner</span>
                    </div>
                </div>
                <span class="tag" style="font-weight: 600;">ISO 9001 Minősítés</span>
            </div>

            <p style="font-size: 1.05rem; line-height: 1.8; margin-bottom: 2rem; color: var(--text-color); opacity: 0.9;" data-i18n="partner_desc">
                A Térmágus Kft. professzionális építőipari generálkivitelezőként a Home Vision háztervek hivatalos és dedikált kivitelező partnere. Több évtizedes szakmai tapasztalattal, saját mérnökcsapattal és modern gépparkkal biztosítják, hogy a tervrajzokon megálmodott részletek milliméteres pontossággal valósuljanak meg a valóságban is.
            </p>

            <!-- 3 Kiemelt Tulajdonság Kártya -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
                <div style="background: var(--bg-color); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.4rem;">
                    <div style="font-size: 1.8rem; margin-bottom: 0.6rem; color: var(--primary-color);"><?= hv_icon('key', 'hv-icon-lg') ?></div>
                    <h3 style="font-size: 1.15rem; margin-bottom: 0.5rem; color: var(--text-color);" data-i18n="partner_feat_1_title">Kulcsrakész Generálkivitelezés</h3>
                    <p style="font-size: 0.92rem; line-height: 1.6; opacity: 0.8; margin: 0;" data-i18n="partner_feat_1_desc">
                        Az alapozástól az utolsó lámpatest felszereléséig mindent egy kézben tartunk, így nincs szükség külön alvállalkozók keresésére.
                    </p>
                </div>
                <div style="background: var(--bg-color); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.4rem;">
                    <div style="font-size: 1.8rem; margin-bottom: 0.6rem; color: #10b981;"><?= hv_icon('leaf', 'hv-icon-lg') ?></div>
                    <h3 style="font-size: 1.15rem; margin-bottom: 0.5rem; color: var(--text-color);" data-i18n="partner_feat_2_title">Energiatakarékos Megoldások</h3>
                    <p style="font-size: 0.92rem; line-height: 1.6; opacity: 0.8; margin: 0;" data-i18n="partner_feat_2_desc">
                        A+ és A++ energetikai besorolású épületek, hőszivattyús fűtés, hővisszanyerős szellőztetés és napelem-előkészítés.
                    </p>
                </div>
                <div style="background: var(--bg-color); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.4rem;">
                    <div style="font-size: 1.8rem; margin-bottom: 0.6rem; color: #f59e0b;"><?= hv_icon('shield', 'hv-icon-lg') ?></div>
                    <h3 style="font-size: 1.15rem; margin-bottom: 0.5rem; color: var(--text-color);" data-i18n="partner_feat_3_title">Fix Árak és Garanciák</h3>
                    <p style="font-size: 0.92rem; line-height: 1.6; opacity: 0.8; margin: 0;" data-i18n="partner_feat_3_desc">
                        Szerződésben rögzített átadási határidők, rejtett költségek nélküli tételes árajánlat és teljes körű szerkezeti garancia.
                    </p>
                </div>
            </div>
        </div>

        <!-- 4 Lépéses Munkafolyamat -->
        <div style="margin-bottom: 4rem;">
            <h2 class="page-title" style="margin-bottom: 2.5rem;" data-i18n="workflow_title">A Megvalósítás 4 Lépése</h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 260px), 1fr)); gap: 1.5rem;">
                <div class="plan-card" style="padding: 1.8rem; text-align: left; display: flex; flex-direction: column;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="font-size: 2rem; font-weight: 800; color: var(--primary-color); opacity: 0.4;">01</span>
                        <span class="tag">Előkészítés</span>
                    </div>
                    <h3 style="font-size: 1.15rem; margin-bottom: 0.75rem; color: var(--text-color);" data-i18n="step_1_title">1. Tervegyeztetés & Telekfelmérés</h3>
                    <p style="font-size: 0.92rem; line-height: 1.6; opacity: 0.85; margin: 0;" data-i18n="step_1_desc">
                        Kiválasztott háztervedet a Térmágus Kft. mérnökei a telek adottságaihoz, tájolásához és a helyi szabályzathoz igazítják.
                    </p>
                </div>

                <div class="plan-card" style="padding: 1.8rem; text-align: left; display: flex; flex-direction: column;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="font-size: 2rem; font-weight: 800; color: var(--primary-color); opacity: 0.4;">02</span>
                        <span class="tag">Pénzügyek</span>
                    </div>
                    <h3 style="font-size: 1.15rem; margin-bottom: 0.75rem; color: var(--text-color);" data-i18n="step_2_title">2. Részletes Költségvetés</h3>
                    <p style="font-size: 0.92rem; line-height: 1.6; opacity: 0.85; margin: 0;" data-i18n="step_2_desc">
                        Teljes körű, tételes árajánlat készítése ütemezett fizetési mérföldkövekkel és rögzített kivitelezési időtartammal.
                    </p>
                </div>

                <div class="plan-card" style="padding: 1.8rem; text-align: left; display: flex; flex-direction: column;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="font-size: 2rem; font-weight: 800; color: var(--primary-color); opacity: 0.4;">03</span>
                        <span class="tag">Építkezés</span>
                    </div>
                    <h3 style="font-size: 1.15rem; margin-bottom: 0.75rem; color: var(--text-color);" data-i18n="step_3_title">3. Precíz Kivitelezés</h3>
                    <p style="font-size: 0.92rem; line-height: 1.6; opacity: 0.85; margin: 0;" data-i18n="step_3_desc">
                        Folyamatos mérnöki felügyelet, prémium alapanyagok és rendszeres státuszjelentések a megrendelőnek.
                    </p>
                </div>

                <div class="plan-card" style="padding: 1.8rem; text-align: left; display: flex; flex-direction: column;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="font-size: 2rem; font-weight: 800; color: var(--primary-color); opacity: 0.4;">04</span>
                        <span class="tag">Átadás</span>
                    </div>
                    <h3 style="font-size: 1.15rem; margin-bottom: 0.75rem; color: var(--text-color);" data-i18n="step_4_title">4. Kulcsátadás & Garancia</h3>
                    <p style="font-size: 0.92rem; line-height: 1.6; opacity: 0.85; margin: 0;" data-i18n="step_4_desc">
                        Sikeres műszaki átadás-átvétel, a használatbavételi engedély intézése és az új otthon kulcsainak átadása.
                    </p>
                </div>
            </div>
        </div>

        <!-- Látványterv illusztráció galéria -->
        <div style="margin-bottom: 4rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 280px), 1fr)); gap: 1.5rem;">
                <div style="border-radius: 16px; overflow: hidden; border: 1px solid var(--border-color); box-shadow: 0 8px 24px rgba(0,0,0,0.06);">
                    <img src="../images/villa_minimal.jpg" alt="Villa Minimal Megvalósítás" style="width: 100%; height: 230px; object-fit: cover; display: block;">
                    <div style="padding: 1rem 1.2rem; background: var(--card-bg);">
                        <h4 style="margin: 0 0 0.3rem; font-size: 1.05rem;">Villa Minimal • Generálkivitelezés</h4>
                        <p style="margin: 0; font-size: 0.85rem; opacity: 0.7;">Modern vasbeton szerkezet, monolit födémek és rejtett zsaluzia.</p>
                    </div>
                </div>
                <div style="border-radius: 16px; overflow: hidden; border: 1px solid var(--border-color); box-shadow: 0 8px 24px rgba(0,0,0,0.06);">
                    <img src="../images/nordic_family.jpg" alt="Nordic Family Megvalósítás" style="width: 100%; height: 230px; object-fit: cover; display: block;">
                    <div style="padding: 1rem 1.2rem; background: var(--card-bg);">
                        <h4 style="margin: 0 0 0.3rem; font-size: 1.05rem;">Nordic Family • Fenntartható Otthon</h4>
                        <p style="margin: 0; font-size: 0.85rem; opacity: 0.7;">Skandináv thermo fa homlokzatburkolat és 3 rétegű panoráma üvegezés.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Blokk -->
        <div class="contact-wrapper" style="text-align: center; max-width: 800px; margin: 0 auto 3rem; background: linear-gradient(135deg, var(--card-bg), rgba(255, 84, 77, 0.05)); border: 1px solid var(--border-color);">
            <h2 style="font-size: 1.6rem; margin-bottom: 0.8rem;" data-i18n="impl_cta_title">Építsd fel velünk álmaid otthonát!</h2>
            <p style="font-size: 1.05rem; line-height: 1.7; opacity: 0.85; margin-bottom: 1.8rem;" data-i18n="impl_cta_desc">
                Vedd fel a kapcsolatot csapatunkkal és a Térmágus Kft. szakembereivel egy kötetlen, díjmentes konzultációért!
            </p>
            <a href="kapcsolat.php" class="btn-large" style="display: inline-block; text-decoration: none;" data-i18n="impl_cta_btn">
                Konzultáció Kérése
            </a>
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
                        <li><a href="megvalositas.php" class="active" data-i18n="implementation">Megvalósítás</a></li>
                        <li><a href="media.php" data-i18n="media">Média</a></li>
                        <li><a href="rolunk.php" data-i18n="about">Rólunk</a></li>
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

    <!-- Modál -->
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
