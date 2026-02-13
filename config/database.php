<?php

/**
 * Database Configuration
 * Uses PDO for secure database interactions
 */

$db_config = [
    'host'     => 'localhost',
    'dbname'   => 'backend_db',
    'username' => 'root',
    'password' => '',
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
    // In production, you would log this and show a generic message
    die("Database connection failed: " . $e->getMessage());
}
