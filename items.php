<?php
// Show PHP errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connection.php';

// SQL to get all items and their sellers
$sql = "SELECT items.id, items.title, items.price, items.category, users.username AS seller
        FROM items
        JOIN users ON items.user_id = users.id
        ORDER BY items.created_at DESC";

$result = $conn->query($sql);

// Check if query failed
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Items for Sale</title>
</head>
<body>
  <h2>Items for Sale</h2>

  <?php if ($result->num_rows > 0): ?>
    <ul>
      <?php while ($row = $result->fetch_assoc()): ?>
        <li>
          <strong><?php echo htmlspecialchars($row['title']); ?></strong> 
          ($<?php echo number_format($row['price'], 2); ?>) - 
          Category: <?php echo htmlspecialchars($row['category']); ?> - 
          Seller: <?php echo htmlspecialchars($row['seller']); ?> |
          <a href="item.php?id=<?php echo $row['id']; ?>">View / Bid</a>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p>No items listed yet.</p>
  <?php endif; ?>

  <p><a href="index.php">Back to Home</a></p>
</body>
</html>
