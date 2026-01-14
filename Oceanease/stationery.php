<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ================= FETCH LOGGED-IN USER ================= */
$stmt = $con->prepare("SELECT name, email FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$username = $user['name'] ?? 'User';
$email    = $user['email'] ?? '';

$_SESSION['name'] = $username;

/* ================= FETCH STATIONERY ITEMS ================= */
$items_result = $con->query("SELECT * FROM stationery_items ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>OceanEase | Stationery</title>
<link rel="stylesheet" href="css/stationery.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="Logo">
        <span class="logo-text">OceanEase</span>
    </div>

    <nav>
        <a href="homepage.php">Home</a>

        <div class="dropdown">
            <button class="dropbtn">Services ▾</button>
            <div class="dropdown-content">
                <a href="catering.php">Catering</a>
                <a href="stationery.php" class="active">Stationery</a>
                <a href="resort_booking.php">Resort & Movies</a>
                <a href="salon_booking.php">Beauty Salon</a>
                <a href="fitness_booking.php">Fitness Center</a>
                <a href="party_booking.php">Party Hall</a>
            </div>
        </div>

        <a href="about.php">About us</a>
        <a href="contact.php">Contact</a>
        <a href="feedback.php">Feedback</a>
    </nav>

    <div class="user-actions">
        <span class="user">
            Hello, <?= htmlspecialchars($email) ?>👤
        </span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- ================= HERO ================= -->
<section class="hero">
    <h1>Welcome, <?= htmlspecialchars($username) ?></h1>
    <p>Select your stationery items and place your order easily onboard</p>
</section>

<!-- ================= ITEMS FORM ================= -->
<form id="stationeryForm">
    <div class="card-grid">
        <?php while ($item = $items_result->fetch_assoc()): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($item['image']) ?>" 
                     alt="<?= htmlspecialchars($item['item']) ?>">

                <h3><?= htmlspecialchars($item['item']) ?></h3>
                <p class="price">Price: ₹<?= number_format($item['price'], 2) ?></p>

                <input
                    type="number"
                    min="0"
                    value="0"
                    class="item-quantity"
                    data-name="<?= htmlspecialchars($item['item']) ?>"
                    data-price="<?= $item['price'] ?>">
            </div>
        <?php endwhile; ?>
    </div>

    <button type="submit" class="place-order-btn">Place Order</button>
</form>

<!-- ================= BOOKINGS TABLE ================= -->
<h2 class="booking-title">Your Stationery Bookings</h2>

<table id="stationeryBookingTable" class="booking-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<footer>
&copy; <?= date("Y") ?> @OceanEase | All Rights Reserved
</footer>

<script src="js/stationery.js"></script>
</body>
</html>
