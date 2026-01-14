<?php
session_start();
include '../config.php'; // adjust path if needed
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in to submit feedback.'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation
if (!$name || !$email || !$message) {
    echo json_encode([
        'success' => false,
        'message' => 'Name, email, and message are required.'
    ]);
    exit;
}

// Prepare insert statement
$stmt = $con->prepare("INSERT INTO feedback (user_id, name, email, message) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $con->error
    ]);
    exit;
}

$stmt->bind_param("isss", $user_id, $name, $email, $message);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your feedback has been submitted.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save feedback: ' . $stmt->error
    ]);
}

$stmt->close();
$con->close();
