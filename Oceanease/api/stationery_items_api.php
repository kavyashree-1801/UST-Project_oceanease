<?php
session_start();
header('Content-Type: application/json');
include '../config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'list') {

    $result = $con->query("
        SELECT id, item, quantity_in_stock, image, price
        FROM stationery_items
        ORDER BY created_at DESC
    ");

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    echo json_encode($items);
    exit;
}

echo json_encode(['error' => 'Invalid action']);
