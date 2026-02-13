<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

// Only allow Admins to delete subjects
authorizeAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();
$id = $_GET['id'] ?? $data['id'] ?? null;

if (!$id) {
    sendError('Subject ID is required');
}

try {
    $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        sendError('Subject not found or already deleted', 404);
    }

    sendResponse(['message' => 'Subject deleted successfully']);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
