<?php
include 'connection.php';

$result = $conn->query("SELECT COUNT(*) AS total FROM items");

if (!$result) {
    die("Query error: " . $conn->error);
}

$row = $result->fetch_assoc();
echo "Total items: " . $row['total'];
?>
