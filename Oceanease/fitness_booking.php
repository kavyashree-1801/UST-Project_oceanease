<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* FETCH USER NAME + EMAIL */
$stmt = $con->prepare("SELECT name, email FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Fitness Booking | OceanEase</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/fitness.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="OceanEase Logo">
        <div class="logo-text">OceanEase</div>
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
                <a href="fitness_booking.php" class="active">Fitness Center</a>
                <a href="party_booking.php">Party Hall</a>
            </div>
        </div>

        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>
        <a href="feedback.php">Feedback</a>
    </nav>

    <!-- USER EMAIL FIRST, THEN LOGOUT -->
    <div class="user-actions">
        <span class="user-email">Hello, <?= htmlspecialchars($user['email']) ?>👤</span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- ================= HERO ================= -->
<div class="hero">
    <h1>Welcome, <?= htmlspecialchars($user['name']) ?></h1>
    <p>Book fitness services onboard to stay healthy and active</p>
</div>

<!-- ================= CONTENT ================= -->
<div class="container">
    <h2>Fitness Booking</h2>

    <form id="fitnessForm">
        <input type="hidden" name="booking_id" id="booking_id">

        <select name="workout_type" required>
            <option value="">-- Select Fitness Service --</option>
            <option value="Gym Training">Gym Training</option>
            <option value="Yoga">Yoga</option>
            <option value="Personal Training">Personal Training</option>
            <option value="Aerobics">Aerobics</option>
        </select>

        <input type="date" name="booking_date" required>

        <select name="booking_time" required>
            <option value="">-- Select Time --</option>
            <?php
            for ($h = 0; $h < 24; $h++) {
                for ($m = 0; $m < 60; $m += 30) {
                    $value = sprintf("%02d:%02d:00", $h, $m);
                    $display = date("h:i A", strtotime($value));
                    echo "<option value='$value'>$display</option>";
                }
            }
            ?>
        </select>

        <button type="submit">Book Now</button>
    </form>

    <h3 class="center-title">Your Bookings</h3>

    <div class="table-container">
        <table id="fitnessTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="fitnessList"></tbody>
        </table>
    </div>
</div>

<footer>
    &copy; <?= date("Y"); ?> @OceanEase | All rights reserved.
</footer>

<script src="js/fitness.js"></script>
</body>
</html>
