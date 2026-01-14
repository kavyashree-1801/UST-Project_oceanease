<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: homepage.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get username
$stmt = $con->prepare("SELECT name,email FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$username = $user['name'];
$_SESSION['name'] = $username;
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Catering | OceanEase</title>
<link rel="stylesheet" href="css/catering.css">
</head>
<body>

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
                <a href="stationery.php">Stationery</a>
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
            Hello, <?= htmlspecialchars($user['email']) ?>👤
        </span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div class="hero">
    <h1>Welcome, <?= htmlspecialchars($username) ?></h1>
    <p>Order delicious food onboard</p>
</div>

<div class="container">
    <h2>Catering Menu</h2>
    <div id="menuGrid" class="grid"></div>

    <div class="order-btn-container">
        <button id="orderNowBtn">Order Now</button>
    </div>

    <h2>Your Bookings</h2>
    <table id="bookingTable">
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
</div>

<footer>
&copy; <?= date("Y") ?> @OceanEase | All Rights Reserved
</footer>
<script src="js/catering.js"></script>
</body>
</html>
