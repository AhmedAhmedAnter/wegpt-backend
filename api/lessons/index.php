<?php
require_once '../../config/database.php';
require_once '../helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

try {
    $stmt = $pdo->query("SELECT * FROM lessons WHERE is_published = 1 ORDER BY created_at DESC");
    $lessons = $stmt->fetchAll();

    sendResponse($lessons);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
