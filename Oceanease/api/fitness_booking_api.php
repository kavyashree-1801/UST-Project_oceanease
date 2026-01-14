<?php
session_start();
require_once "../config.php";

header("Content-Type: application/json");

/* ================= AUTH ================= */
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "error"  => "Unauthorized"
    ]);
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$action  = $_REQUEST['action'] ?? '';

/* =========================================================
   AUTO MARK PAST BOOKINGS AS COMPLETED
   ========================================================= */
$con->query("
    UPDATE fitness_bookings
    SET status = 'completed'
    WHERE CONCAT(booking_date, ' ', booking_time) < NOW()
      AND status = 'confirmed'
");

/* =========================================================
   GET BOOKINGS
   ========================================================= */
if ($action === "get") {

    // SINGLE (EDIT)
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        $stmt = $con->prepare("
            SELECT
                id,
                workout_type,
                booking_date,
                booking_time,
                status
            FROM fitness_bookings
            WHERE id=? AND user_id=?
        ");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();

        echo json_encode([
            "status" => "success",
            "data"   => $stmt->get_result()->fetch_assoc()
        ]);
        exit;
    }

    // ALL BOOKINGS
    $stmt = $con->prepare("
        SELECT
            id,
            workout_type,
            booking_date,
            booking_time,
            status
        FROM fitness_bookings
        WHERE user_id=?
        ORDER BY booking_date DESC, booking_time DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $rows = [];
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "data"   => $rows
    ]);
    exit;
}

/* =========================================================
   CREATE BOOKING
   ========================================================= */
if ($action === "book") {

    $workout_type = trim($_POST['workout_type']);
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];

    $stmt = $con->prepare("
        INSERT INTO fitness_bookings
        (user_id, workout_type, booking_date, booking_time, status)
        VALUES (?, ?, ?, ?, 'confirmed')
    ");
    $stmt->bind_param(
        "isss",
        $user_id,
        $workout_type,
        $booking_date,
        $booking_time
    );

    echo json_encode([
        "status" => $stmt->execute() ? "success" : "error"
    ]);
    exit;
}

/* =========================================================
   EDIT BOOKING
   ========================================================= */
if ($action === "edit") {

    $id           = (int) $_POST['booking_id'];
    $workout_type = trim($_POST['workout_type']);
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];

    $stmt = $con->prepare("
        UPDATE fitness_bookings
        SET workout_type=?, booking_date=?, booking_time=?
        WHERE id=? AND user_id=? AND status != 'cancelled'
    ");
    $stmt->bind_param(
        "sssii",
        $workout_type,
        $booking_date,
        $booking_time,
        $id,
        $user_id
    );

    echo json_encode([
        "status" => $stmt->execute() ? "success" : "error"
    ]);
    exit;
}

/* =========================================================
   CANCEL BOOKING
   ========================================================= */
if ($action === "cancel") {

    $id = (int) $_GET['id'];

    $stmt = $con->prepare("
        UPDATE fitness_bookings
        SET status='cancelled'
        WHERE id=? AND user_id=?
    ");
    $stmt->bind_param("ii", $id, $user_id);

    echo json_encode([
        "status" => $stmt->execute() ? "success" : "error"
    ]);
    exit;
}

/* =========================================================
   INVALID ACTION
   ========================================================= */
echo json_encode([
    "status" => "error",
    "error"  => "Invalid action"
]);
exit;
