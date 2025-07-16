<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view this page.");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT i.id AS item_id, i.title, b.amount, b.created_at
        FROM bids b
        JOIN items i ON b.item_id = i.id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Bids</title>
</head>
<body>
  <h2>My Bids</h2>

  <?php if ($result->num_rows > 0): ?>
    <ul>
      <?php while ($row = $result->fetch_assoc()): ?>
        <li>
          <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
          Bid: $<?php echo number_format($row['amount'], 2); ?><br>
          On: <?php echo date("M j, Y g:i a", strtotime($row['created_at'])); ?><br>
          <a href="item.php?id=<?php echo $row['item_id']; ?>">View Item</a>
        </li>
        <hr>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p>You haven’t placed any bids yet.</p>
  <?php endif; ?>

  <p><a href="index.php">← Back to Home</a></p>
</body>
</html>
