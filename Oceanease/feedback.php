<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit;
}

$user_id = $_SESSION['user_id'];
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
<title>Feedback | OceanEase</title>
<link rel="stylesheet" href="css/feedback.css">
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
                <a href="fitness_booking.php">Fitness Center</a>
                <a href="party_booking.php">Party Hall</a>
            </div>
        </div>

        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>
        <a href="feedback.php" class="active">Feedback</a>
    </nav>

    <!-- USER EMAIL + LOGOUT -->
    <div class="user-actions">
        <span class="user-email">Hello, <?= htmlspecialchars($user['email']) ?>👤</span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- ================= HERO ================= -->
<div class="hero">
    <h1>Feedback</h1>
    <p>We value your opinion. Let us know how we can improve!</p>
</div>

<!-- ================= FEEDBACK FORM ================= -->
<div class="container">
    <h2>Send Us Your Feedback</h2>
    <form id="feedbackForm">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label for="message">Message</label>
        <textarea name="message" id="message" rows="5" placeholder="Write your feedback here..." required></textarea>

        <button type="submit">Submit Feedback</button>
    </form>
    <div id="feedbackMsg"></div>
</div>

<!-- ================= FOOTER ================= -->
<footer>
    &copy; <?= date("Y"); ?> @OceanEase | All rights reserved.
</footer>

<script src="js/feedback.js"></script>
</body>
</html>
