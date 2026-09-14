-- HomeVision MySQL Adatbázis Séma és Mintaadatok
-- Készült: 2026-09-09

CREATE DATABASE IF NOT EXISTS `homevision`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `homevision`;

-- --------------------------------------------------------
-- 1. Tábla: `plans` (Megvásárolható háztervek)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `plans` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `title` VARCHAR(255) NOT NULL,
  `sqm` INT NOT NULL,
  `rooms` INT NOT NULL,
  `price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
  `price_formatted` VARCHAR(100) DEFAULT NULL,
  `short_desc` TEXT DEFAULT NULL,
  `full_desc` TEXT DEFAULT NULL,
  `image` VARCHAR(255) NOT NULL,
  `page_url` VARCHAR(255) NOT NULL,
  `is_featured` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `plans` (`id`, `slug`, `title`, `sqm`, `rooms`, `price`, `price_formatted`, `short_desc`, `full_desc`, `image`, `page_url`, `is_featured`) VALUES
(1, 'villa-minimal', 'Villa Minimal', 120, 4, 1650000.00, '1 650 000 Ft', 
 'Modern, letisztult minimál stílusú családi ház tágas terekkel és hatalmas ablakokkal.',
 'A Villa Minimal a letisztult vonalvezetés és a maximális funkcionális térkihasználás tökéletes harmóniája. 120 négyzetméteres alapterületén négy kényelmes szoba kapott helyet, emellett a legkorszerűbb hőszigetelési technológiák garantálják az alacsony fenntartási költségeket.',
 '../images/villa_minimal.jpg', 'nordicfamily.php', 1),

(2, 'nordic-family', 'Nordic Family', 150, 5, 1850000.00, '1 850 000 Ft', 
 'Prémium skandináv stílusú, egyszintes családi ház hatalmas üvegfelületekkel és elszeparált szülői lakosztállyal.',
 'A Nordic Family egy modern, skandináv stílusjegyeket magán viselő, egyszintes családi ház. Hatalmas üvegfelületeinek köszönhetően a nappali egész nap fényárban úszik. Az alaprajz különválasztja a szülői hálót a gyerekszobáktól, így biztosítva a maximális intimitást. A terv tartalmazza az építészeti, gépészeti és villamossági tervdokumentációt is.',
 '../images/nordic_family.jpg', 'nordicfamily.php', 1),

(3, 'eco-compact', 'Eco Compact', 80, 3, 1290000.00, '1 290 000 Ft', 
 'Kiemelkedően energiatakarékos, kompakt elrendezésű okosotthon A++ energetikai besorolással.',
 'Az Eco Compact ideális választás fiatal pároknak és kisebb családoknak, akik kompromisszummentes minőségre vágynak fenntartható és rezsimentes formában. Hőszivattyús rendszerrel és hővisszanyerős szellőztetéssel felszerelve.',
 '../images/eco_compact.jpg', 'nordicfamily.php', 1)
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- --------------------------------------------------------
-- 2. Tábla: `reviews` (Google Értékelések)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `reviewer_name` VARCHAR(150) NOT NULL,
  `reviewer_sub` VARCHAR(150) DEFAULT 'Google felhasználó',
  `rating` INT NOT NULL DEFAULT 5,
  `review_text` TEXT NOT NULL,
  `review_date_str` VARCHAR(50) NOT NULL,
  `avatar_initials` VARCHAR(10) NOT NULL,
  `avatar_gradient` VARCHAR(120) NOT NULL DEFAULT 'linear-gradient(135deg, #2563eb, #1d4ed8)',
  `is_approved` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `reviews` (`id`, `reviewer_name`, `reviewer_sub`, `rating`, `review_text`, `review_date_str`, `avatar_initials`, `avatar_gradient`, `is_approved`) VALUES
(1, 'Kovács Péter', 'Helyi idegenvezető • 12 vélemény', 5,
 'A Nordic Family tervet vásároltuk meg, és a Térmágus Kft. segítségével építettük fel. A tervezéstől a kulcsátadásig minden zökkenőmentes volt, a nappali hatalmas ablakai és a beáradó természetes fény egyszerűen lenyűgözőek!',
 '2 hete', 'KP', 'linear-gradient(135deg, #ff544d, #ff7b75)', 1),

(2, 'Nagy Eszter', 'Google felhasználó', 5,
 'Nagyon örülök, hogy rátaláltunk a Home Visionre! A tervdokumentáció azonnal letölthető volt, a mérnöki csapat pedig minden kérdésünkre villámgyorsan és rendkívül szakszerűen válaszolt.',
 '1 hónapja', 'NE', 'linear-gradient(135deg, #10b981, #059669)', 1),

(3, 'Tóth Balázs', 'Helyi idegenvezető • 28 vélemény', 5,
 'A Villa Minimal terv alapján építkeztünk. Tartottunk az elszálló költségektől, de a Térmágus Kft.-vel rögzített áron és pontos határidőre készült el az épület. 5 csillagos élmény!',
 '3 hete', 'TB', 'linear-gradient(135deg, #f59e0b, #d97706)', 1),

(4, 'Dr. Szabó Zoltán', 'Google felhasználó', 5,
 'Profi, modern szemléletű csapat. A weboldalon lévő interaktív 3D modell már előre tökéletes képet adott a terekről, a minőségi anyaghasználat és a kivitelezés pedig magáért beszél. Csak ajánlani tudom.',
 '2 hónapja', 'SZ', 'linear-gradient(135deg, #8b5cf6, #7c3aed)', 1),

(5, 'Varga Dániel', 'Helyi idegenvezető • 8 vélemény', 5,
 'Az Eco Compact tervet választottuk. A téli fűtésszámlánk szinte elenyésző a hőszivattyús rendszernek és az A+ szigetelésnek köszönhetően. Zseniális koncepció!',
 '3 hete', 'VD', 'linear-gradient(135deg, #06b6d4, #0891b2)', 1),

(6, 'Kiss Mónika', 'Google felhasználó', 5,
 'Páratlan rugalmasság és segítőkészség! Külön dicséret a többnyelvű oldalért és a Térmágus Kft. megbízható generálkivitelezési garanciájáért. Köszönjük!',
 '4 napja', 'KM', 'linear-gradient(135deg, #ec4899, #db2777)', 1)
ON DUPLICATE KEY UPDATE `reviewer_name` = VALUES(`reviewer_name`);

-- --------------------------------------------------------
-- 3. Tábla: `messages` (Kapcsolatfelvételi üzenetek)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('new', 'read', 'replied') DEFAULT 'new',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. Tábla: `media_gallery` (Megépült otthonok galériája)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `media_gallery` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `location` VARCHAR(150) DEFAULT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `media_gallery` (`id`, `title`, `location`, `image_path`) VALUES
(1, 'Villa Minimal - Budapest', 'Budapest', '../images/villa_minimal.jpg'),
(2, 'Nordic Family - Győr', 'Győr', '../images/nordic_family.jpg')
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- --------------------------------------------------------
-- 5. Tábla: `users` (Felhasználók & Adminok)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `full_name` VARCHAR(150) DEFAULT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `phone` VARCHAR(50) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'user') DEFAULT 'user',
  `last_login` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jelszó az adminhoz: 'admin123'
INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `phone`, `city`, `address`, `password_hash`, `role`) VALUES
(1, 'admin', 'HomeVision Adminisztrátor', 'info@homevision.hu', '+36 (30) 987-6543', 'Budapest', 'Szabadság tér 7.', '$2y$10$FL58ICKJXBbinbOybPzDRuf/GN82BtQyzAlluDgQjn8Q7jahDk2ry', 'admin')
ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);

-- --------------------------------------------------------
-- 6. Tábla: `site_settings` (Cégadatok és Beállítások)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('company_name', 'Home Vision Kft.'),
('company_hq', '1054 Budapest, Szabadság tér 7.'),
('company_tax', '28741923-2-41'),
('company_reg', '01-09-389142'),
('company_bank', '11705008-20451829 (OTP)'),
('phone_landline', '+36 (1) 234-5678'),
('phone_mobile', '+36 (30) 987-6543'),
('email', 'info@homevision.hu'),
('opening_hours', 'H-P: 09:00 - 17:00')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);
