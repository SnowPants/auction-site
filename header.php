<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Auction Site</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
  <strong>Auction Site</strong> |
  <a href="index.php">Home</a>
  <?php if (isset($_SESSION['user_id'])): ?>
    <a href="my_bids.php">My Bids</a>
    <a href="my_items.php">My Listings</a>
    <a href="add_item.php">Add Item</a>
    <a href="logout.php">Logout</a>
  <?php else: ?>
    <a href="register.php">Register</a>
    <a href="login.php">Login</a>
  <?php endif; ?>
</nav>
<hr>
