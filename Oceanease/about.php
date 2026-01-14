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
    <title>About Us | OceanEase</title>
    <link rel="stylesheet" href="css/about.css">
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

        <a href="about.php" class="active">About Us</a>
        <a href="contact.php">Contact</a>
        <a href="feedback.php">Feedback</a>
    </nav>

    <div class="user-actions">
        <?php if(!empty($user_email)): ?>
            <span class="user-email">Hello, <?= htmlspecialchars($user_email) ?>👤</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        <?php else: ?>
            <a href="login.php" class="logout-btn">Login</a>
        <?php endif; ?>
    </div>
</div>

<!-- ================= HERO ================= -->
<section class="hero">
    <h1>About OceanEase</h1>
    <p>Luxury • Comfort • Seamless Experiences at Sea</p>
</section>

<!-- ================= ABOUT CONTENT ================= -->
<section class="about-container">
    <h2>Who We Are</h2>
    <p>
        OceanEase is a premium cruise service platform designed to provide
        seamless onboard experiences. From luxury resorts and fitness centers
        to beauty salons, catering, and entertainment, OceanEase ensures
        comfort, convenience, and elegance throughout your journey.
    </p>

    <p>
        Our mission is to redefine ocean travel by combining world-class services
        with innovative digital booking solutions — all in one place.
    </p>
</section>

<!-- ================= STAFF SECTION ================= -->
<section class="staff-section">
    <h2>Meet Our Team</h2>

    <div class="staff-grid">
        <!-- Staff Cards -->
        <div class="staff-card">
            <img src="https://media.istockphoto.com/id/1362347349/photo/ship-captain-with-elegant-uniform.jpg?s=612x612&w=0&k=20&c=3nHBvipEAsOITfG6KXFuy16QFonwFNdhF-sOxd4ujdQ=" alt="Captain">
            <h3>Captain James Walker</h3>
            <p>Chief Operations Officer</p>
        </div>

        <div class="staff-card">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSoDjtYovndoXWojDPHQIL24S2islfz_0i8ow&s" alt="Manager">
            <h3>Emma Rodriguez</h3>
            <p>Guest Experience Manager</p>
        </div>

        <div class="staff-card">
            <img src="https://5.imimg.com/data5/SELLER/Default/2022/8/YK/SD/AA/148640419/all-rounder-chef-for-hostal.jpg" alt="Chef">
            <h3>Chef Oliver Stone</h3>
            <p>Head of Catering Services</p>
        </div>

        <div class="staff-card">
            <img src="https://imgcdn.stablediffusionweb.com/2024/10/7/0bfc6ba3-d679-4d81-81af-004bd1a5a129.jpg" alt="Trainer">
            <h3>Ryan Patel</h3>
            <p>Lead Fitness Trainer</p>
        </div>

        <div class="staff-card">
            <img src="https://www.datocms-assets.com/55385/1633368090-jlp-1.jpeg?auto=format" alt="Salon Expert">
            <h3>Sophia Lee</h3>
            <p>Beauty & Wellness Director</p>
        </div>

        <div class="staff-card">
            <img src="https://thumbs.dreamstime.com/b/telemarketing-communication-man-call-center-customer-service-lead-generation-crm-agent-happy-salesman-consultant-284735913.jpg" alt="Support">
            <h3>Michael Brown</h3>
            <p>Customer Support Lead</p>
        </div>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<footer>
    &copy; <?= date("Y"); ?> @OceanEase | All rights reserved.
</footer>

</body>
</html>
