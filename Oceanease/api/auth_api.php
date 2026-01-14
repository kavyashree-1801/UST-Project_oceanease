<?php
session_start();
include '../config.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

/* ================= READ INPUT ================= */
$data = $_POST;
if (empty($data)) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
}

$action = $data['action'] ?? '';

/* ================= LOGIN ================= */
if ($action === 'login') {

    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    if (!$email || !$password) {
        echo json_encode([
            "status" => "error",
            "messages" => ["Email and password required"]
        ]);
        exit;
    }

    $stmt = $con->prepare("SELECT id, name, password, role FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        echo json_encode([
            "status" => "success",
            "role" => $user['role'],
            "messages" => ["Login successful"]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "messages" => ["Invalid email or password"]
        ]);
    }
    exit;
}

/* ================= REGISTER ================= */
if ($action === 'register') {

    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';
    $role = $data['role'] ?? 'voyager';

    if (!$name || !$email || !$password) {
        echo json_encode([
            "status" => "error",
            "messages" => ["All fields are required"]
        ]);
        exit;
    }

    $check = $con->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();

    if ($check->get_result()->num_rows > 0) {
        echo json_encode([
            "status" => "error",
            "messages" => ["Email already registered"]
        ]);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $con->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed, $role);
    $stmt->execute();

    echo json_encode([
        "status" => "success",
        "messages" => ["Registration successful. Please login."]
    ]);
    exit;
}

/* ================= FORGOT PASSWORD ================= */
if ($action === 'forgot') {

    $email = trim($data['email'] ?? '');

    if (!$email) {
        echo json_encode([
            "status" => "error",
            "messages" => ["Email is required"]
        ]);
        exit;
    }

    $stmt = $con->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $token = bin2hex(random_bytes(16));
        $expires = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        $stmt2 = $con->prepare(
            "UPDATE users SET reset_id=?, reset_expires=? WHERE id=?"
        );
        $stmt2->bind_param("ssi", $token, $expires, $user['id']);
        $stmt2->execute();

        $link = "http://" . $_SERVER['HTTP_HOST'] .
            dirname(dirname($_SERVER['PHP_SELF'])) .
            "/reset_password.php?token=" . $token;

        echo json_encode([
            "status" => "success",
            "messages" => ["Password reset link generated"],
            "reset_link" => $link
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "messages" => ["Email not found"]
        ]);
    }
    exit;
}

/* ================= RESET PASSWORD ================= */
if ($action === 'reset') {

    $token = $data['token'] ?? '';
    $newPassword = $data['password'] ?? '';

    if (!$token || !$newPassword) {
        echo json_encode([
            "status" => "error",
            "messages" => ["Invalid reset request"]
        ]);
        exit;
    }

    $stmt = $con->prepare(
        "SELECT id FROM users WHERE reset_id=? AND reset_expires > NOW()"
    );
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        echo json_encode([
            "status" => "error",
            "messages" => ["Invalid or expired token"]
        ]);
        exit;
    }

    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt2 = $con->prepare(
        "UPDATE users SET password=?, reset_id=NULL, reset_expires=NULL WHERE id=?"
    );
    $stmt2->bind_param("si", $hashed, $user['id']);
    $stmt2->execute();

    echo json_encode([
        "status" => "success",
        "messages" => ["Password updated successfully"]
    ]);
    exit;
}

/* ================= INVALID ACTION ================= */
echo json_encode([
    "status" => "error",
    "messages" => ["Invalid request"]
]);
exit;
