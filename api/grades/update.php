<?php
require_once '../../config/database.php';
require_once '../helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();
$id = $_GET['id'] ?? $data['id'] ?? null;

if (!$id || empty($data['name'])) {
    sendError('Grade ID and name are required');
}

try {
    $stmt = $pdo->prepare("UPDATE grades SET name = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->execute([$data['name'], $id]);
    sendResponse(['message' => 'Grade updated']);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
