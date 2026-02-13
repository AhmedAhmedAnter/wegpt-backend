<?php
require_once '../../config/database.php';
require_once '../helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

$conversationId = $_GET['conversation_id'] ?? null;

if (!$conversationId) {
    sendError('Conversation ID is required');
}

try {
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE conversation_id = ? ORDER BY created_at ASC");
    $stmt->execute([$conversationId]);
    $messages = $stmt->fetchAll();
    sendResponse($messages);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
