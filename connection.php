<?php
// connection.php

$host = "db";          // Docker service name
$user = "root";
$pass = "root";
$db   = "auction_db";  // <-- updated!

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set('America/Chicago');
?>
