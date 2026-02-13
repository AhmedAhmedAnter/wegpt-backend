<?php

/**
 * Authentication and Authorization Middleware
 */

/**
 * Validates the user's session/token and returns user data
 * For now, using a simple Header based approach (Authorization: Bearer <user_id>)
 * In production, replace with JWT verification.
 *
 * @return array The user record
 */
function authenticate()
{
    $headers = apache_request_headers();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

    if (empty($authHeader) || !preg_match('/Bearer\s+(.*)/i', $authHeader, $matches)) {
        sendError('Unauthorized: Missing or invalid token', 401);
    }

    $token = $matches[1];
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT id, role, status FROM users WHERE id = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if (!$user) {
            sendError('Unauthorized: User not found', 401);
        }

        if ($user['status'] !== 'active') {
            sendError('Forbidden: Account is ' . $user['status'], 403);
        }

        return $user;
    } catch (PDOException $e) {
        sendError('Authentication error', 500);
    }
}

/**
 * Restricts access to Admin role only
 *
 * @return array The user record
 */
function authorizeAdmin()
{
    $user = authenticate();
    if ($user['role'] !== 'admin') {
        sendError('Forbidden: Admin access required', 403);
    }
    return $user;
}

/**
 * Restricts access to students or admins (Generic Auth)
 *
 * @return array The user record
 */
function authorizeUser()
{
    return authenticate();
}
