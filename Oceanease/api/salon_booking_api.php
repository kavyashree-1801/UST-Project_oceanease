<?php
session_start();
include "../config.php";
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','error'=>'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action  = $_REQUEST['action'] ?? '';

/* ================= AUTO COMPLETE PAST BOOKINGS ================= */
$con->query("
    UPDATE salon_bookings
    SET status='completed'
    WHERE CONCAT(booking_date,' ',booking_time) < NOW()
    AND status NOT IN ('cancelled','completed')
");

/* ================= GET BOOKINGS ================= */
if ($action === "get") {
    $stmt = $con->prepare("
        SELECT *
        FROM salon_bookings
        WHERE user_id=?
        ORDER BY booking_date DESC, booking_time DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $data = [];
    while($row = $res->fetch_assoc()){
        $data[] = $row;
    }

    echo json_encode(['status'=>'success','data'=>$data]);
    exit;
}

/* ================= ADD / EDIT ================= */
if ($action === "add") {
    $id = $_POST['booking_id'] ?? 0;
    $service = $_POST['service_name'];
    $date = $_POST['booking_date'];
    $time = $_POST['booking_time'];

    if($id){ // edit
        $stmt = $con->prepare("
            UPDATE salon_bookings
            SET service_name=?, booking_date=?, booking_time=?, status='confirmed'
            WHERE id=? AND user_id=?
        ");
        $stmt->bind_param("ssiii",$service,$date,$time,$id,$user_id);
        $stmt->execute();
        echo json_encode(['status'=>'success']);
        exit;
    }

    // new booking
    $stmt = $con->prepare("
        INSERT INTO salon_bookings (user_id, service_name, booking_date, booking_time, status)
        VALUES (?,?,?,?, 'confirmed')
    ");
    $stmt->bind_param("isss",$user_id,$service,$date,$time);
    $stmt->execute();
    echo json_encode(['status'=>'success']);
    exit;
}

/* ================= CANCEL ================= */
if ($action === "cancel") {
    $id = $_POST['booking_id'] ?? 0;
    $stmt = $con->prepare("
        UPDATE salon_bookings
        SET status='cancelled'
        WHERE id=? AND user_id=?
    ");
    $stmt->bind_param("ii",$id,$user_id);
    $stmt->execute();
    echo json_encode(['status'=>'success']);
    exit;
}

echo json_encode(['status'=>'error','error'=>'Invalid action']);
