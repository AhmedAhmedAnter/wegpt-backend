<?php
require_once '../../config/database.php';
require_once '../helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();

if (empty($data['name']) || empty($data['grade_id'])) {
    sendError('Name and grade_id are required');
}

try {
    $stmt = $pdo->prepare("INSERT INTO subjects (name, grade_id, specialization_id, code, description, tags) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['name'],
        $data['grade_id'],
        $data['specialization_id'] ?? null,
        $data['code'] ?? null,
        $data['description'] ?? null,
        isset($data['tags']) ? json_encode($data['tags']) : null
    ]);
    sendResponse(['message' => 'Subject created', 'id' => $pdo->lastInsertId()], 201);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
