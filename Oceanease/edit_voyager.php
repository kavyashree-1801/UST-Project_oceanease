<?php
session_start();
include 'config.php';

/* ================= AUTH CHECK ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

/* ================= FETCH ADMIN EMAIL ================= */
$admin_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT email FROM users WHERE id=?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$admin_email = $admin['email'];

/* ================= UPDATE VOYAGER ================= */
if (isset($_POST['update_voyager'])) {
    $id     = intval($_POST['id']);
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $status = $_POST['status'];

    $stmt = $con->prepare(
        "UPDATE users SET name=?, email=?, status=? WHERE id=? AND role='voyager'"
    );
    $stmt->bind_param("sssi", $name, $email, $status, $id);
    $stmt->execute();

    header("Location: manage_voyagers.php");
    exit;
}

/* ================= FETCH VOYAGER ================= */
$id = intval($_GET['id'] ?? 0);
$q = $con->query("SELECT * FROM users WHERE id=$id AND role='voyager'");
$voyager = $q->fetch_assoc();

if (!$voyager) {
    die("Voyager not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Voyager | OceanEase</title>
<!-- Page Specific CSS -->
<link rel="stylesheet" href="css/edit_voyager.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="OceanEase Logo">
        <div class="logo-text">OceanEase</div>
    </div>

    <nav>
        <a href="admin_homepage.php">Dashboard</a>
        <a href="manage_menu.php">Menu Items</a>
        <a href="manage_voyagers.php" class="active">Voyagers</a>
    </nav>

    <div class="user-info">
        <div class="user-email">Hello, <?= htmlspecialchars($admin_email) ?>🧑‍💻</div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- ================= FORM ================= -->
<div class="container">
    <h2>edit voyager</h2>

    <form method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($voyager['id']) ?>">

        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($voyager['name']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($voyager['email']) ?>" required>

        <label>Status</label>
        <select name="status" required>
            <option value="active"   <?= $voyager['status'] === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $voyager['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>

        <button type="submit" name="update_voyager">Update Voyager</button>
    </form>
</div>
<footer>
    &copy; <?= date("Y") ?> @OceanEase | All rights reserved.
</footer>
</body>
</html>
