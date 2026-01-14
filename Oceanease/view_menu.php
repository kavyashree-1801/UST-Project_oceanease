<?php
session_start();
include 'config.php';

/* Auth: Head Cook only */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'headcook') {
    header("Location: auth.php");
    exit;
}

/* Fetch head cook email */
$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$email = $stmt->get_result()->fetch_assoc()['email'];
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Catering Menu | Head Cook</title>
<link rel="stylesheet" href="css/view_menu.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" height="40">
        <span class="logo-text">OceanEase</span>
    </div>

    <nav>
        <a href="headcook_dashboard.php">Dashboard</a>
        <a href="view_catering_orders.php">Orders</a>
        <a href="view_menu.php" class="active">Menu Items</a>
    </nav>

    <div class="user-actions">
        <div class="user-email">Hello, <?= htmlspecialchars($email) ?> 👨‍🍳</div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- Hero -->
<div class="hero">
    <div>
        <h1>Catering Menu</h1>
        <p>Check all menu items for catering management.</p>
    </div>
</div>

<!-- Menu Items -->
<div class="container">
    <h2>Catering Menu</h2>
    <div class="grid" id="menuGrid"></div>
</div>

<footer>
    &copy; <?= date("Y") ?> @OceanEase | All rights reserved.
</footer>

<script src="js/view_menu.js"></script>
</body>
</html>
