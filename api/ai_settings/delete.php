<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

authorizeAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();
$id = $_GET['id'] ?? $data['id'] ?? null;

if (!$id) {
    sendError('Setting ID is required');
}

try {
    $stmt = $pdo->prepare("DELETE FROM ai_settings WHERE id = ?");
    $stmt->execute([$id]);
    sendResponse(['message' => 'AI setting deleted']);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
