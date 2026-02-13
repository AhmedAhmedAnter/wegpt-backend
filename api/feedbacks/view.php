<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once "../auth_middleware.php";
authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

$id = $_GET['id'] ?? null;

if (!$id) {
    sendError('Feedback ID is required');
}

try {
    $stmt = $pdo->prepare("SELECT * FROM feedbacks WHERE id = ?");
    $stmt->execute([$id]);
    $feedback = $stmt->fetch();

    if (!$feedback) {
        sendError('Feedback not found', 404);
    }

    sendResponse($feedback);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
