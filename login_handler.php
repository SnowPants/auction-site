<?php
session_start();
include 'connection.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Initialize login attempts per session
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = [];
}

if (!isset($_SESSION['login_attempts'][$username])) {
    $_SESSION['login_attempts'][$username] = 0;
}

// Block if too many failed attempts
if ($_SESSION['login_attempts'][$username] >= 10) {
    echo "Too many failed attempts for this username. Please try again later.";
    exit();
}

// Look up user
$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        // Login successful
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['login_attempts'][$username] = 0;

        header("Location: index.php");
        exit();
    }
}

// Login failed
$_SESSION['login_attempts'][$username] += 1;
$remaining = 10 - $_SESSION['login_attempts'][$username];

echo "<p>Incorrect username or password. You have $remaining attempt(s) left.</p>";
echo '<p><a href="login.php">Try again</a></p>';
?>
