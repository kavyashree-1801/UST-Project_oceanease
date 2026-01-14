<?php
session_start();
include 'config.php';

/* ==========================
   AUTH CHECK
========================== */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: auth.php");
    exit;
}

/* ==========================
   FETCH MANAGER DETAILS
========================== */
$user_id = $_SESSION['user_id'];

$userQuery = $con->prepare(
    "SELECT name, email FROM users WHERE id = ? AND status = 'active'"
);

if (!$userQuery) {
    die("Prepare failed: " . $con->error);
}

$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$userResult = $userQuery->get_result();

if ($userResult->num_rows === 0) {
    session_destroy();
    header("Location: auth.php");
    exit;
}

$user = $userResult->fetch_assoc();

/* ==========================
   FETCH BOOKING COUNTS
========================== */
function getCount($con, $table) {
    $result = $con->query("SELECT COUNT(*) AS total FROM $table");
    return $result ? $result->fetch_assoc()['total'] : 0;
}

$resortCount  = getCount($con, 'resort_movie_bookings');
$salonCount   = getCount($con, 'salon_bookings');
$fitnessCount = getCount($con, 'fitness_bookings');
$partyCount   = getCount($con, 'party_hall_bookings');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manager Dashboard | OceanEase</title>
<link rel="stylesheet" href="css/manager_dash.css">
</head>

<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="OceanEase">
        <span class="logo-text">OceanEase</span>
    </div>

    <nav>
        <a href="manager_dashboard.php" class="active">Dashboard</a>

        <div class="dropdown">
            <span class="dropbtn">Onboard Services ▾</span>
            <div class="dropdown-content">
                <a href="view_resort_movies.php">Resort & Movie</a>
                <a href="view_beauty_salon.php">Beauty Salon</a>
                <a href="view_fitness.php">Fitness Center</a>
                <a href="view_partyhall.php">Party Hall</a>
            </div>
        </div>
    </nav>

    <div class="user-info">
        <span>Hello, <?= htmlspecialchars($user['email']) ?> 🧑‍💼</span>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- ================= HERO ================= -->
<div class="hero">
    <div class="hero-content">
        <h1>Welcome, Manager</h1>
        <p>Manage all onboard services with ease</p>
    </div>
</div>

<!-- ================= CONTENT ================= -->
<div class="dashboard-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Onboard Services</h3>
        <ul>
            <li><a href="view_resort_movies.php">Resort & Movie</a></li>
            <li><a href="view_beauty_salon.php">Beauty Salon</a></li>
            <li><a href="view_fitness.php">Fitness Center</a></li>
            <li><a href="view_partyhall.php">Party Hall</a></li>
        </ul>
    </div>

    <!-- Overview -->
    <div class="overview">
        <div class="cards">

            <div class="card">
                <img src="https://aniprivateresorts.com/wp-content/uploads/2023/01/Sri-Lanka-Full-Service-Private-Movie-Screening-1.jpg">
                <div class="info">
                    <h4>Resort & Movie</h4>
                    <p><?= $resortCount ?> Bookings</p>
                    <a href="view_resort_movies.php">Manage</a>
                </div>
            </div>

            <div class="card">
                <img src="https://mycruisestories.com/wp-content/uploads/2015/08/spa-legend-salon.jpg">
                <div class="info">
                    <h4>Beauty Salon</h4>
                    <p><?= $salonCount ?> Bookings</p>
                    <a href="view_beauty_salon.php">Manage</a>
                </div>
            </div>

            <div class="card">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQkcnudSgJTa5ll1BuAICsPsoPxwA0IcJTGLQ&s">
                <div class="info">
                    <h4>Fitness Center</h4>
                    <p><?= $fitnessCount ?> Bookings</p>
                    <a href="view_fitness.php">Manage</a>
                </div>
            </div>

            <div class="card">
                <img src="https://thumbs.dreamstime.com/b/cruise-ship-dining-room-opus-celebrity-reflection-celebrity-line-39993773.jpg">
                <div class="info">
                    <h4>Party Hall</h4>
                    <p><?= $partyCount ?> Bookings</p>
                    <a href="view_partyhall.php">Manage</a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ================= FOOTER ================= -->
<footer>
    &copy; <?= date("Y") ?> OceanEase | All rights reserved.
</footer>

</body>
</html>
