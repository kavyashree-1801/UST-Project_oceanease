<?php
session_start();
include '../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'headcook') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? '';

/* FETCH ORDERS */
if ($action === 'get_orders') {
    $sql = "
        SELECT 
            o.id,
            o.order_date,
            o.total_amount,
            o.status,
            mi.item_name
        FROM orders o
        JOIN menu_items mi ON o.id = mi.id
        ORDER BY o.order_date DESC
    ";
    $result = $con->query($sql);

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode($orders);
    exit;
}

/* UPDATE STATUS */
if ($action === 'update_status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $con->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

echo json_encode(['error' => 'Invalid action']);
