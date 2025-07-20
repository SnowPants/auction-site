<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to place a bid.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid access method.");
}

$user_id = $_SESSION['user_id'];
$item_id = $_POST['item_id'] ?? null;
$bid_amount = $_POST['bid_amount'] ?? null;
$full_name = trim($_POST['name'] ?? '');
$shipping_address = trim($_POST['shipping_address'] ?? '');

if (!$item_id || !$bid_amount || !$full_name || !$shipping_address) {
    die("All fields are required.");
}

// Fetch the item
$stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    die("Item not found.");
}

// Check if auction ended using correct timezone
$current_time = time();
$end_time = strtotime($item['end_time']);

if ($end_time <= $current_time) {
    die("Auction has ended. You can no longer bid on this item.");
}

// Get highest current bid
$bid_stmt = $conn->prepare("SELECT MAX(amount) AS max_bid FROM bids WHERE item_id = ?");
$bid_stmt->bind_param("i", $item_id);
$bid_stmt->execute();
$max_result = $bid_stmt->get_result()->fetch_assoc();
$current_highest = $max_result['max_bid'] ?? 0;

$minimum_required = max($item['price'], $current_highest + 0.01);
if ($bid_amount < $minimum_required) {
    die("Your bid must be at least $" . number_format($minimum_required, 2));
}

// Insert bid
$insert = $conn->prepare("INSERT INTO bids (item_id, user_id, amount, created_at, full_name, shipping_address) VALUES (?, ?, ?, NOW(), ?, ?)");
$insert->bind_param("iisss", $item_id, $user_id, $bid_amount, $full_name, $shipping_address);

if ($insert->execute()) {
    header("Location: item.php?id=$item_id&success=1");
    exit();
} else {
    die("Failed to place bid. Please try again.");
}
?>
