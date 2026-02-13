<?php
require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

// Only allow Admins to see all users
authorizeAdmin();

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

try {
    $stmt = $pdo->query("SELECT id, name, email, role, status, created_at FROM users");
    $users = $stmt->fetchAll();

    sendResponse($users);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
