<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once "../auth_middleware.php";
authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

try {
    $stmt = $pdo->query("SELECT * FROM ai_settings WHERE is_active = 1");
    $settings = $stmt->fetchAll();
    sendResponse($settings);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
