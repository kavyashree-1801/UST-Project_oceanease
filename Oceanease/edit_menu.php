<?php
session_start();
include 'config.php';

// ================= AUTH CHECK =================
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ================= FETCH ADMIN EMAIL =================
$admin_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT email FROM users WHERE id=?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$admin_email = $admin['email'];

// ================= UPDATE MENU ITEM =================
if (isset($_POST['update_item'])) {
    $id       = intval($_POST['id']);
    $name     = $_POST['item_name'];
    $category = $_POST['category'];
    $price    = $_POST['price'];
    $desc     = $_POST['description'];
    $image    = $_POST['image'];

    $stmt = $con->prepare("UPDATE menu_items SET item_name=?, category=?, price=?, description=?, image=? WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("ssdssi", $name, $category, $price, $desc, $image, $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: manage_menu.php");
    exit;
}

// ================= FETCH ITEM =================
$id     = intval($_GET['id'] ?? 0);
$result = $con->query("SELECT * FROM menu_items WHERE id=$id");
$item   = $result->fetch_assoc();

if (!$item) {
    die("Menu item not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Menu Item | OceanEase</title>
<link rel="stylesheet" href="css/edit_menu.css">
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
        <a href="manage_menu.php" class="active">Menu Items</a>
        <a href="manage_voyagers.php">Voyagers</a>
    </nav>

    <div class="user-info">
        <div class="user-email">Hello, <?= htmlspecialchars($admin_email) ?> 🧑‍💻</div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- ================= FORM ================= -->
<div class="container">
    <h2>edit menu item</h2>

    <form method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">

        <label>Item Name</label>
        <input type="text" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required>

        <label>Category</label>
        <input type="text" name="category" value="<?= htmlspecialchars($item['category']) ?>" required>

        <label>Price</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($item['price']) ?>" required>

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($item['description']) ?></textarea>

        <label>Image Link</label>
        <?php if (!empty($item['image'])): ?>
            <img class="preview" src="<?= htmlspecialchars($item['image']) ?>" alt="Current Item Image">
        <?php endif; ?>
        <input type="text" name="image" placeholder="Enter full image URL" value="<?= htmlspecialchars($item['image']) ?>">

        <button type="submit" name="update_item">Update Item</button>
    </form>
</div>

<!-- ================= FOOTER ================= -->
<footer>
    &copy; <?= date('Y') ?> @OceanEase | All rights reserved.
</footer>

</body>
</html>
