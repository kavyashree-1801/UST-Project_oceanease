<?php
session_start();
include '../config.php';
header('Content-Type: application/json');

// Auth
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'headcook') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// User details
$user_id = $_SESSION['user_id'];
$userStmt = $con->prepare("SELECT name, email FROM users WHERE id=?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

// Menu items
$menuRes = $con->query("SELECT * FROM menu_items ORDER BY category, item_name");

$menu = [];
while ($row = $menuRes->fetch_assoc()) {
    $menu[] = $row;
}

echo json_encode([
    'user' => $user,
    'menu' => $menu
]);
