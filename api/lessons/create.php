<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once "../auth_middleware.php";
authorizeAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();

if (empty($data['title']) || empty($data['subject_id']) || empty($data['grade_id']) || empty($data['term_id'])) {
    sendError('Title, subject_id, grade_id, and term_id are required');
}

try {
    $sql = "INSERT INTO lessons (title, subject_id, grade_id, specialization_id, term_id, author_id, content_text, pdf_path, images_links, video_url, tags, attachments, duration, difficulty_level, is_published) 
            VALUES (:title, :subject_id, :grade_id, :specialization_id, :term_id, :author_id, :content_text, :pdf_path, :images_links, :video_url, :tags, :attachments, :duration, :difficulty_level, :is_published)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'title' => $data['title'],
        'subject_id' => $data['subject_id'],
        'grade_id' => $data['grade_id'],
        'specialization_id' => $data['specialization_id'] ?? null,
        'term_id' => $data['term_id'],
        'author_id' => $data['author_id'] ?? null,
        'content_text' => $data['content_text'] ?? null,
        'pdf_path' => $data['pdf_path'] ?? null,
        'images_links' => $data['images_links'] ?? null,
        'video_url' => $data['video_url'] ?? null,
        'tags' => isset($data['tags']) ? json_encode($data['tags']) : null,
        'attachments' => isset($data['attachments']) ? json_encode($data['attachments']) : null,
        'duration' => $data['duration'] ?? null,
        'difficulty_level' => $data['difficulty_level'] ?? 'medium',
        'is_published' => $data['is_published'] ?? 1
    ]);

    sendResponse(['message' => 'Lesson created', 'id' => $pdo->lastInsertId()], 201);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
