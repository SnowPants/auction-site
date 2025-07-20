<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add an item.");
}

$title = $_POST['title'];
$description = $_POST['description'];
$category_id = $_POST['category_id'];
$price = $_POST['price'];
$end_time = date('Y-m-d H:i:s', strtotime($_POST['end_time']));
$user_id = $_SESSION['user_id'];

$image_path = null;

if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";

    // Make sure uploads folder exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $original_name = basename($_FILES["item_image"]["name"]);
    $unique_name = uniqid() . "_" . preg_replace("/[^A-Za-z0-9.\-_]/", "", $original_name);
    $target_file = $target_dir . $unique_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Invalid file type. Only JPG, JPEG, PNG & GIF allowed.");
    }

    if (!move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
        die("❌ File was received but could not be saved. Check write permissions on 'uploads/' directory.");
    }

    $image_path = $target_file;
} else {
    $uploadError = $_FILES['item_image']['error'];
    $errorMsg = match ($uploadError) {
        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => "Uploaded file is too large.",
        UPLOAD_ERR_PARTIAL => "File was only partially uploaded.",
        UPLOAD_ERR_NO_FILE => "No file was uploaded.",
        UPLOAD_ERR_NO_TMP_DIR => "Missing temporary folder.",
        UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
        UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload.",
        default => "Unknown upload error."
    };
    die("Image upload failed: " . $errorMsg);
}

// Insert into DB
$sql = "INSERT INTO items (title, description, category_id, price, user_id, end_time, image_path)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssdiss", $title, $description, $category_id, $price, $user_id, $end_time, $image_path);

if ($stmt->execute()) {
    header("Location: my_items.php");
    exit();
} else {
    echo "Database Error: " . $stmt->error;
}
?>
