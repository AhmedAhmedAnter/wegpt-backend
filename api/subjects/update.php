<?php
require_once '../../config/database.php';
require_once '../helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();
$id = $_GET['id'] ?? $data['id'] ?? null;

if (!$id) {
    sendError('Subject ID is required');
}

try {
    $sql = "UPDATE subjects SET name = :name, grade_id = :grade_id, specialization_id = :spec_id, 
            code = :code, description = :desc, tags = :tags, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name' => $data['name'],
        'grade_id' => $data['grade_id'],
        'spec_id' => $data['specialization_id'] ?? null,
        'code' => $data['code'] ?? null,
        'desc' => $data['description'] ?? null,
        'tags' => isset($data['tags']) ? json_encode($data['tags']) : null,
        'id' => $id
    ]);
    sendResponse(['message' => 'Subject updated']);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
