<?php
session_start();
include 'config.php';

// Admin Authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Delete voyager
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $con->query("DELETE FROM users WHERE id=$id AND role='voyager'");
    header("Location: manage_voyagers.php");
    exit;
}

// Fetch all voyagers
$voyagers = $con->query("SELECT * FROM users WHERE role='voyager'");

// Fetch admin email for navbar
$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT email FROM users WHERE id = ? AND role='admin'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$admin_email = ($result->num_rows > 0) ? $result->fetch_assoc()['email'] : 'admin@example.com';
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Voyagers | OceanEase</title>
<link rel="stylesheet" href="css/manage_voyagers.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="OceanEase Logo">
        <div class="logo-text">OceanEase</div>
    </div>

    <nav class="nav-center">
        <a href="admin_homepage.php">Dashboard</a>
        <a href="manage_menu.php">Menu Items</a>
        <a href="manage_voyagers.php" class="active">Voyagers</a>
    </nav>

    <div class="user-actions">
        <div class="user-email">Hello, <?= htmlspecialchars($admin_email) ?>🧑‍💻</div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div class="container">
    <h2>All Voyagers</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $voyagers->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <a href="edit_voyager.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this voyager?')" class="delete-btn">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<footer>
    &copy; <?= date("Y") ?> @OceanEase | All rights reserved.
</footer>

</body>
</html>
