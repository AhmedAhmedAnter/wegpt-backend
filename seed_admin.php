<?php

/**
 * Super Admin Seeder
 * Run this script ONCE to create your initial admin account.
 * Usage: php seed_admin.php
 */

require_once 'config/database.php';

echo "--- WeGPT Super Admin Seeder ---\n";

$name = "Admin";
$email = "admin@wegpt.com";
$password = "admin123"; // CHANGE THIS IMMEDIATELY AFTER LOGIN

try {
    // Check if exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        die("Error: An account with this email already exists.\n");
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password_hash, role, status) VALUES (?, ?, ?, 'admin', 'active')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $hash]);

    echo "✅ Success! Super Admin created.\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
    echo "-------------------------------\n";
    echo "REMINDER: Delete this file after use for security.\n";
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}
