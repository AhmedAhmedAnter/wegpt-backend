<?php
require_once '../../config/database.php';
require_once '../helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

$id = $_GET['id'] ?? null;

if (!$id) {
    sendError('Lesson ID is required');
}

try {
    $stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");
    $stmt->execute([$id]);
    $lesson = $stmt->fetch();

    if (!$lesson) {
        sendError('Lesson not found', 404);
    }

    sendResponse($lesson);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
