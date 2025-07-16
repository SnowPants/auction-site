<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$full_name = $_POST['full_name'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$credit_card = !empty($_POST['credit_card']) ? $_POST['credit_card'] : null;

$sql = "UPDATE users SET full_name = ?, address = ?, phone = ?, credit_card = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $full_name, $address, $phone, $credit_card, $user_id);

if ($stmt->execute()) {
    header("Location: profile.php");
    exit();
} else {
    echo "Error updating profile: " . $stmt->error;
}
?>
