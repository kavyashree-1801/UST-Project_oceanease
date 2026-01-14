<?php
session_start();
include 'config.php';

/* ================= AUTH CHECK ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supervisor') {
    header("Location: auth.php");
    exit;
}

/* ================= FETCH SUPERVISOR EMAIL ================= */
$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$email = $stmt->get_result()->fetch_assoc()['email'] ?? 'Supervisor';
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Stationery Items | Supervisor</title>
<link rel="stylesheet" href="css/view_stationery_items.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="page-wrapper">

    <!-- ================= NAVBAR ================= -->
    <div class="navbar">
        <div class="logo-container">
            <img src="images/oceanease.png" alt="Logo">
            <div class="logo-text">OceanEase</div>
        </div>
        <nav>
            <a href="supervisor_dashboard.php">Dashboard</a>
            <a href="view_stationery_orders.php">Inventory</a>
            <a href="view_stationery_items.php" class="active">Stationery Items</a>
        </nav>
        <div class="user-info">
            <span>Hello, <?= htmlspecialchars($email) ?> 🗂️</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <!-- ================= HERO ================= -->
    <div class="hero">
        <h1>Stationery Items</h1>
    </div>

    <!-- ================= SINGLE STRETCHED CARD ================= -->
    <div class="overview-card">
        <img src="https://png.pngtree.com/thumb_back/fw800/background/20231008/pngtree-back-to-school-stationery-set-isolated-on-white-background-3d-render-image_13560058.png" alt="Stationery">
        <div class="card-text">
            <h2>Inventory Overview</h2>
            <p>Total Items: <span id="totalItems">0</span></p>
            <p>Items In Stock: <span id="inStock">0</span></p>
            <p>Keep track of all stationery products and their availability in real-time.</p>
        </div>
    </div>

    <!-- ================= ITEMS GRID ================= -->
    <h2 style="margin:30px 20px 10px 20px;">Stationery Items</h2>
    <div class="grid" id="cardsContainer"></div>

    <!-- ================= FOOTER ================= -->
    <footer>
        &copy; <?= date("Y") ?> @OceanEase | All rights reserved.
    </footer>

</div>

<script src="js/view_stationery_items.js"></script>
</body>
</html>
