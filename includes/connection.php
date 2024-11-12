<?php
$servername = "localhost";  // Usually localhost in XAMPP
$username = "root";         // Default XAMPP username
$password = "";             // Default XAMPP password is empty
$dbname = "bikerecommendation";  // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
