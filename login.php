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


<?php include 'footer.php'; ?>

