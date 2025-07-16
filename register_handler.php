<?php
session_start();
include 'connection.php';

// Collect input values
$full_name = $_POST['full_name'];
$address = $_POST['address'];
$credit_card = !empty($_POST['credit_card']) ? $_POST['credit_card'] : null;
$phone = $_POST['phone'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Check passwords match
if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare SQL insert
$sql = "INSERT INTO users (full_name, address, credit_card, phone, username, password)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $full_name, $address, $credit_card, $phone, $username, $hashed_password);

// Execute and redirect
if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['username'] = $username;
    header("Location: welcome.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>
