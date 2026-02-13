<?php

require_once '../helpers.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();

if (empty($data['email']) || empty($data['password'])) {
    sendError('Email and password are required');
}

try {
    $stmt = $pdo->prepare("SELECT id, name, email, password_hash, role FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($data['password'], $user['password_hash'])) {
        sendError('Invalid email or password', 401);
    }

    // Update last login
    $updateStmt = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
    $updateStmt->execute([$user['id']]);

    // Generate JWT
    require_once '../jwt_helper.php';
    $token = JWT::encode([
        'user_id' => $user['id'],
        'role'    => $user['role'],
        'email'   => $user['email']
    ], 86400); // 24 hours

    unset($user['password_hash']);

    sendResponse([
        'message' => 'Login successful',
        'token'   => $token,
        'user'    => $user
    ]);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
