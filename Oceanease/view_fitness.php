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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Fitness Bookings | OceanEase</title>

<!-- SAME NAVBAR CSS -->
<link rel="stylesheet" href="css/view_fitness.css">
</head>

<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="OceanEase Logo">
        <span class="logo-text">OceanEase</span>
    </div>

    <nav>
        <a href="manager_dashboard.php">Dashboard</a>

        <div class="dropdown">
            <span class="dropbtn">Onboard Services ▾</span>
            <div class="dropdown-content">
                <a href="view_resort_movies.php">Resort & Movie</a>
                <a href="view_beauty_salon.php">Beauty Salon</a>
                <a href="view_fitness.php" class="active">Fitness Center</a>
                <a href="view_partyhall.php">Party Hall</a>
            </div>
        </div>
    </nav>

    <div class="user-info">
        <span>Hello, <?= htmlspecialchars($user['email']) ?> 🧑‍💼</span>        
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- ================= PAGE CONTENT ================= -->
<div class="container">
    <h2>Fitness Center Bookings</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Workout Type</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="fitnessTable">
            <tr>
                <td colspan="5" style="text-align:center;">Loading bookings...</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- ================= FOOTER ================= -->
<footer>
    &copy; <?= date("Y") ?> @OceanEase | All rights reserved.
</footer>

<!-- ================= JS ================= -->
<script src="js/view_fitness.js"></script>
</body>
</html>
