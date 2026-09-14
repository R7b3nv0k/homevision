<?php
require_once __DIR__ . '/config/db.php';

// Ha nincs bejelentkezve, átirányítjuk a bejelentkezési oldalra
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = getDB();
$userId = (int)$_SESSION['user_id'];

// Friss felhasználói adatok lekérdezése
$stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    // Ha a felhasználó id-je nem létezne az adatbázisban
    header('Location: logout.php');
    exit;
}

// Felhasználóhoz tartozó korábbi üzenetek / ajánlatkérések
$userMessages = [];
try {
    $msgStmt = $db->prepare('SELECT * FROM messages WHERE email = ? ORDER BY created_at DESC');
    $msgStmt->execute([$user['email']]);
    $userMessages = $msgStmt->fetchAll();
} catch (Exception $e) {
    error_log("Üzenetek lekérési hiba: " . $e->getMessage());
}

$initials = '';
$nameParts = explode(' ', $user['full_name'] ?: $user['username']);
foreach ($nameParts as $part) {
    if (!empty($part)) $initials .= mb_substr($part, 0, 1, 'UTF-8');
}
$initials = mb_strtoupper(mb_substr($initials, 0, 2, 'UTF-8'), 'UTF-8');
?>
<!DOCTYPE html>
<html lang="hu" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Vision | Felhasználói Profil</title>
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
        .profile-container {
            max-width: 1100px;
            margin: 3rem auto;
            padding: 0 1.5rem;
        }

        .profile-header-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .profile-header-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-color), #ff8a85);
        }

        .profile-user-info {
            display: flex;
            align-items: center;
            gap: 1.8rem;
        }

        .profile-avatar-large {
            width: 90px;
            height: 90px;
            border-radius: 22px;
            background: linear-gradient(135deg, var(--primary-color), #ff7b75);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2.2rem;
            font-weight: 700;
            box-shadow: 0 8px 25px var(--primary-glow);
            flex-shrink: 0;
        }

        .profile-titles h1 {
            font-size: 1.8rem;
            margin: 0 0 0.4rem 0;
            color: var(--text-color);
        }

        .profile-titles p {
            margin: 0 0 0.8rem 0;
            opacity: 0.75;
            font-size: 0.95rem;
        }

        .role-badge {
            display: inline-block;
            padding: 0.35rem 0.9rem;
            background: rgba(255, 84, 77, 0.12);
            color: var(--primary-color);
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 600;
            border: 1px solid rgba(255, 84, 77, 0.25);
        }

        .role-badge.admin {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
            border-color: rgba(16, 185, 129, 0.25);
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 900px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        .profile-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 2rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
            margin-bottom: 2rem;
        }

        .profile-card-title {
            font-size: 1.3rem;
            margin: 0 0 1.5rem 0;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.8rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
            margin-bottom: 1.2rem;
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .form-group label {
            font-size: 0.85rem;
            font-weight: 600;
            opacity: 0.8;
            color: var(--text-color);
        }

        .form-group input {
            padding: 0.85rem 1rem;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: var(--bg-color);
            color: var(--text-color);
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .form-group input[readonly] {
            opacity: 0.65;
            cursor: not-allowed;
            background: rgba(150, 150, 150, 0.08);
        }

        .profile-alert {
            display: none;
            padding: 1rem;
            border-radius: 10px;
            font-size: 0.92rem;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 500;
        }

        .msg-history-item {
            padding: 1rem;
            border-radius: 12px;
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .msg-history-top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.4rem;
            font-size: 0.8rem;
            opacity: 0.7;
        }
    </style>
</head>

<body>

    <!-- Navigáció -->
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
            <a href="profile.php" class="btn-primary" style="text-decoration: none;" id="loginBtn"><?= hv_icon('user') ?> <span class="i18n-text" data-i18n="profile_btn">Profil</span></a>
            <a href="logout.php" class="btn-text" style="text-decoration: none; color: #ef4444;" title="Kijelentkezés"><?= hv_icon('logout', 'hv-icon-sm') ?> <span class="i18n-text" data-i18n="logout_btn">Kilépés</span></a>
            <button id="mobileMenuBtn" class="hamburger-btn" aria-label="Menü megnyitása">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </nav>
    <div class="mobile-nav-backdrop"></div>

    <!-- Profil Konténer -->
    <main class="profile-container">

        <!-- Fejléc / Üdvözlő kártya -->
        <div class="profile-header-card">
            <div class="profile-user-info">
                <div class="profile-avatar-large">
                    <?= htmlspecialchars($initials) ?>
                </div>
                <div class="profile-titles">
                    <h1><?= htmlspecialchars($user['full_name'] ?: $user['username']) ?></h1>
                    <p><?= hv_icon('mail') ?> <?= htmlspecialchars($user['email']) ?> &bull; <?= hv_icon('clock') ?> <span data-i18n="registered_on">Regisztrált:</span> <?= date('Y.m.d', strtotime($user['created_at'])) ?></p>
                    <span class="role-badge <?= $user['role'] === 'admin' ? 'admin' : '' ?>">
                        <?= $user['role'] === 'admin' ? hv_icon('crown') . ' <span data-i18n="admin_role">Adminisztrátor</span>' : hv_icon('home') . ' <span data-i18n="user_role">HomeVision Ügyfél</span>' ?>
                    </span>
                </div>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="pages/tervek.php" class="btn-primary" style="text-decoration: none; font-size: 0.95rem;">
                    <?= hv_icon('home') ?> <span class="i18n-text" data-i18n="explore_plans">Tervek felfedezése</span>
                </a>
                <a href="logout.php" class="btn-large" style="text-decoration: none; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.25); padding: 0.8rem 1.4rem; font-size: 0.95rem;">
                    <?= hv_icon('logout') ?> <span class="i18n-text" data-i18n="logout_btn">Kijelentkezés</span>
                </a>
            </div>
        </div>

        <div class="profile-grid">
            <!-- Bal oszlop: Adatmódosítási Űrlap -->
            <div class="profile-card">
                <h2 class="profile-card-title"><?= hv_icon('edit') ?> <span class="i18n-text" data-i18n="personal_data_title">Személyes Adatok Módosítása</span></h2>

                <div id="profileAlert" class="profile-alert"></div>

                <form id="profileUpdateForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="profName" data-i18n="name_label">Teljes Név</label>
                            <input type="text" id="profName" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?: '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="profEmail" data-i18n="email_label">Email Cím</label>
                            <input type="email" id="profEmail" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="profPhone" data-i18n="phone_label">Telefonszám</label>
                            <input type="tel" id="profPhone" name="phone" placeholder="+36 (30) 123-4567" value="<?= htmlspecialchars($user['phone'] ?: '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="profCity" data-i18n="city_label">Település / Város</label>
                            <input type="text" id="profCity" name="city" placeholder="pl. Budapest" data-i18n-placeholder="city" value="<?= htmlspecialchars($user['city'] ?: '') ?>">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="profAddress" data-i18n="address_label">Pontos Lakcím (utca, házszám)</label>
                        <input type="text" id="profAddress" name="address" placeholder="pl. Fő utca 12." data-i18n-placeholder="address" value="<?= htmlspecialchars($user['address'] ?: '') ?>">
                    </div>

                    <h3 style="font-size: 1.1rem; margin: 1.8rem 0 1rem 0; opacity: 0.9; color: var(--text-color);">
                        <?= hv_icon('lock') ?> <span data-i18n="password_change_title">Jelszó Módosítása (opcionális)</span>
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="profCurrPass" data-i18n="curr_pass_label">Jelenlegi Jelszó</label>
                            <input type="password" id="profCurrPass" name="current_password" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label for="profNewPass" data-i18n="new_pass_label">Új Jelszó (min. 6 karakter)</label>
                            <input type="password" id="profNewPass" name="new_password" placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" id="saveProfileBtn" class="btn-primary" style="margin-top: 1rem; width: 100%;">
                        <?= hv_icon('save') ?> <span class="i18n-text" data-i18n="save_changes_btn">Változtatások Mentése</span>
                    </button>
                </form>
            </div>

            <!-- Jobb oszlop: Fiók Státusz & Korábbi Megkeresések -->
            <div>
                <div class="profile-card">
                    <h2 class="profile-card-title"><?= hv_icon('info') ?> <span class="i18n-text" data-i18n="profile_panel_subtitle">Fiók Információk</span></h2>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.92rem; line-height: 2;">
                        <li><strong>Felhasználónév:</strong> <?= htmlspecialchars($user['username']) ?></li>
                        <li><strong>Fiók Típus:</strong> <?= $user['role'] === 'admin' ? 'Adminisztrátor' : 'Ügyfél' ?></li>
                        <li><strong>Utolsó belépés:</strong> <?= $user['last_login'] ? date('Y.m.d H:i', strtotime($user['last_login'])) : 'Most' ?></li>
                        <li><strong data-i18n="security_status">Biztonsági státusz:</strong> <span style="color: #10b981; font-weight: 600;"><?= hv_icon('shield') ?> <span data-i18n="status_protected">Aktív & Védett</span></span></li>
                    </ul>
                </div>

                <div class="profile-card">
                    <h2 class="profile-card-title"><?= hv_icon('inbox') ?> <span class="i18n-text" data-i18n="messages_title">Üzeneteid & Érdeklődéseid</span></h2>
                    <?php if (empty($userMessages)): ?>
                        <p style="opacity: 0.7; font-size: 0.9rem; margin: 0;" data-i18n="no_messages_yet">
                            Még nem küldtél üzenetet az oldal kapcsolatfelvételi űrlapján keresztül.
                        </p>
                    <?php else: ?>
                        <div style="max-height: 280px; overflow-y: auto; padding-right: 5px;">
                            <?php foreach ($userMessages as $msg): ?>
                                <div class="msg-history-item">
                                    <div class="msg-history-top">
                                        <span><?= hv_icon('calendar') ?> <?= date('Y.m.d H:i', strtotime($msg['created_at'])) ?></span>
                                        <span class="tag" style="padding: 0.15rem 0.5rem; font-size: 0.75rem;">
                                            <?= $msg['status'] === 'replied' ? 'Megválaszolva' : ($msg['status'] === 'read' ? 'Feldolgozás alatt' : 'Beérkezett') ?>
                                        </span>
                                    </div>
                                    <p style="margin: 0; opacity: 0.9;"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </main>

    <!-- Lábléc -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-bottom">
                <p data-i18n="footer_rights">© 2026 Home Vision Kft. Minden jog fenntartva.</p>
                <p class="footer-credit" style="font-size: 0.78rem; opacity: 0.55; margin-top: 6px;"
                    data-i18n="footer_credit">Web oldalt készítette: Katona Ruben (Térmágus Kft.)</p>
            </div>
        </div>
    </footer>

    <script src="js/script.js?v=<?= filemtime(__DIR__ . '/js/script.js') ?>"></script>
    <script>
        // Profil frissítés kezelése
        const profForm = document.getElementById('profileUpdateForm');
        const profAlert = document.getElementById('profileAlert');
        const saveBtn = document.getElementById('saveProfileBtn');

        if (profForm) {
            profForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                saveBtn.disabled = true;
                saveBtn.textContent = 'Mentés folyamatban...';

                const payload = {
                    action: 'update_profile',
                    full_name: document.getElementById('profName').value,
                    phone: document.getElementById('profPhone').value,
                    city: document.getElementById('profCity').value,
                    address: document.getElementById('profAddress').value,
                    current_password: document.getElementById('profCurrPass').value,
                    new_password: document.getElementById('profNewPass').value
                };

                try {
                    const res = await fetch('api/auth.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json();

                    profAlert.style.display = 'block';
                    profAlert.textContent = data.message;

                    if (data.success) {
                        profAlert.style.background = 'rgba(16, 185, 129, 0.15)';
                        profAlert.style.color = '#10b981';
                        profAlert.style.border = '1px solid rgba(16, 185, 129, 0.3)';
                        document.getElementById('profCurrPass').value = '';
                        document.getElementById('profNewPass').value = '';
                        setTimeout(() => { location.reload(); }, 1200);
                    } else {
                        profAlert.style.background = 'rgba(239, 68, 68, 0.15)';
                        profAlert.style.color = '#ef4444';
                        profAlert.style.border = '1px solid rgba(239, 68, 68, 0.3)';
                    }
                } catch (err) {
                    profAlert.style.display = 'block';
                    profAlert.style.background = 'rgba(239, 68, 68, 0.15)';
                    profAlert.style.color = '#ef4444';
                    profAlert.textContent = 'Kommunikációs hiba a szerverrel.';
                } finally {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<?= hv_icon("save") ?> <span class="i18n-text">' + (translations[currentLang]?.save_changes_btn || 'Változtatások Mentése') + '</span>';
                }
            });
        }
    </script>
</body>

</html>
