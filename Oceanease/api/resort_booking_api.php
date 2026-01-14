<?php
session_start();
include '../config.php';
header('Content-Type: application/json');

/* ================= AUTH CHECK ================= */
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "error" => "Unauthorized"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$method  = $_SERVER['REQUEST_METHOD'];

/* ================= GET BOOKINGS ================= */
if ($method === 'GET') {

    $stmt = $con->prepare("
        SELECT 
            id,
            resort_name,
            movie_name,
            booking_date,
            booking_time,
            guests,
            status
        FROM resort_movie_bookings
        WHERE user_id = ?
        ORDER BY created_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "data" => $rows
    ]);
    exit;
}

/* ================= POST ACTIONS ================= */
if ($method === 'POST') {

    $action = $_POST['action'] ?? '';

    /* ===== CREATE BOOKING ===== */
    if ($action === 'create') {

        $resort_name  = trim($_POST['resort_name']);
        $movie_name   = trim($_POST['movie_name'] ?? null);
        $booking_date = $_POST['booking_date'];
        $booking_time = $_POST['booking_time'];
        $guests       = intval($_POST['guests']);

        $stmt = $con->prepare("
            INSERT INTO resort_movie_bookings
            (user_id, resort_name, movie_name, booking_date, booking_time, guests, status)
            VALUES (?, ?, ?, ?, ?, ?, 'confirmed')
        ");
        $stmt->bind_param(
            "issssi",
            $user_id,
            $resort_name,
            $movie_name,
            $booking_date,
            $booking_time,
            $guests
        );

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode([
                "status" => "error",
                "error" => "Failed to create booking"
            ]);
        }
        exit;
    }

    /* ===== UPDATE BOOKING ===== */
    if ($action === 'update') {

        $id            = intval($_POST['id']);
        $resort_name   = trim($_POST['resort_name']);
        $movie_name    = trim($_POST['movie_name'] ?? null);
        $booking_date  = $_POST['booking_date'];
        $booking_time  = $_POST['booking_time'];
        $guests        = intval($_POST['guests']);

        $stmt = $con->prepare("
            UPDATE resort_movie_bookings
            SET resort_name=?, movie_name=?, booking_date=?, booking_time=?, guests=?
            WHERE id=? AND user_id=? AND status NOT IN ('cancelled','completed')
        ");
        $stmt->bind_param(
            "ssssiii",
            $resort_name,
            $movie_name,
            $booking_date,
            $booking_time,
            $guests,
            $id,
            $user_id
        );

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode([
                "status" => "error",
                "error" => "Failed to update booking"
            ]);
        }
        exit;
    }

    /* ===== UPDATE STATUS ===== */
    if ($action === 'status') {

        $id     = intval($_POST['id']);
        $status = strtolower($_POST['status']); // IMPORTANT

        if (!in_array($status, ['pending','confirmed','cancelled','completed'])) {
            echo json_encode([
                "status" => "error",
                "error" => "Invalid status"
            ]);
            exit;
        }

        $stmt = $con->prepare("
            UPDATE resort_movie_bookings
            SET status=?
            WHERE id=? AND user_id=?
        ");
        $stmt->bind_param("sii", $status, $id, $user_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode([
                "status" => "error",
                "error" => "Failed to update status"
            ]);
        }
        exit;
    }

    echo json_encode([
        "status" => "error",
        "error" => "Invalid action"
    ]);
    exit;
}
