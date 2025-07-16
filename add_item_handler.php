<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add an item.");
}

$title = $_POST['title'];
$description = $_POST['description'];
$category = $_POST['category'];
$price = $_POST['price'];
$user_id = $_SESSION['user_id'];

$sql = "INSERT INTO items (title, description, category, price, user_id)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssdi", $title, $description, $category, $price, $user_id);

if ($stmt->execute()) {
    header("Location: my_items.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>
