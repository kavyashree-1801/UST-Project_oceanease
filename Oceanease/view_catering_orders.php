<?php
session_start();
include 'config.php';

/* ================= AJAX STATUS UPDATE ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'headcook') {
        echo json_encode(['success' => false]);
        exit;
    }

    $order_id = intval($_POST['order_id']);

    $stmt = $con->prepare(
        "UPDATE orders SET status='completed' WHERE id=? AND status='pending'"
    );
    $stmt->bind_param("i", $order_id);

    echo json_encode(['success' => $stmt->execute()]);
    $stmt->close();
    exit;
}

/* ================= AUTH CHECK ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'headcook') {
    header("Location: auth.php");
    exit;
}

/* ================= FETCH EMAIL ================= */
$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT email FROM users WHERE id=? AND role='headcook'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$email = ($result->num_rows > 0) ? $result->fetch_assoc()['email'] : 'email@example.com';
$stmt->close();

/* ================= FETCH ORDERS ================= */
$orderStmt = $con->prepare("
    SELECT id, username, item_names, total_amount, status, order_date
    FROM orders
    ORDER BY order_date DESC
");
$orderStmt->execute();
$orders = $orderStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>OceanEase | Catering Orders</title>

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/view_catering_orders.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<div class="navbar">
    <div class="logo-container">
        <img src="images/oceanease.png" height="40">
        <div class="logo-text">OceanEase</div>
    </div>

    <nav>
        <a href="headcook_dashboard.php">Dashboard</a>
        <a href="view_catering_orders.php" class="active">Orders</a>
        <a href="view_menu.php">Menu Items</a>
    </nav>

    <div class="user-actions">
        <div class="user-email">Hello, <?= htmlspecialchars($email) ?> 👨‍🍳</div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<!-- ================= HERO ================= -->
<div class="hero">
    <div>
        <h1>Catering Orders 📋</h1>
        <p>All catering food orders placed onboard OceanEase.</p>
    </div>
</div>

<!-- ================= TABLE ================= -->
<div class="container">
    <h2>All Catering Orders</h2>

    <div class="table-wrapper">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Items</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            <?php if ($orders->num_rows > 0): ?>
                <?php $sr = 1; // ✅ SERIAL NUMBER STARTS FROM 1 ?>
                <?php while ($row = $orders->fetch_assoc()): ?>
                <tr>
                    <!-- ✅ SERIAL NUMBER -->
                    <td><?= $sr++; ?></td>

                    <td><?= htmlspecialchars($row['username']); ?></td>

                    <td><?= htmlspecialchars($row['item_names']); ?></td>

                    <td>₹<?= number_format($row['total_amount'], 2); ?></td>

                    <td>
                        <span id="status-<?= $row['id']; ?>"
                              class="status <?= strtolower($row['status']); ?>">
                            <?= ucfirst($row['status']); ?>
                        </span>
                    </td>

                    <td><?= date("d M Y, h:i A", strtotime($row['order_date'])); ?></td>

                    <td>
                        <?php if ($row['status'] === 'pending'): ?>
                            <select class="status-action" data-id="<?= $row['id']; ?>">
                                <option value="pending" selected>Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center;">No catering orders found.</td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>

<footer>
    &copy; <?= date("Y") ?> @OceanEase | All rights reserved.
</footer>

<script src="js/catering_orders.js"></script>
</body>
</html>
