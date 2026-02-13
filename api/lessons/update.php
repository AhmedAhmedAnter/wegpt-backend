<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

// Only allow Admins to update lessons
authorizeAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();
$id = $_GET['id'] ?? $data['id'] ?? null;

if (!$id) {
    sendError('Lesson ID is required');
}

try {
    // Check if lesson exists
    $checkStmt = $pdo->prepare("SELECT id FROM lessons WHERE id = ?");
    $checkStmt->execute([$id]);
    if (!$checkStmt->fetch()) {
        sendError('Lesson not found', 404);
    }

    $fields = [];
    $params = [];

    $allowedFields = [
        'title',
        'subject_id',
        'grade_id',
        'specialization_id',
        'term_id',
        'author_id',
        'content_text',
        'pdf_path',
        'images_links',
        'video_url',
        'tags',
        'attachments',
        'duration',
        'difficulty_level',
        'is_published'
    ];

    foreach ($allowedFields as $field) {
        if (isset($data[$field])) {
            $fields[] = "$field = :$field";
            if (in_array($field, ['tags', 'attachments']) && is_array($data[$field])) {
                $params[$field] = json_encode($data[$field]);
            } else {
                // If the field is empty string and it's an optional INT/FK field, set to NULL
                $optionalNulls = ['specialization_id', 'author_id', 'pdf_path', 'video_url', 'images_links'];
                if (in_array($field, $optionalNulls) && $data[$field] === '') {
                    $params[$field] = null;
                } else {
                    $params[$field] = $data[$field];
                }
            }
        }
    }

    if (empty($fields)) {
        sendError('No fields to update');
    }

    $params['id'] = $id;
    $sql = "UPDATE lessons SET " . implode(', ', $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    sendResponse(['message' => 'Lesson updated successfully']);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
