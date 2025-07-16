<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view this page.");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT i.id AS item_id, i.title, i.price AS starting_price,
               MAX(b.amount) AS highest_bid
        FROM items i
        LEFT JOIN bids b ON i.id = b.item_id
        WHERE i.user_id = ?
        GROUP BY i.id, i.title, i.price
        ORDER BY i.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Listings</title>
</head>
<body>
  <h2>My Listings</h2>

  <?php if ($result->num_rows > 0): ?>
    <ul>
      <?php while ($row = $result->fetch_assoc()): ?>
        <li>
          <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
          Starting Price: $<?php echo number_format($row['starting_price'], 2); ?><br>
          Current Highest Bid: 
          <?php echo $row['highest_bid'] ? "$" . number_format($row['highest_bid'], 2) : "No bids yet"; ?><br>
          <a href="item.php?id=<?php echo $row['item_id']; ?>">View Item</a>
        </li>
        <hr>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p>You haven’t listed any items yet.</p>
  <?php endif; ?>

  <p><a href="index.php">← Back to Home</a></p>
</body>
</html>
