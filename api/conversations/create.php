<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once "../auth_middleware.php";
authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();

if (empty($data['user_id'])) {
    sendError('User ID is required');
}

try {
    $sql = "INSERT INTO conversations (user_id, subject_id, term_id, lessons_ids, context, ai_model_version) 
            VALUES (:user_id, :subject_id, :term_id, :lessons_ids, :context, :ai_model_version)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'user_id' => $data['user_id'],
        'subject_id' => $data['subject_id'] ?? null,
        'term_id' => $data['term_id'] ?? null,
        'lessons_ids' => isset($data['lessons_ids']) ? json_encode($data['lessons_ids']) : null,
        'context' => $data['context'] ?? null,
        'ai_model_version' => $data['ai_model_version'] ?? 'gpt-4'
    ]);

    sendResponse(['message' => 'Conversation created', 'id' => $pdo->lastInsertId()], 201);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
