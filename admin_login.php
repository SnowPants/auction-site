<!-- admin_login.php -->
<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
</head>
<body>
  <h2>Admin Login</h2>
  <form method="POST" action="admin_login_handler.php">
    <label>Username:</label>
    <input type="text" name="username" required><br><br>
    
    <label>Password:</label>
    <input type="password" name="password" required><br><br>
    
    <button type="submit">Login</button>
  </form>
</body>
</html>
