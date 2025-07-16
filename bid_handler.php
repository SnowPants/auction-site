<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to bid.");
}

$item_id = intval($_POST['item_id']);
$user_id = $_SESSION['user_id'];
$amount = floatval($_POST['amount']);

// Get current highest bid
$sql = "SELECT MAX(amount) AS highest_bid FROM bids WHERE item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$current_high = $row['highest_bid'] ?? 0;

// Get starting price if no bids yet
if (!$current_high) {
    $start_sql = "SELECT price FROM items WHERE id = ?";
    $start_stmt = $conn->prepare($start_sql);
    $start_stmt->bind_param("i", $item_id);
    $start_stmt->execute();
    $start_result = $start_stmt->get_result();
    $start_row = $start_result->fetch_assoc();
    $current_high = $start_row['price'];
}

// Compare with bid
if ($amount <= $current_high) {
    die(" Your bid must be higher than $" . number_format($current_high, 2));
}

// Insert the bid
$insert_sql = "INSERT INTO bids (item_id, user_id, amount) VALUES (?, ?, ?)";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("iid", $item_id, $user_id, $amount);

if ($insert_stmt->execute()) {
    header("Location: item.php?id=" . $item_id);
    exit();
} else {
    echo " Error placing bid: " . $insert_stmt->error;
}
?>
