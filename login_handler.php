<?php
session_start();
include 'connection.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Initialize login attempts counter if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Check if the user already failed 3 times
if ($_SESSION['login_attempts'] >= 3) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        // Successful login
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['login_attempts'] = 0; // Reset attempts
        header("Location: welcome.php");
        exit();
    }
}

// If login failed
$_SESSION['login_attempts'] += 1;

if ($_SESSION['login_attempts'] >= 3) {
    // Too many attempts, redirect to homepage
    header("Location: index.php");
    exit();
} else {
    // Let them try again
    $remaining = 3 - $_SESSION['login_attempts'];
    echo "Incorrect username or password. You have $remaining attempt(s) left.";
    echo '<p><a href="login.php">Try again</a></p>';
}
?>
