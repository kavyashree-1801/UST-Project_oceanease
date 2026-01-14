<?php
session_start();
include 'config.php';

// Admin authentication
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch admin email for navbar
$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT email FROM users WHERE id = ? AND role='admin'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$admin_email = ($result->num_rows > 0) ? $result->fetch_assoc()['email'] : 'admin@example.com';
$stmt->close();

// Add Menu Item
if (isset($_POST['add_item'])) {
    $name = $_POST['item_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $image = $_POST['image'] ?? 'images/default.png'; // fallback if no image provided

    $stmt = $con->prepare("INSERT INTO menu_items (item_name, category, price, description, image) VALUES (?,?,?,?,?)");
    $stmt->bind_param("ssdss", $name, $category, $price, $desc, $image);
    $stmt->execute();
}

// Delete Menu Item
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $con->query("DELETE FROM menu_items WHERE id=$id");
}

// Fetch all menu items
$menuItems = $con->query("SELECT * FROM menu_items");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Menu Items | OceanEase</title>
<link rel="stylesheet" href="css/manage_menu.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" alt="OceanEase Logo">
        <div class="logo-text">OceanEase</div>
    </div>

    <nav class="nav-center">
        <a href="admin_homepage.php">Dashboard</a>
        <a href="manage_menu.php" class="active">Menu Items</a>
        <a href="manage_voyagers.php">Voyagers</a>
    </nav>

    <div class="user-actions">
        <div class="user-email">Hello, <?= htmlspecialchars($admin_email) ?>🧑‍💻</div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Manage Menu Items</h2>

    <!-- Add Item Form -->
    <form method="POST" class="menu-form">
        <input type="text" name="item_name" placeholder="Item Name" required>
        <input type="text" name="category" placeholder="Category" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="text" name="image" placeholder="Image URL">
        <textarea name="description" placeholder="Description"></textarea>
        <button type="submit" name="add_item">Add Item</button>
    </form>

    <!-- Menu Items Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $menuItems->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td>
                    <img src="<?= htmlspecialchars($row['image'] ?: 'images/default.png') ?>" alt="<?= htmlspecialchars($row['item_name']) ?>" class="menu-image">
                </td>
                <td><?= htmlspecialchars($row['item_name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>
                    <a class="edit-btn" href="edit_menu.php?id=<?= $row['id'] ?>">Edit</a>
                    <a class="delete-btn" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this item?')">Delete</a>
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
