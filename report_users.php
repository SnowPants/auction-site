<?php
session_start();
include 'connection.php';

// Restrict to admin only
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Table Report</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 20px; }
        h1 { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ccc; }
        th { background: #eee; }
    </style>
</head>
<nav style="background-color: #2c3e50; color: white; padding: 1rem; font-family: Arial, sans-serif;">
    <strong style="font-size: 18px;">Auction Site Admin Dashboard</strong> &nbsp; | &nbsp;
    <a href="index.php" style="color: #00aced; text-decoration: none; margin-right: 15px;">Main Site</a>
    <a href="report_items_sold.php" style="color: white; text-decoration: none; margin-right: 15px;">Items Sold</a>
    <a href="report_items_on_sale.php" style="color: white; text-decoration: none; margin-right: 15px;">Items on Sale</a>
    <a href="report_users.php" style="color: white; text-decoration: none; margin-right: 15px;">User Table</a>
    <a href="logout.php" style="color: #e74c3c; text-decoration: none;">Logout</a>
</nav>

<body>

<h1>Registered Users Report</h1>

<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Full Name</th>
            <th>Registered On</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT id, username, email, full_name, created_at FROM users";
        $result = $conn->query($sql);

        if ($result->num_rows === 0) {
            echo "<tr><td colspan='5'>No users found.</td></tr>";
        } else {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>" . htmlspecialchars($row['username']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['full_name']) . "</td>
                        <td>{$row['created_at']}</td>
                      </tr>";
            }
        }
        ?>
    </tbody>
</table>

</body>
</html>
