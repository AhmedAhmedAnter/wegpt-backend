<?php
require_once '../../config/database.php';
require_once '../helpers.php';
require_once '../auth_middleware.php';

// Only authenticated users can upload
authorizeUser();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method Not Allowed', 405);
}

if (!isset($_FILES['file'])) {
    sendError('No file uploaded');
}

$file = $_FILES['file'];
$type = $_POST['type'] ?? 'general'; // 'avatar', 'lesson', or 'general'

// 1. Validate File Errors
if ($file['error'] !== UPLOAD_ERR_OK) {
    sendError('Upload failed with error code: ' . $file['error']);
}

// 2. Validate File Size (max 5MB)
if ($file['size'] > 5 * 1024 * 1024) {
    sendError('File too large. Max size is 5MB');
}

// 3. Validate Extensions
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowedExts = [
    'avatar' => ['jpg', 'jpeg', 'png', 'webp'],
    'lesson' => ['pdf', 'docx', 'pptx', 'jpg', 'png'],
    'general' => ['jpg', 'png', 'pdf', 'zip']
];

$targetExts = $allowedExts[$type] ?? $allowedExts['general'];

if (!in_array($ext, $targetExts)) {
    sendError('Invalid file type for ' . $type . '. Allowed: ' . implode(', ', $targetExts));
}

// 4. Create Directory if not exists
$targetDir = '../../uploads/' . $type . '/';
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// 5. Generate Unique Name
$newName = uniqid() . '_' . time() . '.' . $ext;
$targetPath = $targetDir . $newName;

// 6. Move File
if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    // Return relative URL for storage in DB
    $relativeUrl = '/uploads/' . $type . '/' . $newName;
    sendResponse([
        'message' => 'File uploaded successfully',
        'url' => $relativeUrl,
        'full_url' => ($_ENV['BASE_URL'] ?? 'http://localhost/backend') . $relativeUrl
    ]);
} else {
    sendError('Could not save file to disk', 500);
}
