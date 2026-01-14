<?php
session_start();
header('Content-Type: application/json');
include '../config.php'; // adjust path if needed

/* ================= AUTH CHECK ================= */
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'manager'
) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

/* ================= MANAGER EMAIL ================= */
$user_id = $_SESSION['user_id'];
$userStmt = $con->prepare("SELECT email FROM users WHERE id=?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

/* ================= FITNESS BOOKINGS ================= */
$sql = "
    SELECT 
        f.workout_type,
        f.booking_date,
        f.booking_time,
        f.status,
        u.name
    FROM fitness_bookings f
    JOIN users u ON f.user_id = u.id
    ORDER BY f.created_at DESC
";

$result = $con->query($sql);

if (!$result) {
    echo json_encode([
        'error' => 'Query failed',
        'details' => $con->error
    ]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

/* ================= RESPONSE ================= */
echo json_encode([
    'manager_email' => $user['email'],
    'data' => $data
]);
