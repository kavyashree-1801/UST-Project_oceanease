<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $con->prepare("SELECT name,email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$username = $user['name']; // ✅ REAL USER NAME
$email    = $user['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Salon Booking | OceanEase</title>
<link rel="stylesheet" href="css/salon.css">
</head>
<body>

<!-- NAVBAR -->
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
                <a href="salon_booking.php" class="active">Beauty Salon</a>
                <a href="fitness_booking.php">Fitness Center</a>
                <a href="party_booking.php">Party Hall</a>
            </div>
        </div>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="feedback.php">Feedback</a>
    </nav>
    <div class="user-actions">
        <span class="user-email">
            Hello, <?= htmlspecialchars($email) ?>👤
        </span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Welcome, <?= htmlspecialchars($username) ?></h1>
    <p>Book your salon services easily onboard</p>
</div>

<!-- BOOKING FORM -->
<div class="container">
    <h2>Book a Salon Service</h2>

    <form id="salonForm">

    <input type="hidden" name="booking_id" id="booking_id">
    
        <label>Service:</label>
        <select name="service_name" id="service_name" required>
            <option value="">Select Service</option>

            <optgroup label="Men">
                <option value="Hair Cut">Hair Cut</option>
                <option value="Beard Trim">Beard Trim</option>
                <option value="Shaving">Shaving</option>
                <option value="Hair Coloring">Hair Coloring</option>
                <option value="Facial">Facial</option>
            </optgroup>

            <optgroup label="Women">
                <option value="Hair Cut">Hair Cut</option>
                <option value="Hair Spa">Hair Spa</option>
                <option value="Facial">Facial</option>
                <option value="Waxing">Waxing</option>
                <option value="Manicure">Manicure</option>
                <option value="Pedicure">Pedicure</option>
                <option value="Makeup">Makeup</option>
            </optgroup>
        </select>

        <label>Booking Date:</label>
        <input type="date" name="booking_date" required>

        <label>Booking Time:</label>
        <input type="time" name="booking_time" required>

        <button type="submit">Add Booking</button>
    </form>

    <!-- BOOKINGS TABLE -->
    <h2>Your Bookings</h2>
    <div class="table-container">
        <table id="salonList">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- JS will insert rows here -->
            </tbody>
        </table>
    </div>
</div>

<footer>
&copy; <?= date("Y") ?> @OceanEase | All Rights Reserved
</footer>

<script src="js/salon.js"></script>
</body>
</html>
