<?php
session_start();
include 'connection.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
  echo "<p style='text-align:center;'>Please <a href='login.php'>log in</a> to view your bids.</p>";
  include 'footer.php';
  exit;
}

date_default_timezone_set('America/Chicago');

$user_id = $_SESSION['user_id'];
$selected_category = $_GET['category'] ?? '';

// Build SQL
$sql = "SELECT i.id, i.title, i.price AS starting_price, i.end_time, i.image_path,
               MAX(b.amount) AS highest_bid,
               c.name AS category
        FROM items i
        JOIN bids b ON i.id = b.item_id
        LEFT JOIN categories c ON i.category_id = c.id
        WHERE b.user_id = ?";

if (!empty($selected_category)) {
    $sql .= " AND i.category_id = ?";
}

$sql .= " GROUP BY i.id, i.title, i.price, i.end_time, i.image_path, c.name
          ORDER BY i.end_time ASC";

// Prepare and bind
if (!empty($selected_category)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $selected_category);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

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
  width: auto;
  height: 500px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  overflow: hidden;
  margin-bottom: 20px; /* ADD THIS */
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

<h2>Your Bids</h2>

<form method="GET">
  <label for="category">Filter by Category:</label>
  <select name="category" id="category" onchange="this.form.submit()">
    <option value="">All Categories</option>
    <?php
    $cat_result = $conn->query("SELECT id, name FROM categories ORDER BY name");
    while ($cat = $cat_result->fetch_assoc()):
        $is_selected = ($cat['id'] == $selected_category) ? 'selected' : '';
    ?>
      <option value="<?= htmlspecialchars($cat['id']) ?>" <?= $is_selected ?>>
        <?= htmlspecialchars($cat['name']) ?>
      </option>
    <?php endwhile; ?>
  </select>
</form>

<?php if ($result->num_rows > 0): ?>
  <div class="grid-container">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="item-card">
        <h3><?= htmlspecialchars($row['title']) ?></h3>
        <?php if (!empty($row['image_path']) && file_exists($row['image_path'])): ?>
          <img src="<?= $row['image_path'] ?>" alt="Image">
        <?php endif; ?>
        <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
        <p><strong>Current Price:</strong> $
          <?= number_format($row['highest_bid'] ?? $row['starting_price'], 2) ?>
        </p>
        <p><strong>Ends:</strong> <?= date("M d, Y g:i A", strtotime($row['end_time'])) ?></p>
        <p class="countdown" id="countdown-<?= $row['id'] ?>"></p>
        <a href="item.php?id=<?= $row['id'] ?>"><button class="bid-btn">View & Bid</button></a>
      </div>

      <script>
        const countdownEl<?= $row['id'] ?> = document.getElementById("countdown-<?= $row['id'] ?>");
        const endTime<?= $row['id'] ?> = <?= strtotime($row['end_time']) ?> * 1000;

        function updateCountdown<?= $row['id'] ?>() {
          const now = new Date().getTime();
          const distance = endTime<?= $row['id'] ?> - now;

          if (distance <= 0) {
            countdownEl<?= $row['id'] ?>.textContent = "Auction ended";
            clearInterval(timer<?= $row['id'] ?>);
            return;
          }

          const days = Math.floor(distance / (1000 * 60 * 60 * 24));
          const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          const seconds = Math.floor((distance % (1000 * 60)) / 1000);

          let timeText = "Time left: ";
          if (days > 0) timeText += `${days}d `;
          timeText += `${hours}h ${minutes}m ${seconds}s`;

          countdownEl<?= $row['id'] ?>.textContent = timeText;
        }

        const timer<?= $row['id'] ?> = setInterval(updateCountdown<?= $row['id'] ?>, 1000);
        updateCountdown<?= $row['id'] ?>();
      </script>
    <?php endwhile; ?>
  </div>
<?php else: ?>
  <p style="text-align:center;">You have not placed any bids yet.</p>
<?php endif; ?>

<p style="text-align:center; margin-top: 30px;">
  <a href="index.php">← Back to Home</a>
</p>

<?php include 'footer.php'; ?>
