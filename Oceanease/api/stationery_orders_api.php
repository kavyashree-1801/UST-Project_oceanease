<?php
session_start();
header('Content-Type: application/json');

/* ================= CONFIG ================= */
include __DIR__ . '/../config.php';

/* ================= AUTH CHECK ================= */
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? '';

/* ================= USER INFO ================= */
if ($action === 'user') {

    $stmt = $con->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    echo json_encode([
        'name' => $res['name'] ?? 'User'
    ]);
    exit;
}

/* ================= LIST ORDERS ================= */
if ($action === 'list') {

    $sql = "
        SELECT 
            id,
            item_name,
            quantity,
            ordered_by,
            order_date,
            status,
            price
        FROM stationery_orders
        ORDER BY order_date DESC
    ";

    $result = $con->query($sql);

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode($orders);
    exit;
}

/* ================= DELETE ORDER ================= */
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['error' => 'Invalid order ID']);
        exit;
    }

    $stmt = $con->prepare("DELETE FROM stationery_orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(['success' => true]);
    exit;
}

/* ================= INVALID ACTION ================= */
echo json_encode(['error' => 'Invalid action']);
exit;
