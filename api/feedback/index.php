<?php
require_once '../../config/database.php';
require_once '../helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

try {
    $stmt = $pdo->query("SELECT * FROM feedback ORDER BY created_at DESC");
    $feedbacks = $stmt->fetchAll();
    sendResponse($feedbacks);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
