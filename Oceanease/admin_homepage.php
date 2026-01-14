<?php
session_start();
include 'config.php';

// Admin authentication
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['user_id'];

// Fetch admin email from users table
$stmt = $con->prepare("SELECT email, name FROM users WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $admin_email = "admin@example.com"; // fallback
    $admin_name  = "Admin";
} else {
    $row = $result->fetch_assoc();
    $admin_email = $row['email'];
    $admin_name  = $row['name'];
}
$stmt->close();

// Initialize counts
$bookingCount = 0;
$menuCount    = 0;
$orderCount   = 0;
$activeUsers  = 0;

// Safe queries
$q = $con->query("SELECT COUNT(*) AS total FROM party_hall_bookings");
if ($q) $bookingCount = $q->fetch_assoc()['total'];

$q = $con->query("SELECT COUNT(*) AS total FROM menu_items");
if ($q) $menuCount = $q->fetch_assoc()['total'];

$q = $con->query("SELECT COUNT(*) AS total FROM orders");
if ($q) $orderCount = $q->fetch_assoc()['total'];

$q = $con->query("SELECT COUNT(*) AS total FROM users WHERE status='active'");
if ($q) $activeUsers = $q->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | OceanEase</title>
<link href="css/admin_home.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="OceanEase Logo">
        <div class="logo-text">OceanEase</div>
    </div>

    <nav>
        <a href="admin_homepage.php" class="active">Dashboard</a>
        <a href="manage_menu.php">Menu Items</a>
        <a href="manage_voyagers.php">Voyagers</a>
    </nav>

    <div class="user-actions">
        <div class="user-email">Hello,<?= htmlspecialchars($admin_email) ?>🧑‍💻</div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div style="text-align:center; margin: 30px 0;">
    <h2>Welcome, <?= htmlspecialchars($admin_name) ?>!</h2>
    <p>Here is the overview of your cruise services.</p>
</div>

<div class="dashboard-container">

    <!-- Total Bookings -->
    <div class="card">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRKkdE98zRT_-2bk-4jK2RWcia9zLoC5evHhw&s" alt="Bookings">
        <h3>Total Bookings</h3>
        <p><?= $bookingCount ?></p>
    </div>

    <!-- Total Menu Items -->
    <div class="card">
        <img src="https://images.pexels.com/photos/8790962/pexels-photo-8790962.jpeg" alt="Menu Items">
        <h3>Total Menu Items</h3>
        <p><?= $menuCount ?></p>
    </div>

    <!-- Total Orders -->
    <div class="card">
        <img src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?w=400" alt="Orders">
        <h3>Total Orders</h3>
        <p><?= $orderCount ?></p>
    </div>

    <!-- Active Users -->
    <div class="card">
        <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=400" alt="Active Users">
        <h3>Active Users</h3>
        <p><?= $activeUsers ?></p>
    </div>

</div>

<footer>
    &copy; <?= date("Y") ?> @OceanEase | All rights reserved.
</footer>

</body>
</html>
