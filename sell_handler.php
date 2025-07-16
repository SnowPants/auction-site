<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to post an item.";
    exit();
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
    echo "Item posted successfully! <a href='index.php'>Back to home</a>";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
