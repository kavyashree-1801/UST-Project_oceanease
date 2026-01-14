<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Helper function
function jsonError($msg){
    echo json_encode(['status'=>'error','message'=>$msg]);
    exit;
}

// Include config
$config_path = __DIR__ . '/../config.php';
if(!file_exists($config_path)) jsonError('Config file not found');
require_once $config_path;
if(!isset($con)) jsonError('Database connection not found');

// POST check
if($_SERVER['REQUEST_METHOD'] !== 'POST') jsonError('Invalid request method');

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
if(!$email) jsonError('Email is required');

// Check user exists
$stmt = $con->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();
$res = $stmt->get_result();
if($res->num_rows === 0) jsonError('Email not found');

$user = $res->fetch_assoc();

// Generate reset_id (10 chars)
try{
    $reset_id = substr(bin2hex(random_bytes(5)),0,10);
}catch(Exception $e){
    jsonError('Cannot generate reset ID');
}

// Set expiration (15 min from now) with timezone-safe handling
date_default_timezone_set('Asia/Kolkata'); // Set to your timezone
$expires = date("Y-m-d H:i:s", strtotime("+15 minutes"));

// Update user
$upd = $con->prepare("UPDATE users SET reset_id=?, reset_expires=? WHERE id=?");
$upd->bind_param("ssi", $reset_id, $expires, $user['id']);
$upd->execute();

// Generate user-facing reset link (include folder)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$link = "$protocol://$host/Oceanease/reset_password.php?id=$reset_id";

// Success response
echo json_encode([
    'status'=>'success',
    'link'=>$link,
    'expires'=>$expires
]);
exit;
