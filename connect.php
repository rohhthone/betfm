<?php
$servername = "vip234.hosting.reg.";
$username = "u1810140_default";
$password = "JvSHikI8Y4zDy1m9";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>