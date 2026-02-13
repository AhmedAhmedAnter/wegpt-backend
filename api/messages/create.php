<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once "../auth_middleware.php";
authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();

if (empty($data['conversation_id']) || empty($data['role']) || empty($data['content_text'])) {
    sendError('conversation_id, role, and content_text are required');
}

try {
    $sql = "INSERT INTO messages (conversation_id, role, content_text, pdf_path, images_links, reactions, attachments) 
            VALUES (:conversation_id, :role, :content_text, :pdf_path, :images_links, :reactions, :attachments)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'conversation_id' => $data['conversation_id'],
        'role' => $data['role'], // 'student' or 'AI'
        'content_text' => $data['content_text'],
        'pdf_path' => $data['pdf_path'] ?? null,
        'images_links' => $data['images_links'] ?? null,
        'reactions' => isset($data['reactions']) ? json_encode($data['reactions']) : null,
        'attachments' => isset($data['attachments']) ? json_encode($data['attachments']) : null
    ]);

    sendResponse(['message' => 'Message sent', 'id' => $pdo->lastInsertId()], 201);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
