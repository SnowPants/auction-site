<?php
session_start();
include 'connection.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die("Invalid request method.");
}

if (!isset($_SESSION['user_id']) || !isset($_POST['item_id'])) {
    http_response_code(403);
    die("Unauthorized access. Missing session or item ID.");
}

$item_id = intval($_POST['item_id']);
$user_id = $_SESSION['user_id'];

// Confirm item ownership
$sql = "SELECT image_path FROM items WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $item_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Item not found or permission denied.");
}

$row = $result->fetch_assoc();

// Delete image file
if (!empty($row['image_path']) && file_exists($row['image_path'])) {
    unlink($row['image_path']);
}

// Delete bids first (to avoid foreign key constraint)
$delete_bids_sql = "DELETE FROM bids WHERE item_id = ?";
$bids_stmt = $conn->prepare($delete_bids_sql);
$bids_stmt->bind_param("i", $item_id);
$bids_stmt->execute();

// Now delete the item
$delete_item_sql = "DELETE FROM items WHERE id = ?";
$item_stmt = $conn->prepare($delete_item_sql);
$item_stmt->bind_param("i", $item_id);

if ($item_stmt->execute()) {
    header("Location: my_items.php?deleted=1");
    exit();
} else {
    die("Error deleting item: " . $item_stmt->error);
}
?>
