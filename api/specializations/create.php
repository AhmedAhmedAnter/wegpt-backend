<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

authorizeAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();
validateInput($data, ['name', 'grade_id']);

try {
    $stmt = $pdo->prepare("INSERT INTO specializations (name, grade_id) VALUES (?, ?)");
    $stmt->execute([$data['name'], $data['grade_id']]);

    sendResponse(['message' => 'Specialization created', 'id' => $pdo->lastInsertId()], 201);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
