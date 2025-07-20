<?php
session_start();
include 'connection.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view this page.");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT i.id AS item_id, i.title, i.price AS starting_price, i.end_time, i.image_path,
               MAX(b.amount) AS highest_bid
        FROM items i
        LEFT JOIN bids b ON i.id = b.item_id
        WHERE i.user_id = ?
        GROUP BY i.id, i.title, i.price, i.end_time, i.image_path
        ORDER BY i.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
  body {
    background-color: #f7f7f7;
    color: #000;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
  }

  h2 {
    text-align: center;
  }

.grid-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 30px; /* Applies spacing between grid cells */
  justify-items: center;
  margin-top: 20px;
}



.item-card {
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 15px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  width: 320px;
  height: 500px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  overflow: hidden;
}


  .item-card h3 {
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 18px;
  }

  .item-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 4px;
    margin-bottom: 10px;
  }

  .item-card button {
    margin: 5px 5px 0 0;
    padding: 6px 12px;
    font-size: 14px;
  }

  .delete-btn {
    background-color: red;
    color: white;
    border: none;
  }

  .countdown {
    font-weight: bold;
    color: orange;
    margin: 10px 0;
  }
</style>

<h2>My Listings</h2>

<?php if ($result->num_rows > 0): ?>
  <div class="grid-container">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="item-card">
        <div class="card-body">
          <h3><?= htmlspecialchars($row['title']) ?></h3>
          <?php if (!empty($row['image_path']) && file_exists($row['image_path'])): ?>
            <img src="<?= $row['image_path'] ?>" alt="Image">
          <?php endif; ?>
          <p><strong>Current Price:</strong> $
            <?= number_format($row['highest_bid'] ?? $row['starting_price'], 2) ?>
          </p>
          <p><strong>Ends:</strong> <?= date("M d, Y g:i A", strtotime($row['end_time'])) ?></p>
          <p class="countdown" id="countdown-<?= $row['item_id'] ?>"></p>
        </div>

        <div>
          <a href="item.php?id=<?= $row['item_id'] ?>"><button>View</button></a>
          <a href="edit_item.php?id=<?= $row['item_id'] ?>"><button>Edit</button></a>

          <form method="POST" action="delete_item.php" onsubmit="return confirm('Are you sure you want to delete this item?');" style="display:inline;">
            <input type="hidden" name="item_id" value="<?= $row['item_id'] ?>">
            <button type="submit" class="delete-btn">Delete</button>
          </form>
        </div>
      </div>

      <script>
        const endTime<?= $row['item_id'] ?> = new Date("<?= date('c', strtotime($row['end_time'])) ?>").getTime();
        const countdownEl<?= $row['item_id'] ?> = document.getElementById("countdown-<?= $row['item_id'] ?>");

        function updateCountdown<?= $row['item_id'] ?>() {
          const now = new Date().getTime();
          const distance = endTime<?= $row['item_id'] ?> - now;

          if (distance <= 0) {
            countdownEl<?= $row['item_id'] ?>.textContent = "Auction ended";
            clearInterval(timer<?= $row['item_id'] ?>);
            return;
          }

          const days = Math.floor(distance / (1000 * 60 * 60 * 24));
          const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          const seconds = Math.floor((distance % (1000 * 60)) / 1000);

          countdownEl<?= $row['item_id'] ?>.textContent =
            `Time left: ${days}d ${hours}h ${minutes}m ${seconds}s`;
        }

        const timer<?= $row['item_id'] ?> = setInterval(updateCountdown<?= $row['item_id'] ?>, 1000);
        updateCountdown<?= $row['item_id'] ?>();
      </script>
    <?php endwhile; ?>
  </div>
<?php else: ?>
  <p style="text-align:center;">You haven’t listed any items yet.</p>
<?php endif; ?>

<p style="text-align: center; margin-top: 30px;">
  <a href="index.php">← Back to Home</a>
</p>

<?php include 'footer.php'; ?>
