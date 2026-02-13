<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once "../auth_middleware.php";
authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

try {
    $stmt = $pdo->query("SELECT * FROM feedbacks ORDER BY created_at DESC");
    $feedbacks = $stmt->fetchAll();
    sendResponse($feedbacks);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
