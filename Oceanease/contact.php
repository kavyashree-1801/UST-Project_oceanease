<?php
session_start();
include 'config.php';

$user_email = '';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $con->prepare("SELECT email FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_email = $user['email'];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Us | OceanEase</title>
<link rel="stylesheet" href="css/contact.css">
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
                <a href="party_booking.php">Party Hall</a>
            </div>
        </div>

        <a href="about.php">About Us</a>
        <a href="contact.php" class="active">Contact</a>
        <a href="feedback.php">Feedback</a>
    </nav>

    <div class="user-actions">
        <?php if($user_email): ?>
            <span class="user-email">Hello, <?= htmlspecialchars($user_email) ?>👤</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        <?php else: ?>
            <a href="login.php" class="logout-btn">Login</a>
        <?php endif; ?>
    </div>
</div>

<!-- ================= HERO ================= -->
<div class="hero">
    <h1>Contact OceanEase</h1>
    <p>We’re here to help you sail smoothly</p>
</div>

<!-- ================= CONTACT FORM ================= -->
<div class="container">
    <h2>Get In Touch</h2>

    <form id="contactForm">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="text" name="subject" placeholder="Subject" required>
        <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
        <p id="contactMsg"></p>
    </form>
</div>

<footer>
    &copy; <?= date("Y") ?> @OceanEase | All rights reserved.
</footer>

<script src="js/contact.js"></script>
</body>
</html>
