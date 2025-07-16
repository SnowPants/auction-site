<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Sell an Item</title>
</head>
<body>
  <h2>Sell an Item</h2>
  <form method="post" action="sell_handler.php">
    <label>Item Title: <input type="text" name="title" required></label><br><br>
    <label>Description: <br><textarea name="description" rows="5" cols="40" required></textarea></label><br><br>
    <label>Category: <input type="text" name="category" required></label><br><br>
    <label>Starting Price ($): <input type="number" name="price" step="0.01" required></label><br><br>
    <input type="submit" value="Post Item">
  </form>
  <p><a href="index.php">Back to Home</a></p>
</body>
</html>
