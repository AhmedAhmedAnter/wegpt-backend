<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once "../auth_middleware.php";
authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();

if (empty($data['message_id']) || empty($data['user_id']) || empty($data['type'])) {
    sendError('message_id, user_id, and type are required');
}

try {
    $sql = "INSERT INTO feedbacks (message_id, user_id, type, comment, feedback_type) 
            VALUES (:message_id, :user_id, :type, :comment, :feedback_type)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'message_id' => $data['message_id'],
        'user_id' => $data['user_id'],
        'type' => $data['type'],
        'comment' => $data['comment'] ?? null,
        'feedback_type' => $data['feedback_type'] ?? 'general'
    ]);
    sendResponse(['message' => 'Feedback created', 'id' => $pdo->lastInsertId()], 201);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
