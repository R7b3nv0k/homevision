<?php
// Munkamenet (Session) automatikus indítása
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// Vektoros piktogram (SVG icon) rendszer betöltése
require_once __DIR__ . '/icons.php';

// Adatbázis konfiguráció (XAMPP alapértelmezett beállítások)
$db_host = 'localhost';
$db_name = 'homevision';
$db_user = 'root';
$db_pass = '';
$db_port = 3306;
$db_charset = 'utf8mb4';

$pdo = null;
$db_connected = false;
$db_error = null;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$hosts_to_try = [
    ['host' => 'localhost', 'port' => 3306],
    ['host' => '127.0.0.1', 'port' => 3306],
    ['host' => '127.0.0.1', 'port' => 3307],
    ['host' => 'localhost', 'port' => 3307],
];

foreach ($hosts_to_try as $config) {
    try {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$db_name};charset={$db_charset}";
        $pdo = new PDO($dsn, $db_user, $db_pass, $options);
        $db_connected = true;
        $db_host = $config['host'];
        $db_port = $config['port'];
        $db_error = null;
        break;
    } catch (PDOException $e) {
        $db_connected = false;
        $db_error = $e->getMessage();
    }
}

if (!$db_connected) {
    error_log("HomeVision DB Connection Error: " . ($db_error ?? 'Ismeretlen hiba'));
}

/**
 * Segédfüggvény a PDO objektum lekéréséhez
 */
function getDB() {
    global $pdo;
    return $pdo;
}

/**
 * Ellenőrzi, hogy sikeres-e az adatbázis kapcsolat
 */
function isDBConnected() {
    global $db_connected;
    return $db_connected;
}
