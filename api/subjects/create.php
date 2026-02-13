<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

// Only allow Admins to create subjects
authorizeAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();

if (empty($data['name']) || empty($data['grade_id'])) {
    sendError('Name and grade_id are required');
}

try {
    $sql = "INSERT INTO subjects (name, grade_id, specialization_id, code, description, tags) 
            VALUES (:name, :grade_id, :spec_id, :code, :desc, :tags)";

    $stmt = $pdo->prepare($sql);

    // Smartly handle empty strings as NULL for optional fields
    $params = [
        'name'     => $data['name'],
        'grade_id' => $data['grade_id'],
        'spec_id'  => !empty($data['specialization_id']) ? $data['specialization_id'] : null,
        'code'     => !empty($data['code']) ? $data['code'] : null,
        'desc'     => !empty($data['description']) ? $data['description'] : null,
        'tags'     => !empty($data['tags']) ? json_encode($data['tags']) : null
    ];

    $stmt->execute($params);

    sendResponse([
        'message' => 'Subject created successfully',
        'id' => $pdo->lastInsertId()
    ], 201);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
