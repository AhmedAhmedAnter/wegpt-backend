<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

authorizeAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();
validateInput($data, ['name', 'grade_id', 'term_number']);

try {
    $stmt = $pdo->prepare("INSERT INTO terms (name, grade_id, term_number) VALUES (?, ?, ?)");
    $stmt->execute([$data['name'], $data['grade_id'], $data['term_number']]);

    sendResponse(['message' => 'Term created', 'id' => $pdo->lastInsertId()], 201);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
