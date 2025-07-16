<?php include 'header.php'; ?>

<h1>Welcome to the Auction Site</h1>

<?php if (!isset($_SESSION['user_id'])): ?>
  <div style="margin: 40px;">
    <a href="login.php" class="login-btn">Sign into my account</a>
    <a href="register.php" class="register-btn">Create an Account</a>
  </div>
<?php else: ?>
  <p style="margin: 20px;">Welcome back! Use the navigation above to manage your listings or browse items.</p>
<?php endif; ?>

<?php include 'footer.php'; ?>
