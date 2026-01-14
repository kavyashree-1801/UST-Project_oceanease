<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: stationery.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get username for orders table
$username_stmt = $con->prepare("SELECT name FROM users WHERE id=?");
$username_stmt->bind_param("i", $user_id);
$username_stmt->execute();
$user = $username_stmt->get_result()->fetch_assoc();
$username_stmt->close();
$username = $user['name'];

// Ensure quantities are received
if (!isset($_POST['quantity']) || !is_array($_POST['quantity'])) {
    die("No quantities received.");
}

$quantities = $_POST['quantity'];
$order_date = date("Y-m-d H:i:s");
$status = "Pending";

// Insert each item into stationery_orders
foreach($quantities as $item_id => $qty){
    if($qty > 0){
        // Fetch item details from stationery_items
        $item_stmt = $con->prepare("SELECT item, price, quantity_in_stock FROM stationery_items WHERE id=?");
        $item_stmt->bind_param("i", $item_id);
        $item_stmt->execute();
        $item_data = $item_stmt->get_result()->fetch_assoc();
        $item_stmt->close();

        if($qty > $item_data['quantity_in_stock']){
            die("Quantity for {$item_data['item']} exceeds stock.");
        }

        $stmt = $con->prepare("INSERT INTO stationery_orders (item_name, quantity, ordered_by, order_date, status, price) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("siissi", $item_data['item'], $qty, $user_id, $order_date, $status, $item_data['price']);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: stationery.php?message=Order placed successfully!");
exit;
