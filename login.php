<?php include 'header.php'; ?>

<h2>Login</h2>

<form method="post" action="login_handler.php" style="margin: 20px;">
  <label>Username:<br>
    <input type="text" name="username" required>
  </label><br><br>

  <label>Password:<br>
    <input type="password" name="password" required>
  </label><br><br>

  <input type="submit" value="Login">
</form>

<p style="margin: 20px;">
  Don’t have an account? 
  <a href="register.php" style="color: #7b1e7a; font-weight: bold;">Register here</a>.
</p>

<?php include 'footer.php'; ?>
