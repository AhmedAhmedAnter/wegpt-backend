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
    sendError('Message ID is required');
}

try {
    // Check ownership via conversation
    $sql = "SELECT m.id, c.user_id FROM messages m 
            JOIN conversations c ON m.conversation_id = c.id 
            WHERE m.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $msg = $stmt->fetch();

    if (!$msg) {
        sendError('Message not found', 404);
    }

    if ($msg['user_id'] != $user['id'] && $user['role'] !== 'admin') {
        sendError('Forbidden: You do not own the conversation containing this message', 403);
    }

    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->execute([$id]);
    sendResponse(['message' => 'Message deleted']);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
