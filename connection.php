<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "auction_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set('America/Chicago'); // Add this
?>
