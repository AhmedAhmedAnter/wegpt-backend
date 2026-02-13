<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once "../auth_middleware.php";
$currUser = authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();
$id = $_GET['id'] ?? $data['id'] ?? null;

if (!$id) {
    sendError('User ID is required');
}

// Ownership Check: Users can only update themselves unless they are admin
if ($currUser['id'] != $id && $currUser['role'] !== 'admin') {
    sendError('Forbidden: Access denied', 403);
}

try {
    // Only update provided fields
    $fields = [];
    $params = [];

    $allowedFields = ['name', 'grade_id', 'specialization_id', 'birth_date', 'phone', 'gender', 'avatar_url', 'status', 'settings'];

    foreach ($allowedFields as $field) {
        if (isset($data[$field])) {
            $fields[] = "$field = :$field";
            $params[$field] = $field === 'settings' ? json_encode($data[$field]) : $data[$field];
        }
    }

    if (empty($fields)) {
        sendError('No fields to update');
    }

    $params['id'] = $id;
    $sql = "UPDATE users SET " . implode(', ', $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    sendResponse(['message' => 'User updated']);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
