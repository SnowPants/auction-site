<?php
session_start();
include 'connection.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    die("Unauthorized access.");
}

$item_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch item info
$sql = "SELECT * FROM items WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $item_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Item not found or access denied.");
}

$item = $result->fetch_assoc();

// Fetch categories
$categories = $conn->query("SELECT id, name FROM categories");
?>

<h2>Edit Item</h2>
<form action="edit_item_handler.php" method="post" enctype="multipart/form-data" style="margin: 20px;">
  <input type="hidden" name="item_id" value="<?= $item['id'] ?>">

  <label>Title:<br>
    <input type="text" name="title" value="<?= htmlspecialchars($item['title']) ?>" required>
  </label><br><br>

  <label>Description:<br>
    <textarea name="description" rows="5" cols="40" required><?= htmlspecialchars($item['description']) ?></textarea>
  </label><br><br>

  <label>Category:<br>
    <select name="category_id" required>
      <?php while ($cat = $categories->fetch_assoc()): ?>
        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $item['category_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </label><br><br>

  <label>Starting Price ($):<br>
    <input type="number" name="price" step="0.01" value="<?= $item['price'] ?>" required>
  </label><br><br>

  <label>Auction End Time:<br>
    <input type="datetime-local" name="end_time" 
           value="<?= date('Y-m-d\TH:i', strtotime($item['end_time'])) ?>" required>
  </label><br><br>

  <?php if (!empty($item['image_path']) && file_exists($item['image_path'])): ?>
    <p>Current Image:<br>
      <img src="<?= $item['image_path'] ?>" alt="Current Image" style="max-width: 150px;"><br>
    </p>
  <?php endif; ?>

  <label>Replace Image (optional):<br>
    <input type="file" name="item_image" accept="image/*">
  </label><br><br>

  <button type="submit">Update Item</button>
</form>

<p><a href="my_items.php">← Back to My Items</a></p>

<?php include 'footer.php'; ?>
