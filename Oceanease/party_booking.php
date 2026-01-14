<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT name,email FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Party Hall Booking | OceanEase</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/party.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="Logo">
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
                <a href="fitness_booking.php">Fitness Center</a>
                <a href="party_booking.php" class="active">Party Hall</a>
            </div>
        </div>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="feedback.php">Feedback</a>
    </nav>

    <div class="user-actions">
        <span class="user-email">Hello, <?= htmlspecialchars($user['email']) ?>👤</span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- ================= HERO ================= -->
<div class="hero">
    <h1>Welcome, <?= htmlspecialchars($user['name']) ?></h1>
    <p>Book your Party Hall for special events</p>
</div>

<!-- ================= CONTAINER ================= -->
<div class="container">
    <h2>Party Hall Booking</h2>
    <form id="partyForm">
        <select name="hall_name" required>
            <option value="">-- Select Party Hall --</option>
            <option value="Royal Grand Hall">Royal Grand Hall</option>
            <option value="Ocean View Banquet">Ocean View Banquet</option>
            <option value="Coral Celebration Hall">Coral Celebration Hall</option>
            <option value="Sunset Party Lounge">Sunset Party Lounge</option>
        </select>

        <select name="event_type" required>
            <option value="">-- Select Event Type --</option>
            <option value="Birthday Party">Birthday Party</option>
            <option value="Wedding">Wedding</option>
            <option value="Anniversary">Anniversary</option>
            <option value="Corporate Event">Corporate Event</option>
        </select>

        <input type="date" name="booking_date" required>

        <select name="booking_time" required>
            <option value="">-- Select Time --</option>
            <?php
            for ($hour = 0; $hour < 24; $hour++) {
                for ($min = 0; $min < 60; $min += 30) {
                    $value = sprintf("%02d:%02d:00",$hour,$min);
                    $display = date("h:i A", strtotime($value));
                    echo "<option value='{$value}'>{$display}</option>";
                }
            }
            ?>
        </select>

        <input type="number" name="guests" min="10" placeholder="Number of Guests" required>

        <button type="submit">Book Party Hall</button>
    </form>

    <h3>Your Bookings</h3>
    <div class="table-container">
        <table id="partylist">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Hall</th>
                    <th>Event Type</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Guests</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="partyList"></tbody>
        </table>
    </div>
</div>

<footer>
    &copy; <?= date("Y") ?> @OceanEase | All rights reserved
</footer>

<script src="js/party.js"></script>
</body>
</html>
