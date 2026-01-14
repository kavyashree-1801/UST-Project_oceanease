<?php
session_start();
include 'config.php';

/* ========================== AUTH CHECK ========================== */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supervisor') {
    header("Location: auth.php");
    exit;
}

/* ========================== FETCH SUPERVISOR DETAILS ========================== */
$user_id = $_SESSION['user_id'];
$userQuery = $con->prepare("SELECT name, email FROM users WHERE id = ?");
$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$user = $userQuery->get_result()->fetch_assoc();

/* ========================== FETCH STATS ========================== */
$statsQuery = $con->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status='confirmed' THEN 1 ELSE 0 END) as confirmed,
        SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed
    FROM stationery_orders
");
$stats = $statsQuery->fetch_assoc();

/* ========================== FETCH RECENT ORDERS ========================== */
$orderQuery = $con->query("SELECT * FROM stationery_orders ORDER BY order_date DESC LIMIT 10");
$orders = $orderQuery ? $orderQuery->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Supervisor Dashboard | OceanEase</title>
<link rel="stylesheet" href="css/supervisor_dashboard.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<div class="page-wrapper">

    <!-- ================= NAVBAR ================= -->
    <div class="navbar">
        <div class="logo-container">
            <img src="images/oceanease.png" alt="OceanEase Logo">
            <div class="logo-text">OceanEase</div>
        </div>
        <nav>
            <a href="supervisor_dashboard.php" class="active">Dashboard</a>
            <a href="view_stationery_orders.php">Inventory</a>
            <a href="view_stationery_items.php">Stationery Items</a>
        </nav>
        <div class="user-info">
            <span>Hello, <?= htmlspecialchars($user['email']) ?>🗂️</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <!-- ================= CONTENT ================= -->
    <div class="content">

        <!-- HERO -->
        <div class="hero">
            Welcome back, <?= htmlspecialchars($user['name']) ?> 👋
        </div>

        <!-- ================= 3 DYNAMIC CARDS ================= -->
        <div class="cards">
            <div class="card">
                <img src="https://img.icons8.com/color/96/order-history.png" alt="Total Orders">
                <div>
                    <h3>Total Orders</h3>
                    <p><?= $stats['total'] ?></p>
                </div>
            </div>

            <div class="card">
                <img src="https://img.icons8.com/color/96/hourglass.png" alt="Confirmed Orders">
                <div>
                    <h3>Confirmed</h3>
                    <p><?= $stats['confirmed'] ?></p>
                </div>
            </div>

            <div class="card">
                <img src="https://img.icons8.com/color/96/ok.png" alt="Completed Orders">
                <div>
                    <h3>Completed</h3>
                    <p><?= $stats['completed'] ?></p>
                </div>
            </div>
        </div>

        <!-- ================= RECENT ORDERS TABLE ================= -->
        <div class="table-container">
            <h2>Recent Stationery Orders</h2>
            <br>
            <?php if (!empty($orders)): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Ordered By</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $i => $order): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($order['item_name']) ?></td>
                        <td><?= $order['quantity'] ?></td>
                        <td><?= htmlspecialchars($order['ordered_by']) ?></td>
                        <td><?= date("d-m-Y H:i", strtotime($order['order_date'])) ?></td>
                        <td>
                            <span class="status status-<?= $order['status'] ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
        </div>

    </div>

    <!-- ================= FOOTER ================= -->
    <footer>
        &copy; <?= date("Y") ?> @OceanEase | All rights reserved.
    </footer>

</div>
</body>
</html>
