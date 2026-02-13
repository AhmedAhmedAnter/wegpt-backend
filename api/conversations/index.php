<?php
require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

// Check if logged in
authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

// Basic auth check would go here
$userId = $_GET['user_id'] ?? null;

if (!$userId) {
    sendError('User ID is required');
}

try {
    $stmt = $pdo->prepare("SELECT * FROM conversations WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    $conversations = $stmt->fetchAll();
    sendResponse($conversations);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
