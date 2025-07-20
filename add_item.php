<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add an item.");
}
include 'connection.php';
include 'header.php';

// Fetch categories from DB
$category_sql = "SELECT id, name FROM categories";
$category_result = $conn->query($category_sql);
?>

<h2>Add a New Item</h2>

<!-- Important: Add enctype for file upload -->
<form action="add_item_handler.php" method="post" enctype="multipart/form-data" style="margin: 20px;">
  <label>Title:<br>
    <input type="text" name="title" required>
  </label><br><br>

  <label>Description:<br>
    <textarea name="description" rows="5" cols="40" required></textarea>
  </label><br><br>

  <label>Category:<br>
    <select name="category_id" required>
      <option value="">--Select Category--</option>
      <?php while ($row = $category_result->fetch_assoc()): ?>
        <option value="<?php echo $row['id']; ?>">
          <?php echo htmlspecialchars($row['name']); ?>
        </option>
      <?php endwhile; ?>
    </select>
  </label><br><br>

  <label>Starting Price ($):<br>
    <input type="number" step="0.01" name="price" required>
  </label><br><br>

  <label>Auction End Time:<br>
    <input type="datetime-local" name="end_time" required>
  </label><br><br>

  <!-- New image upload field -->
  <label>Item Image:<br>
    <input type="file" name="item_image" accept="image/*" required>
  </label><br><br>

  <button type="submit">Submit</button>
</form>

<p style="margin: 20px;"><a href="index.php">← Back to Home</a></p>

<?php include 'footer.php'; ?>
