<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in.");
}

$user_id = $_SESSION['user_id'];
$item_id = intval($_POST['item_id']);

// Sanitize and collect form data
$title = $_POST['title'];
$description = $_POST['description'];
$category_id = $_POST['category_id'];
$price = $_POST['price'];
$end_time = $_POST['end_time'];

// First, retrieve the old image path in case it's not being replaced
$sql = "SELECT image_path FROM items WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $item_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Item not found or access denied.");
}

$item = $result->fetch_assoc();
$image_path = $item['image_path'];

// Handle new image upload (optional)
if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $original_name = basename($_FILES["item_image"]["name"]);
    $unique_name = uniqid() . "_" . $original_name;
    $target_file = $upload_dir . $unique_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_type, $allowed_types)) {
        die("Invalid image type. Only JPG, JPEG, PNG, GIF allowed.");
    }

    if (!move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
        die("Image upload failed.");
    }

    $image_path = $target_file; // Overwrite with new image path
}

// Update the item
$update_sql = "UPDATE items 
               SET title = ?, description = ?, category_id = ?, price = ?, end_time = ?, image_path = ?
               WHERE id = ? AND user_id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("ssidssii", $title, $description, $category_id, $price, $end_time, $image_path, $item_id, $user_id);

if ($update_stmt->execute()) {
    header("Location: my_items.php");
    exit();
} else {
    echo "Error updating item: " . $update_stmt->error;
}
?>
