<?php
session_start();
include 'connection.php';
include 'header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid item ID.");
}

$item_id = (int) $_GET['id'];
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;

// Fetch item details with seller and highest bid
$sql = "SELECT i.*, u.username AS seller, MAX(b.amount) AS highest_bid
        FROM items i
        JOIN users u ON i.user_id = u.id
        LEFT JOIN bids b ON b.item_id = i.id
        WHERE i.id = ?
        GROUP BY i.id";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die("Item not found.");
}

$auction_ended = strtotime($item['end_time']) <= time();

// Get bid history
$history_sql = "SELECT b.amount, b.created_at, u.username
                FROM bids b
                JOIN users u ON b.user_id = u.id
                WHERE b.item_id = ?
                ORDER BY b.created_at DESC";

$history_stmt = $conn->prepare($history_sql);
$history_stmt->bind_param("i", $item_id);
$history_stmt->execute();
$history_result = $history_stmt->get_result();

// Determine winner if auction ended
$winner = null;
if ($auction_ended && $item['highest_bid']) {
    $win_sql = "SELECT u.username FROM bids b
                JOIN users u ON b.user_id = u.id
                WHERE b.item_id = ? AND b.amount = ?
                ORDER BY b.created_at ASC LIMIT 1";
    $win_stmt = $conn->prepare($win_sql);
    $win_stmt->bind_param("id", $item_id, $item['highest_bid']);
    $win_stmt->execute();
    $win_row = $win_stmt->get_result()->fetch_assoc();
    if ($win_row) $winner = $win_row['username'];
}
?>

<style>
body {
    background-color: #f9f9f9;
    font-family: Arial, sans-serif;
    padding: 30px;
}
.item-container {
    background: white;
    max-width: 800px;
    margin: auto;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.item-container img {
    max-width: 100%;
    max-height: 300px;
    object-fit: cover;
    margin-bottom: 15px;
}
.countdown {
    font-weight: bold;
    color: #e67e22;
}
form input, form textarea {
    width: 100%;
    margin-top: 10px;
    padding: 10px;
}
form button {
    margin-top: 15px;
    padding: 10px 20px;
    background-color: #7b1e7a;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.history ul {
    margin-top: 10px;
    padding-left: 20px;
}
</style>

<div class="item-container">
    <h2><?= htmlspecialchars($item['title']) ?></h2>
    <?php if (!empty($item['image_path']) && file_exists($item['image_path'])): ?>
        <img src="<?= $item['image_path'] ?>" alt="Item Image">
    <?php endif; ?>

    <p><strong>Seller:</strong> <?= htmlspecialchars($item['seller']) ?></p>
    <p><strong>Ends:</strong> <?= date("M d, Y g:i A", strtotime($item['end_time'])) ?></p>
    <p class="countdown" id="countdown"></p>

    <p><strong>Current Highest Bid:</strong>
        $<?= number_format($item['highest_bid'] ?? $item['price'], 2) ?>
    </p>

    <?php if ($auction_ended): ?>
        <p><strong style="color: green;">Auction Ended</strong>
        <?= $winner ? "- Winner: " . htmlspecialchars($winner) : "- No Bids" ?></p>
    <?php elseif ($is_logged_in): ?>
        <form action="bid_handler.php" method="POST">
            <input type="hidden" name="item_id" value="<?= $item_id ?>">
            <input type="text" name="name" placeholder="Full Name" required>
            <textarea name="shipping_address" placeholder="Shipping Address" required></textarea>
            <input type="number" name="bid_amount" step="0.01" placeholder="Enter bid amount" required>
            <button type="submit">Place Bid</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Log in</a> to place a bid.</p>
    <?php endif; ?>

    <div class="history">
        <h3>Bid History</h3>
        <?php if ($history_result->num_rows > 0): ?>
            <ul>
                <?php while ($bid = $history_result->fetch_assoc()): ?>
                    <li>
                        <?= htmlspecialchars($bid['username']) ?> bid $<?= number_format($bid['amount'], 2) ?>
                        on <?= date("M d, Y g:i A", strtotime($bid['created_at'])) ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No bids yet.</p>
        <?php endif; ?>
    </div>

    <p style="margin-top: 20px;"><a href="all_items.php">← Back to Listings</a></p>
</div>

<script>
const countdownEl = document.getElementById("countdown");
const endTime = new Date("<?= date('c', strtotime($item['end_time'])) ?>").getTime();

function updateCountdown() {
    const now = new Date().getTime();
    const distance = endTime - now;

    if (distance <= 0) {
        countdownEl.textContent = "Auction ended";
        clearInterval(timer);
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    countdownEl.textContent = `Time left: ${days}d ${hours}h ${minutes}m ${seconds}s`;
}
const timer = setInterval(updateCountdown, 1000);
updateCountdown();
</script>

<?php include 'footer.php'; ?>
