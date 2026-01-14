<?php
session_start();
include '../config.php';
header('Content-Type: application/json');

// Auth check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Fetch manager email
$user_id = $_SESSION['user_id'];
$userStmt = $con->prepare("SELECT email FROM users WHERE id=?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

// Fetch bookings
$sql = "
    SELECT r.resort_name, r.movie_name, r.booking_date, r.status, u.name
    FROM resort_movie_bookings r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
";
$result = $con->query($sql);

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode([
    'manager_email' => $user['email'],
    'data' => $bookings
]);
