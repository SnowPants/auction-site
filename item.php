<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

if (!isset($_GET['id'])) {
    die("No item selected.");
}

$item_id = intval($_GET['id']);

// Fetch item info
$sql = "SELECT items.*, users.username AS seller
        FROM items
        JOIN users ON items.user_id = users.id
        WHERE items.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$item_result = $stmt->get_result();

if ($item_result->num_rows === 0) {
    die("Item not found.");
}

$item = $item_result->fetch_assoc();

// Fetch highest bid
$bid_sql = "SELECT MAX(amount) AS highest_bid FROM bids WHERE item_id = ?";
$bid_stmt = $conn->prepare($bid_sql);
$bid_stmt->bind_param("i", $item_id);
$bid_stmt->execute();
$bid_result = $bid_stmt->get_result();
$bid_row = $bid_result->fetch_assoc();
$highest_bid = $bid_row['highest_bid'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
  <title>View Item</title>
</head>
<body>
  <h2><?php echo htmlspecialchars($item['title']); ?></h2>
  <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category']); ?></p>
  <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
  <p><strong>Starting Price:</strong> $<?php echo number_format($item['price'], 2); ?></p>
  <p><strong>Current Highest Bid:</strong> $<?php echo number_format($highest_bid ?? $item['price'], 2); ?></p>
  <p><strong>Seller:</strong> <?php echo htmlspecialchars($item['seller']); ?></p>

  <?php if (isset($_SESSION['username'])): ?>
    <hr>
    <h3>Place a Bid</h3>
    <form method="post" action="bid_handler.php">
      <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
      <label>Your Bid ($): <input type="number" name="amount" step="0.01" required></label><br><br>
      <input type="submit" value="Submit Bid">
    </form>
    <hr>
<h3>Bid History</h3>

<?php
$history_sql = "SELECT b.amount, b.created_at, u.username 
                FROM bids b 
                JOIN users u ON b.user_id = u.id 
                WHERE b.item_id = ? 
                ORDER BY b.created_at DESC";

$history_stmt = $conn->prepare($history_sql);
$history_stmt->bind_param("i", $item_id);
$history_stmt->execute();
$history_result = $history_stmt->get_result();

if ($history_result->num_rows > 0): ?>
  <ul>
    <?php while ($bid = $history_result->fetch_assoc()): ?>
      <li>
        $<?php echo number_format($bid['amount'], 2); ?> by 
        <?php echo htmlspecialchars($bid['username']); ?> 
        on <?php echo date("M j, Y g:i a", strtotime($bid['created_at'])); ?>
      </li>
    <?php endwhile; ?>
  </ul>
<?php else: ?>
  <p>No bids yet.</p>
<?php endif; ?>

  <?php else: ?>
    <p><em>You must <a href="login.php">log in</a> to place a bid.</em></p>
  <?php endif; ?>

  <p><a href="items.php">← Back to Items</a></p>
</body>
</html>
