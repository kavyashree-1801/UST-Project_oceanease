<?php
session_start();
include '../config.php'; // adjust path if needed
header('Content-Type: application/json');

// Get POST data safely
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Simple validation
if (!$name || !$email || !$message) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Name, email, and message are required.'
    ]);
    exit;
}

// Prepare and insert into database
$stmt = $con->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $con->error
    ]);
    exit;
}

$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Thank you! Your message has been sent.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to save message: ' . $stmt->error
    ]);
}

$stmt->close();
$con->close();
