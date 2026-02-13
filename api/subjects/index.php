<?php
require_once '../../config/database.php';
require_once '../helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

try {
    $stmt = $pdo->query("SELECT * FROM subjects ORDER BY name ASC");
    $subjects = $stmt->fetchAll();
    sendResponse($subjects);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
