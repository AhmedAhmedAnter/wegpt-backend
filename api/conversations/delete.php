<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

$user = authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();
$id = $_GET['id'] ?? $data['id'] ?? null;

if (!$id) {
    sendError('Conversation ID is required');
}

try {
    // Check ownership
    $stmt = $pdo->prepare("SELECT user_id FROM conversations WHERE id = ?");
    $stmt->execute([$id]);
    $conv = $stmt->fetch();

    if (!$conv) {
        sendError('Conversation not found', 404);
    }

    if ($conv['user_id'] != $user['id'] && $user['role'] !== 'admin') {
        sendError('Forbidden: You do not own this conversation', 403);
    }

    $stmt = $pdo->prepare("DELETE FROM conversations WHERE id = ?");
    $stmt->execute([$id]);
    sendResponse(['message' => 'Conversation deleted']);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
