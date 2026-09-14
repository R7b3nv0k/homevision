<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$response = [
    'app' => 'HomeVision Diagnostics',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'CLI',
    'db_connected' => isDBConnected(),
    'db_error' => $db_error ?? null,
    'db_host' => $db_host ?? null,
    'db_port' => $db_port ?? null,
    'tables' => [],
    'user_count' => 0
];

if (isDBConnected()) {
    try {
        $db = getDB();
        $stmt = $db->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $response['tables'] = $tables;

        if (in_array('users', $tables)) {
            $userStmt = $db->query("SELECT COUNT(*) FROM users");
            $response['user_count'] = (int)$userStmt->fetchColumn();
        }
    } catch (Exception $e) {
        $response['query_error'] = $e->getMessage();
    }
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
