<?php
require_once 'config/database.php';

header('Content-Type: application/json');

$response = [
    'status' => 'success',
    'message' => 'WeGPT Backend API is running',
    'version' => '1.0.0',
    'environment' => $_ENV['APP_MODE'] ?? 'development',
    'database' => 'connected'
];

// Verify DB connection
try {
    $pdo->query('SELECT 1');
} catch (Exception $e) {
    $response['database'] = 'disconnected';
    $response['status'] = 'error';
}

echo json_encode($response);
