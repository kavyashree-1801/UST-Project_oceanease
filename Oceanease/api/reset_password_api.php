<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

function jsonError($msg){
    echo json_encode(['status'=>'error','message'=>$msg]);
    exit;
}

$config_path = __DIR__ . '/../config.php';
if(!file_exists($config_path)) jsonError('Config file not found');
require_once $config_path;
if(!isset($con)) jsonError('Database connection not found');

if($_SERVER['REQUEST_METHOD'] !== 'POST') jsonError('Invalid request method');

$reset_id = $_POST['reset_id'] ?? '';
$new_password = $_POST['new_password'] ?? '';

if(!$reset_id || !$new_password) jsonError('Missing data');

// Check reset_id exists and not expired
$stmt = $con->prepare("SELECT id FROM users WHERE reset_id=? AND reset_expires >= NOW()");
$stmt->bind_param("s",$reset_id);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows === 0){
    jsonError('Invalid or expired reset link.');
}

$user = $res->fetch_assoc();
$user_id = $user['id'];

// Hash password
$hashed = password_hash($new_password, PASSWORD_DEFAULT);

// Update password and clear reset_id
$upd = $con->prepare("UPDATE users SET password=?, reset_id=NULL, reset_expires=NULL WHERE id=?");
$upd->bind_param("si",$hashed,$user_id);
$upd->execute();

echo json_encode([
    'status'=>'success',
    'message'=>'Password reset successfully. You can now log in.'
]);
exit;
