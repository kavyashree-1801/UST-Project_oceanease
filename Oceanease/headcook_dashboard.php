<?php
session_start();
include 'config.php';

/* Auth: Head Cook only */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'headcook') {
    header("Location: auth.php");
    exit;
}

// Fetch head cook email
$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT email FROM users WHERE id = ? AND role = 'headcook'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$email = ($result->num_rows > 0) ? $result->fetch_assoc()['email'] : 'email@example.com';
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>OceanEase - Head Cook Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/headcook_dash.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="OceanEase Logo" height="40">
        <div class="logo-text">OceanEase</div>
    </div>

    <nav>
        <a href="headcook_dashboard.php">Dashboard</a>
        <a href="view_catering_orders.php">Orders</a>
        <a href="view_menu.php">Menu Items</a>
    </nav>

    <div class="user-actions">
        <div class="user-email">Hello, <?= htmlspecialchars($email) ?> 👨‍🍳</div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- ================= HERO ================= -->
<div class="hero">
    <div>
        <h1>Welcome, Head Cook 👨‍🍳</h1>
        <p>Manage catering orders and oversee food preparation onboard OceanEase.</p>
    </div>
</div>

<!-- ================= DASHBOARD CARDS ================= -->
<div class="container">
    <h2>Head Cook Panel</h2>

    <div class="grid">

        <!-- Catering Orders -->
        <div class="card">
            <img src="https://www.foodandwine.com/thmb/ErWT0Jq2m5Q8MgM1xa3DxqtHlUg=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/GTM-Best-10-Cruises-for-On-Board-Culinary-Experiences-Viking-Cruises-FT-BLOG0423-29a2eb6c576a409193b7c7d173e9416a.jpg" alt="Catering Orders">
            <div class="card-content">
                <h3>Catering Orders</h3>
                <p>View all catering food orders and item quantities.</p>
                <a href="view_catering_orders.php" class="card-btn">View Orders</a>
            </div>
        </div>

        <!-- Menu Items -->
        <div class="card">
            <img src="https://images.unsplash.com/photo-1540189549336-e6e99c3679fe" alt="Menu Items">
            <div class="card-content">
                <h3>Menu Items</h3>
                <p>View available menu items for catering services.</p>
                <a href="view_menu.php" class="card-btn">View Menu</a>
            </div>
        </div>

    </div>
</div>

<footer>
    &copy; <?= date("Y"); ?> @OceanEase | All rights reserved.
</footer>

</body>
</html>
