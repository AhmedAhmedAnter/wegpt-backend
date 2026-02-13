<?php
require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

// Only Admins can create grades
authorizeAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();

if (empty($data['name'])) {
    sendError('Grade name is required');
}

try {
    $stmt = $pdo->prepare("INSERT INTO grades (name) VALUES (?)");
    $stmt->execute([$data['name']]);
    sendResponse(['message' => 'Grade created', 'id' => $pdo->lastInsertId()], 201);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
