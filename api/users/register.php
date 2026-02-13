<?php

require_once '../helpers.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

$data = getJsonInput();

// Basic validation
if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
    sendError('Missing required fields: name, email, password');
}

// Validate email format
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    sendError('Invalid email format');
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        sendError('Email already registered', 409);
    }

    // Hash password
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

    // Prepare insert query
    $sql = "INSERT INTO users (name, email, password_hash, role, grade_id, specialization_id, birth_date, phone, gender) 
            VALUES (:name, :email, :password_hash, :role, :grade_id, :specialization_id, :birth_date, :phone, :gender)";

    $stmt = $pdo->prepare($sql);

    $params = [
        'name' => $data['name'],
        'email' => $data['email'],
        'password_hash' => $passwordHash,
        'role' => $data['role'] ?? 'student',
        'grade_id' => $data['grade_id'] ?? null,
        'specialization_id' => $data['specialization_id'] ?? null,
        'birth_date' => $data['birth_date'] ?? null,
        'phone' => $data['phone'] ?? null,
        'gender' => $data['gender'] ?? null
    ];

    $stmt->execute($params);
    $userId = $pdo->lastInsertId();

    sendResponse([
        'message' => 'User registered successfully',
        'user_id' => $userId
    ], 201);
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
}
