<?php
require_once '../../config/database.php';
require_once '../helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

try {
    $stmt = $pdo->query("SELECT * FROM grades ORDER BY name ASC");
    $grades = $stmt->fetchAll();
    sendResponse($grades);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
