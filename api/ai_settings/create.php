<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

authorizeAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();
validateInput($data, ['key_name', 'value']);

try {
    $stmt = $pdo->prepare("INSERT INTO ai_settings (key_name, value, description, category, is_active) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['key_name'],
        $data['value'],
        $data['description'] ?? null,
        $data['category'] ?? 'general',
        $data['is_active'] ?? 1
    ]);

    sendResponse(['message' => 'AI setting created', 'id' => $pdo->lastInsertId()], 201);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
