<?php
session_start();
include '../config.php';
header("Content-Type: application/json");

if(!isset($_SESSION['user_id'])){
    echo json_encode(['status'=>'error','error'=>'Unauthorized']); exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if($action==='book'){
    $stmt = $con->prepare("INSERT INTO party_hall_bookings (user_id,hall_name,event_type,booking_date,booking_time,guests,status) VALUES (?,?,?,?,?,?,'confirmed')");
    $stmt->bind_param("issssi",$user_id,$_POST['hall_name'],$_POST['event_type'],$_POST['booking_date'],$_POST['booking_time'],$_POST['guests']);
    $stmt->execute();
    echo json_encode(['status'=>'success']); exit;
}

if($action==='update'){
    $stmt = $con->prepare("UPDATE party_hall_bookings SET hall_name=?,event_type=?,booking_date=?,booking_time=?,guests=? WHERE id=? AND user_id=? AND status='confirmed'");
    $stmt->bind_param("ssssiii",$_POST['hall_name'],$_POST['event_type'],$_POST['booking_date'],$_POST['booking_time'],$_POST['guests'],$_POST['id'],$user_id);
    $stmt->execute();
    echo json_encode(['status'=>'success']); exit;
}

if($action==='cancel'){
    $stmt = $con->prepare("UPDATE party_hall_bookings SET status='cancelled' WHERE id=? AND user_id=? AND status='confirmed'");
    $stmt->bind_param("ii",$_POST['id'],$user_id);
    $stmt->execute();
    echo json_encode(['status'=>'success']); exit;
}

if(isset($_GET['id'])){
    $stmt = $con->prepare("SELECT * FROM party_hall_bookings WHERE id=? AND user_id=?");
    $stmt->bind_param("ii",$_GET['id'],$user_id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    echo json_encode($data?['status'=>'success','data'=>$data]:['status'=>'error','error'=>'Not found']); exit;
}

$stmt = $con->prepare("SELECT * FROM party_hall_bookings WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$now = new DateTime();
foreach($bookings as &$b){
    if($b['status']==='confirmed'){
        $bookingDT = new DateTime($b['booking_date'].' '.$b['booking_time']);
        if($bookingDT<$now){
            $update = $con->prepare("UPDATE party_hall_bookings SET status='completed' WHERE id=?");
            $update->bind_param("i",$b['id']);
            $update->execute();
            $b['status']='completed';
        }
    }
}

echo json_encode(['status'=>'success','data'=>$bookings]);
