<?php
session_start();
header('Content-Type: application/json');
include '../config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ================= AUTH ================= */
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action  = $_GET['action'] ?? '';

/* ================= GET USER NAME ================= */
$u = $con->prepare("SELECT name FROM users WHERE id=?");
$u->bind_param("i", $user_id);
$u->execute();
$userRow = $u->get_result()->fetch_assoc();
$username = $userRow['name'] ?? 'User';

/* =====================================================
   AUTO STATUS UPDATES
   ===================================================== */

/* 1️⃣ pending → confirmed (after 3 hours) */
$con->query("
    UPDATE orders
    SET status='confirmed'
    WHERE status='pending'
    AND TIMESTAMPDIFF(HOUR, order_date, NOW()) >= 3
");

/* 2️⃣ confirmed → completed (after date passes) */
$con->query("
    UPDATE orders
    SET status='completed'
    WHERE status='confirmed'
    AND DATE(order_date) < CURDATE()
");

/* ================= GET MENU ================= */
if ($action === 'get_menu') {
    $res = $con->query("SELECT item_name, description, price, image FROM menu_items ORDER BY created_at DESC");
    $menu = [];
    while ($row = $res->fetch_assoc()) {
        $menu[] = $row;
    }
    echo json_encode(['status'=>'success','data'=>$menu]);
    exit;
}

/* ================= PLACE ORDER ================= */
if ($action === 'place_order') {

    $items = json_decode(file_get_contents("php://input"), true);
    if (!$items || count($items) === 0) {
        echo json_encode(['status'=>'error','message'=>'No items selected']);
        exit;
    }

    $total = 0;
    $itemList = [];

    foreach ($items as $item) {
        $total += $item['price'] * $item['qty'];
        $itemList[] = $item['name'] . ' x' . $item['qty'];
    }

    $item_names = implode(', ', $itemList);

    // Status NOT set → DB default = pending
    $stmt = $con->prepare("
        INSERT INTO orders (user_id, username, item_names, total_amount, order_date)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("issd", $user_id, $username, $item_names, $total);
    $stmt->execute();

    echo json_encode(['status'=>'success','message'=>'Order placed successfully']);
    exit;
}

/* ================= GET ORDERS ================= */
if ($action === 'get_orders') {

    $stmt = $con->prepare("
        SELECT id, item_names, total_amount, status, order_date
        FROM orders
        WHERE user_id=?
        ORDER BY id DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $data = [];
    while ($row = $res->fetch_assoc()) {
        $row['username'] = $username;
        $data[] = $row;
    }

    echo json_encode(['status'=>'success','data'=>$data]);
    exit;
}

/* ================= CANCEL ORDER ================= */
if ($action === 'cancel_order') {

    $id = intval($_GET['id'] ?? 0);

    $stmt = $con->prepare("
        UPDATE orders
        SET status='cancelled'
        WHERE id=? AND user_id=? AND status='pending'
    ");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();

    echo json_encode(['status'=>'success','message'=>'Order cancelled']);
    exit;
}

/* ================= INVALID ACTION ================= */
echo json_encode(['status'=>'error','message'=>'Invalid action']);
