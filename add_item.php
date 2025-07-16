<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add an item.");
}
include 'header.php';
?>

<h2>Add a New Item</h2>

<form action="add_item_handler.php" method="post" style="margin: 20px;">
  <label>Title:<br>
    <input type="text" name="title" required>
  </label><br><br>

  <label>Description:<br>
    <textarea name="description" rows="5" cols="40" required></textarea>
  </label><br><br>

  <label>Category:<br>
    <input type="text" name="category" required>
  </label><br><br>

  <label>Starting Price ($):<br>
    <input type="number" step="0.01" name="price" required>
  </label><br><br>

  <button type="submit">Submit</button>
</form>

<p style="margin: 20px;"><a href="index.php">← Back to Home</a></p>

<?php include 'footer.php'; ?>
