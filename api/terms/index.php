<?php

require_once '../../config/database.php';
require_once '../helpers.php';
require_once "../auth_middleware.php";
authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method Not Allowed', 405);
}

try {
    $stmt = $pdo->query("SELECT * FROM terms ORDER BY term_number ASC");
    $terms = $stmt->fetchAll();
    sendResponse($terms);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
