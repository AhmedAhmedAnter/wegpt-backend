<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once "../auth_middleware.php";
authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

// Get ID from query parameter or URL
$id = $_GET['id'] ?? null;

if (!$id) {
    sendError('User ID is required');
}

try {
    $stmt = $pdo->prepare("SELECT id, name, email, role, grade_id, specialization_id, birth_date, phone, gender, avatar_url, status, settings, last_login, created_at FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if (!$user) {
        sendError('User not found', 404);
    }

    sendResponse($user);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
