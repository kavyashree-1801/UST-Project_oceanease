<?php
session_start();
include '../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','message'=>'Login required']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action  = $_GET['action'] ?? '';

// GET USERNAME
$userStmt = $con->prepare("SELECT name FROM users WHERE id=?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();
$username = $user['name'] ?? 'User';

/* ================= AUTO COMPLETE (3 HOURS PAST ORDERS) ================= */
$con->query("
    UPDATE stationery_orders
    SET status='completed'
    WHERE status='confirmed'
    AND TIMESTAMP(order_date, order_time) <= NOW() - INTERVAL 3 HOUR
");

/* ================= PLACE ORDER ================= */
if ($action === 'place_order') {

    $input  = json_decode(file_get_contents('php://input'), true);
    $orders = $input['orders'] ?? [];

    if (!$orders) {
        echo json_encode(['status'=>'error','message'=>'No items selected']);
        exit;
    }

    // Prepare statement
    $stmt = $con->prepare("
        INSERT INTO stationery_orders
        (item_name, quantity, price, ordered_by, order_date, order_time, status)
        VALUES (?, ?, ?, ?, CURDATE(), CURTIME(), 'confirmed')
    ");

    $count = 0;

    foreach ($orders as $o) {
        if ((int)$o['quantity'] <= 0) continue;

        $stmt->bind_param(
            "sids",
            $o['item_name'],
            $o['quantity'],
            $o['price'],
            $username
        );

        if ($stmt->execute()) $count++;
    }

    echo json_encode([
        'status'  => $count ? 'success' : 'error',
        'message' => $count ? 'Order placed successfully' : 'Nothing ordered'
    ]);
    exit;
}

/* ================= GET ORDERS ================= */
if ($action === 'get_orders') {

    $stmt = $con->prepare("
        SELECT
            id,
            item_name,
            quantity,
            price,
            ordered_by,
            COALESCE(NULLIF(status,''),'confirmed') AS status,
            DATE(order_date) AS order_date,
            TIME(order_time) AS order_time
        FROM stationery_orders
        WHERE ordered_by=?
        ORDER BY order_date DESC, order_time DESC
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data'   => $orders
    ]);
    exit;
}

/* ================= CANCEL ORDER ================= */
if ($action === 'cancel_order') {

    $id = intval($_GET['id'] ?? 0);

    $stmt = $con->prepare("
        UPDATE stationery_orders
        SET status='cancelled'
        WHERE id=? AND ordered_by=? AND status='confirmed'
    ");
    $stmt->bind_param("is", $id, $username);
    $stmt->execute();

    echo json_encode([
        'status'  => 'success',
        'message' => 'Order cancelled'
    ]);
    exit;
}

echo json_encode(['status'=>'error','message'=>'Invalid action']);
