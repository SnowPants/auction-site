<?php include 'header.php'; ?>

<h2>Create an Account</h2>

<form method="post" action="register_handler.php" style="margin: 20px;">
  <label>Full Name:<br>
    <input type="text" name="full_name" required>
  </label><br><br>

  <label>Username:<br>
    <input type="text" name="username" required>
  </label><br><br>

  <label>Password:<br>
    <input type="password" name="password" required>
  </label><br><br>

  <label>Confirm Password:<br>
    <input type="password" name="confirm_password" required>
  </label><br><br>

  <label>Shipping Address:<br>
    <input type="text" name="address" required>
  </label><br><br>

  <label>Credit Card Info:<br>
    <input type="text" name="credit_card">
  </label><br><br>

  <label>Phone Number:<br>
    <input type="text" name="phone" required>
  </label><br><br>

  <input type="submit" value="Register">
</form>

<p style="margin: 20px;">
  <a href="login.php" class="login-btn">Sign into my account</a>
</p>

<?php include 'footer.php'; ?>
