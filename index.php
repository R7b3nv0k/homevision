<?php
require_once __DIR__ . '/config/db.php';

// Google vélemények lekérdezése az adatbázisból (ha elérhető)
$reviews = [];
if (isDBConnected()) {
    try {
        $stmt = $pdo->query("SELECT * FROM reviews WHERE is_approved = 1 ORDER BY id ASC");
        $reviews = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Vélemények lekérési hiba: " . $e->getMessage());
    }
}

// Fallback vélemények, ha az adatbázis üres lenne vagy nem elérhető
if (empty($reviews)) {
    $reviews = [
        [
            'reviewer_name' => 'Kovács Péter',
            'reviewer_sub' => 'Helyi idegenvezető • 12 vélemény',
            'rating' => 5,
            'review_text' => 'A Nordic Family tervet vásároltuk meg, és a Térmágus Kft. segítségével építettük fel. A tervezéstől a kulcsátadásig minden zökkenőmentes volt, a nappali hatalmas ablakai és a beáradó természetes fény egyszerűen lenyűgözőek!',
            'review_date_str' => '2 hete',
            'avatar_initials' => 'KP',
            'avatar_gradient' => 'linear-gradient(135deg, #ff544d, #ff7b75)'
        ],
        [
            'reviewer_name' => 'Nagy Eszter',
            'reviewer_sub' => 'Google felhasználó',
            'rating' => 5,
            'review_text' => 'Nagyon örülök, hogy rátaláltunk a Home Visionre! A tervdokumentáció azonnal letölthető volt, a mérnöki csapat pedig minden kérdésünkre villámgyorsan és rendkívül szakszerűen válaszolt.',
            'review_date_str' => '1 hónapja',
            'avatar_initials' => 'NE',
            'avatar_gradient' => 'linear-gradient(135deg, #10b981, #059669)'
        ],
        [
            'reviewer_name' => 'Tóth Balázs',
            'reviewer_sub' => 'Helyi idegenvezető • 28 vélemény',
            'rating' => 5,
            'review_text' => 'A Villa Minimal terv alapján építkeztünk. Tartottunk az elszálló költségektől, de a Térmágus Kft.-vel rögzített áron és pontos határidőre készült el az épület. 5 csillagos élmény!',
            'review_date_str' => '3 hete',
            'avatar_initials' => 'TB',
            'avatar_gradient' => 'linear-gradient(135deg, #f59e0b, #d97706)'
        ],
        [
            'reviewer_name' => 'Dr. Szabó Zoltán',
            'reviewer_sub' => 'Google felhasználó',
            'rating' => 5,
            'review_text' => 'Profi, modern szemléletű csapat. A weboldalon lévő interaktív 3D modell már előre tökéletes képet adott a terekről, a minőségi anyaghasználat és a kivitelezés pedig magáért beszél. Csak ajánlani tudom.',
            'review_date_str' => '2 hónapja',
            'avatar_initials' => 'SZ',
            'avatar_gradient' => 'linear-gradient(135deg, #8b5cf6, #7c3aed)'
        ],
        [
            'reviewer_name' => 'Varga Dániel',
            'reviewer_sub' => 'Helyi idegenvezető • 8 vélemény',
            'rating' => 5,
            'review_text' => 'Az Eco Compact tervet választottuk. A téli fűtésszámlánk szinte elenyésző a hőszivattyús rendszernek és az A+ szigetelésnek köszönhetően. Zseniális koncepció!',
            'review_date_str' => '3 hete',
            'avatar_initials' => 'VD',
            'avatar_gradient' => 'linear-gradient(135deg, #06b6d4, #0891b2)'
        ],
        [
            'reviewer_name' => 'Kiss Mónika',
            'reviewer_sub' => 'Google felhasználó',
            'rating' => 5,
            'review_text' => 'Páratlan rugalmasság és segítőkészség! Külön dicséret a többnyelvű oldalért és a Térmágus Kft. megbízható generálkivitelezési garanciájáért. Köszönjük!',
            'review_date_str' => '4 napja',
            'avatar_initials' => 'KM',
            'avatar_gradient' => 'linear-gradient(135deg, #ec4899, #db2777)'
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="hu" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Vision | Építsd meg az álmod</title>
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime(__DIR__ . '/css/style.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
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
        <a href="index.php" class="logo" style="display: flex; align-items: center;" aria-label="HomeVision Főoldal">
            <img src="images/logo.png" alt="HomeVision" class="logo-img logo-light">
            <img src="images/logo-dark.png" alt="HomeVision" class="logo-img logo-dark">
        </a>
        <!-- LOGÓ VÉGE -->

        <ul class="nav-links">
            <li><a href="index.php" class="active" data-i18n="home">Főoldal</a></li>
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

    <header class="hero">
        <div class="hero-content">
            <h1 data-i18n="hero_title">Tervezd meg a jövőd otthonát</h1>
            <p data-i18n="hero_desc">Prémium minőségű, azonnal megvásárolható háztervek és inspirációk egy helyen.</p>
            <a href="pages/tervek.php" class="btn-large" data-i18n="hero_cta">Tervek felfedezése</a>
        </div>
    </header>

    <!-- Google Értékelések (Nonstop Pörgő Végtelen Ticker) -->
    <section class="reviews-section">
        <div class="reviews-header">
            <div class="google-rating-pill">
                <svg class="google-g-icon" viewBox="0 0 24 24">
                    <path fill="#4285F4"
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853"
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05"
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                    <path fill="#EA4335"
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                </svg>
                <span data-i18n="reviews_badge">Kiváló 4.9 ★★★★★ (64 Google vélemény)</span>
            </div>
            <h2 class="page-title" style="margin-bottom: 0.8rem;" data-i18n="reviews_title">Amit ügyfeleink mondanak
                rólunk</h2>
            <p style="font-size: 1.05rem; line-height: 1.7; opacity: 0.8; margin: 0;" data-i18n="reviews_lead">
                Valós visszajelzések olyan építtetőktől, akik a Home Vision terveivel és a Térmágus Kft. professzionális
                kivitelezésével valósították meg új otthonukat.
            </p>
        </div>

        <div class="reviews-marquee-wrapper">
            <div class="reviews-track">
                <!-- 1. Kártya készlet (Adatbázisból / fallbackből dinamikusan) -->
                <?php foreach ($reviews as $rev): ?>
                <div class="review-card">
                    <div>
                        <div class="review-card-top">
                            <div class="reviewer-profile">
                                <div class="reviewer-avatar" style="background: <?= htmlspecialchars($rev['avatar_gradient'] ?? 'linear-gradient(135deg, #ff544d, #ff7b75)') ?>;">
                                    <?= htmlspecialchars($rev['avatar_initials']) ?>
                                </div>
                                <div class="reviewer-info">
                                    <h4><?= htmlspecialchars($rev['reviewer_name']) ?></h4>
                                    <span><?= htmlspecialchars($rev['reviewer_sub']) ?></span>
                                </div>
                            </div>
                            <svg class="google-g-icon" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                            </svg>
                        </div>
                        <div class="review-stars-row">
                            <span class="reviews-stars-gold"><?= str_repeat('★', (int)$rev['rating']) ?></span>
                            <span class="review-date"><?= htmlspecialchars($rev['review_date_str']) ?></span>
                        </div>
                        <p class="review-text">
                            <?= htmlspecialchars($rev['review_text']) ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- 2. Kártya készlet (Másolat a folyamatos végtelenített hatáshoz) -->
                <?php foreach ($reviews as $rev): ?>
                <div class="review-card" aria-hidden="true">
                    <div>
                        <div class="review-card-top">
                            <div class="reviewer-profile">
                                <div class="reviewer-avatar" style="background: <?= htmlspecialchars($rev['avatar_gradient'] ?? 'linear-gradient(135deg, #ff544d, #ff7b75)') ?>;">
                                    <?= htmlspecialchars($rev['avatar_initials']) ?>
                                </div>
                                <div class="reviewer-info">
                                    <h4><?= htmlspecialchars($rev['reviewer_name']) ?></h4>
                                    <span><?= htmlspecialchars($rev['reviewer_sub']) ?></span>
                                </div>
                            </div>
                            <svg class="google-g-icon" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                            </svg>
                        </div>
                        <div class="review-stars-row">
                            <span class="reviews-stars-gold"><?= str_repeat('★', (int)$rev['rating']) ?></span>
                            <span class="review-date"><?= htmlspecialchars($rev['review_date_str']) ?></span>
                        </div>
                        <p class="review-text">
                            <?= htmlspecialchars($rev['review_text']) ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <!-- Lábléc (Footer) -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- 1. Cég bemutatkozás & Logó -->
                <div class="footer-col">
                    <a href="index.php" class="logo" style="margin-bottom: 1rem; display: inline-flex;"
                        aria-label="HomeVision Főoldal">
                        <img src="images/logo.png" alt="HomeVision" class="logo-img logo-light">
                        <img src="images/logo-dark.png" alt="HomeVision" class="logo-img logo-dark">
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
                        <li><a href="index.php" data-i18n="home">Főoldal</a></li>
                        <li><a href="pages/tervek.php" data-i18n="plans">Tervek</a></li>
                        <li><a href="pages/megvalositas.php" data-i18n="implementation">Megvalósítás</a></li>
                        <li><a href="pages/media.php" data-i18n="media">Média</a></li>
                        <li><a href="pages/rolunk.php" data-i18n="about">Rólunk</a></li>
                        <li><a href="pages/kapcsolat.php" data-i18n="contact">Kapcsolat</a></li>
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
                <p class="footer-credit" style="font-size: 0.78rem; opacity: 0.55; margin-top: 6px;"
                    data-i18n="footer_credit">Web oldalt készítette:Katona Ruben(Térmágus KFT.)</p>
            </div>
        </div>
    </footer>

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

    <script src="js/script.js?v=<?= filemtime(__DIR__ . '/js/script.js') ?>"></script>
</body>

</html>
