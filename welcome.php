<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'header.php';
?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<p style="margin: 20px;">You are now logged in. Use the navigation above to manage your account.</p>



<?php include 'footer.php'; ?>
