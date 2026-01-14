<?php
// Database configuration for OceanEase
$host = "localhost";       // Usually localhost
$dbUsername = "root";      // Your database username
$dbPassword = "";          // Your database password
$dbName = "oceanease";     // Your database name

// Create connection
$con = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Set character set to UTF-8
$con->set_charset("utf8");
?>
