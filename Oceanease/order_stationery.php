<?php
session_start();
include 'config.php';

// If user not logged in, send to homepage
if (!isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_SESSION['user_id']);
    $item     = $con->real_escape_string($_POST['item'] ?? '');
    $qty      = intval($_POST['qty'] ?? 0);

    // Simple validation
    if ($item === '' || $qty < 1) {
        $_SESSION['order_msg'] = "Invalid order!";
        header("Location: stationery.php");
        exit;
    }

    $stmt = $con->prepare("INSERT INTO stationery_orders (user_id, item, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $item, $qty);

    if ($stmt->execute()) {
        $_SESSION['order_msg'] = "Order placed successfully!";
    } else {
        $_SESSION['order_msg'] = "Failed to place order!";
    }

    $stmt->close();
    header("Location: stationery.php");
    exit;
}
?>
