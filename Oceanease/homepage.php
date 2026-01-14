<?php
session_start();
include 'config.php';

// Redirect to login if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}

// Fetch user info
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
<title>OceanEase - Homepage</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/home.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="OceanEase Logo">
        <div class="logo-text">OceanEase</div>
    </div>

    <nav>
        <a href="homepage.php" class="active">Home</a>

        <!-- Services Dropdown -->
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

    <!-- User email + Logout -->
    <div class="user-actions">
        <span class="user-email">Hello, <?= htmlspecialchars($user['email']); ?>👤</span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- ================= HERO SECTION ================= -->
<div class="hero">
    <div>
        <h1>Welcome, <?= htmlspecialchars($user['name']); ?>!</h1>
        <p>Explore our amazing services onboard the cruise.</p>
    </div>
</div>

<!-- ================= SERVICES SECTION ================= -->
<div class="container">
    <h2>Our Services</h2>
    <div class="grid">
        <div class="card">
            <img src="https://www.foodandwine.com/thmb/ErWT0Jq2m5Q8MgM1xa3DxqtHlUg=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/GTM-Best-10-Cruises-for-On-Board-Culinary-Experiences-Viking-Cruises-FT-BLOG0423-29a2eb6c576a409193b7c7d173e9416a.jpg">
            <div class="card-content">
                <h3>Catering</h3>
                <p>Order snacks, food, beverages, and more. Managed by the head chef.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://www.shutterstock.com/shutterstock/videos/1010296196/thumb/1.jpg?ip=x480">
            <div class="card-content">
                <h3>Stationery</h3>
                <p>Order gifts, chocolates, books, and stationery items.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://thumbs.dreamstime.com/b/luxury-open-air-cinema-setup-swimming-pool-night-large-screen-awaits-movie-viewers-palm-trees-resort-building-illuminated-408118679.jpg">
            <div class="card-content">
                <h3>Resort & Movies</h3>
                <p>Book movie seats and resort rooms with premium views.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://cdn.sanity.io/images/rd0y3pad/production/7d535acf7eb157d7c6665c76649e3bd4a89c35b1-1200x800.jpg">
            <div class="card-content">
                <h3>Beauty Salon</h3>
                <p>Schedule beauty and wellness services onboard.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://www.msccruises.com/int/-/media/global-contents/on-board/spa-beauty-and-fitness/sport-and-fitness/tile-images/fitness-technogym.jpg">
            <div class="card-content">
                <h3>Fitness Center</h3>
                <p>Book gym sessions and hire fitness equipment.</p>
            </div>
        </div>
        <div class="card">
            <img src="https://www.seanzcruise.com/wp-content/uploads/2024/06/3-2.jpg">
            <div class="card-content">
                <h3>Party Hall</h3>
                <p>Book halls for birthdays, weddings, and celebrations.</p>
            </div>
        </div>
    </div>
</div>

<!-- ================= FOOTER ================= -->
<footer>
    &copy; <?= date("Y"); ?> @OceanEase | All rights reserved.
</footer>

</body>
</html>
