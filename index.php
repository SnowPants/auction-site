<?php
session_start();
include 'connection.php';
include 'header.php';
date_default_timezone_set('America/Chicago');

// Fetch top 4 items ending soon
$soon_stmt = $conn->prepare("
    SELECT id, title, image_path, end_time
    FROM items
    WHERE end_time > NOW()
    ORDER BY end_time ASC
    LIMIT 4
");
$soon_stmt->execute();
$soon_items = $soon_stmt->get_result();

// Fetch categories
$cat_result = $conn->query("SELECT id, name FROM categories ORDER BY name");
?>

<style>
  body {
    background-color: #f3f4f6;
    font-family: 'Segoe UI', sans-serif;
  }

  .hero {
    background: linear-gradient(to right, #fff, #f0f0ff);
    padding: 40px;
    text-align: center;
    border-bottom: 2px solid #ccc;
  }

  .hero h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
  }

  .hero p {
    font-size: 1.2rem;
    margin-bottom: 20px;
  }

  .hero a {
    display: inline-block;
    margin: 10px;
    padding: 12px 24px;
    font-size: 16px;
    text-decoration: none;
    border-radius: 6px;
  }

  .login-btn {
    background-color: #6c5ce7;
    color: white;
  }

  .register-btn {
    background-color: #00b894;
    color: white;
  }

  .section {
    padding: 30px;
    max-width: 1200px;
    margin: auto;
  }

  .section h2 {
    margin-bottom: 15px;
    color: #333;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
  }

  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }

  .card {
    background: white;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    border: 1px solid #ccc;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }

  .card img {
    max-width: 100%;
    max-height: 140px;
    object-fit: cover;
    margin-bottom: 10px;
    border-radius: 4px;
  }

  .card-title {
    font-weight: bold;
    font-size: 1.1rem;
    margin: 5px 0;
  }

  .card small {
    color: #999;
  }

  .card a {
    display: inline-block;
    margin-top: 10px;
    color: #0984e3;
    text-decoration: none;
  }

  .category-link {
    background-color: #dfe6e9;
    padding: 10px 15px;
    border-radius: 20px;
    display: inline-block;
    margin: 5px;
    color: #2d3436;
    text-decoration: none;
    font-weight: bold;
  }
</style>

<div class="hero">
  <h1>Welcome to the Auction Zone!</h1>
  <p>Bid on rare finds, collectibles, tech gear, and more!</p>
  <?php if (!isset($_SESSION['user_id'])): ?>
    <a href="login.php" class="login-btn">Sign In</a>
    <a href="register.php" class="register-btn">Create Account</a>
  <?php else: ?>
    <a href="all_items.php" class="login-btn">Browse All Auctions</a>
    <a href="my_items.php" class="register-btn">My Listings</a>
  <?php endif; ?>
</div>

<div class="section">
  <h2>Browse by Category</h2>
  <?php while ($cat = $cat_result->fetch_assoc()): ?>
    <a href="all_items.php?category=<?= $cat['id'] ?>" class="category-link">
      <?= htmlspecialchars($cat['name']) ?>
    </a>
  <?php endwhile; ?>
</div>

<div class="section">
  <h2>Ending Soon</h2>
  <div class="grid">
    <?php while ($item = $soon_items->fetch_assoc()): ?>
      <div class="card">
        <?php if (!empty($item['image_path']) && file_exists($item['image_path'])): ?>
          <img src="<?= $item['image_path'] ?>" alt="Auction Image">
        <?php endif; ?>
        <div class="card-title"><?= htmlspecialchars($item['title']) ?></div>
        <small>Ends: <?= date("M d, Y g:i A", strtotime($item['end_time'])) ?></small>
        <br>
        <a href="item.php?id=<?= $item['id'] ?>">View & Bid →</a>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<p style="text-align:center; margin: 30px;">
  <a href="all_items.php">Explore All Auctions →</a>
</p>

<?php include 'footer.php'; ?>
