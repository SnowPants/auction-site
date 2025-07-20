<?php
session_start();

// Hardcoded admin credentials
$admin_username = "admin";
$admin_password = "admin123"; // Plain text (not secure for real applications)

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === $admin_username && $password === $admin_password) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $admin_username;
    header("Location: admin_dashboard.php");
    exit();
} else {
    echo "<p>Invalid admin credentials.</p>";
    echo '<p><a href="admin_login.php">Try again</a></p>';
}
?>
