<?php
session_start();
include '../config.php';
header('Content-Type: application/json');

/* Auth check */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'headcook') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

/* Fetch all menu items */
$result = $con->query("SELECT id, item_name, category, description, price, image FROM menu_items ORDER BY item_name");

$menu = [];
while ($row = $result->fetch_assoc()) {
    $menu[] = $row;
}

echo json_encode($menu);
