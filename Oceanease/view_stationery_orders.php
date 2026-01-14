<?php
session_start();
include 'config.php';

/* ================= AUTH CHECK ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supervisor') {
    header("Location: auth.php");
    exit;
}

/* ================= FETCH SUPERVISOR DETAILS ================= */
$user_id = $_SESSION['user_id'];
$userQuery = $con->prepare("SELECT name, email FROM users WHERE id=?");
$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$user = $userQuery->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Ordered Stationery | OceanEase</title>
<link rel="stylesheet" href="css/view_stationery.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="page-wrapper">

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="OceanEase Logo">
        <div class="logo-text">OceanEase</div>
    </div>
    <nav>
        <a href="supervisor_dashboard.php">Dashboard</a>
        <a href="view_stationery_orders.php" class="active">Inventory</a>
        <a href="view_stationery_items.php">Stationery Items</a>
    </nav>
    <div class="user-info">
        <span>Hello, <?= htmlspecialchars($user['email']) ?> 🗂️</span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- CONTENT -->
<div class="content">
    <div class="hero">Ordered Stationery Items</div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Ordered By</th>
                    <th>Price</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ordersTable">
                <!-- JS will load orders dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- FOOTER -->
<footer>
    &copy; <?= date("Y") ?> @OceanEase | All rights reserved.
</footer>

<script defer src="js/view_stationery.js"></script>
</body>
</html>
