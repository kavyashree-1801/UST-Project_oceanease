<?php
session_start();
include '../config.php';
header('Content-Type: application/json');

// Auth check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Manager email
$user_id = $_SESSION['user_id'];
$userStmt = $con->prepare("SELECT email FROM users WHERE id=?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

// Fetch salon bookings
$sql = "
    SELECT s.service_name, s.booking_date, s.status, u.name
    FROM salon_bookings s
    JOIN users u ON s.user_id = u.id
    ORDER BY s.created_at DESC
";
$result = $con->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    'manager_email' => $user['email'],
    'data' => $data
]);
