<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

$user = authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
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

    $fields = [];
    $params = [];
    $allowedFields = ['conversation_status', 'metadata', 'lessons_ids', 'context'];

    foreach ($allowedFields as $field) {
        if (isset($data[$field])) {
            $fields[] = "$field = :$field";
            $params[$field] = is_array($data[$field]) ? json_encode($data[$field]) : $data[$field];
        }
    }

    if (empty($fields)) {
        sendError('No fields to update');
    }

    $params['id'] = $id;
    $sql = "UPDATE conversations SET " . implode(', ', $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    sendResponse(['message' => 'Conversation updated']);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
