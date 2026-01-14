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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Resort & Movie Booking | OceanEase</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/resort_booking.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="OceanEase Logo" style="height:40px;">
        <span class="logo-text">OceanEase</span>
    </div>

    <nav>
        <a href="homepage.php">Home</a>
        <div class="dropdown">
            <button class="dropbtn">Services ▾</button>
            <div class="dropdown-content">
                <a href="catering.php">Catering</a>
                <a href="stationery.php">Stationery</a>
                <a href="resort_booking.php" class="active">Resort & Movies</a>
                <a href="salon_booking.php">Beauty Salon</a>
                <a href="fitness_booking.php">Fitness Center</a>
                <a href="party_booking.php">Party Hall</a>
            </div>
        </div>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>
        <a href="feedback.php">Feedback</a>
    </nav>

    <div class="user-actions">
        <span class="user-email">Hello, <?= htmlspecialchars($email) ?>👤</span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- ================= HERO ================= -->
<div class="hero">
    <h1>Welcome, <?= htmlspecialchars($username) ?></h1>
    <p>Relax, enjoy movies & luxury resorts onboard</p>
</div>

<!-- ================= CONTENT ================= -->
<div class="container">
    <h2>Resort & Movie Booking</h2>

    <form id="bookingForm">
        <select name="resort_name" required>
            <option value="">Select Resort</option>
            <option value="Ocean Pearl Resort">Ocean Pearl Resort</option>
            <option value="Blue Wave Resort">Blue Wave Resort</option>
            <option value="Coral Bay Resort">Coral Bay Resort</option>
            <option value="Sunset Paradise">Sunset Paradise</option>
        </select>

        <input type="text" name="movie_name" placeholder="Movie Name (optional)">

        <input type="date" name="booking_date" required>

        <select name="booking_time" required>
            <option value="">Select Time</option>
            <option value="10:00">10:00</option>
            <option value="12:00">12:00</option>
            <option value="14:00">14:00</option>
            <option value="16:00">16:00</option>
            <option value="18:00">18:00</option>
            <option value="20:00">20:00</option>
        </select>

        <input type="number" name="guests" min="1" value="1" required>
        <button type="submit">Book Now</button>
    </form>

    <h3>Your Bookings</h3>
    <div class="table-container">
        <table id="resortList">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Resort</th>
                    <th>Movie</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Guests</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookingList"></tbody>
        </table>
    </div>
</div>

<footer>
    &copy; <?= date("Y"); ?> @OceanEase | All rights reserved.
</footer>
<script src="js/resort_booking.js"></script>
</body>
</html>
