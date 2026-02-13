<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

authorizeAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();
$id = $_GET['id'] ?? $data['id'] ?? null;

if (!$id) {
    sendError('Specialization ID is required');
}

try {
    $fields = [];
    $params = [];
    $allowedFields = ['name', 'grade_id'];

    foreach ($allowedFields as $field) {
        if (isset($data[$field])) {
            $fields[] = "$field = :$field";
            $params[$field] = $data[$field];
        }
    }

    if (empty($fields)) {
        sendError('No fields to update');
    }

    $params['id'] = $id;
    $sql = "UPDATE specializations SET " . implode(', ', $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    sendResponse(['message' => 'Specialization updated']);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
