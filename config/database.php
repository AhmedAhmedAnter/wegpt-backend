<?php
require_once __DIR__ . '/env_loader.php';

/**
 * Database Configuration
 * Uses PDO for secure database interactions
 */

$db_config = [
    'host'     => $_ENV['DB_HOST'] ?? 'localhost',
    'dbname'   => $_ENV['DB_NAME'] ?? 'backend_db',
    'username' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASS'] ?? '',
    'charset'  => 'utf8mb4'
];

try {
    $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']}";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $db_config['username'], $db_config['password'], $options);
} catch (\PDOException $e) {
    if (($_ENV['APP_MODE'] ?? 'development') === 'development') {
        die("Database connection failed: " . $e->getMessage());
    }
    die("Database connection failed: Connection refused.");
}
