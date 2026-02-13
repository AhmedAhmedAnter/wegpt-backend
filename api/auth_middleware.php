<?php
require_once __DIR__ . '/jwt_helper.php';

/**
 * Authentication and Authorization Middleware
 */

/**
 * Validates the user's JWT and returns user data
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
    $payload = JWT::decode($token);

    if (!$payload || !isset($payload['user_id'])) {
        sendError('Unauthorized: Invalid or expired token', 401);
    }

    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT id, role, status FROM users WHERE id = ?");
        $stmt->execute([$payload['user_id']]);
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
 */
function authorizeUser()
{
    return authenticate();
}
