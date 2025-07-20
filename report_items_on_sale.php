<?php
session_start();
include 'connection.php';

// Optional: restrict to logged-in admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Items Currently on Sale</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f5fa;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #2c3e50;
            color: white;
            padding: 1rem;
        }

        nav a {
            color: white;
            margin-right: 1rem;
            text-decoration: none;
        }

        .container {
            padding: 30px;
        }

        h1 {
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<nav>
    <strong>Auction Admin Dashboard</strong>
    <a href="index.php">Back to Site</a>
    <a href="admin_dashboard.php">Dashboard Home</a>
    <a href="report_items_sold.php">Sold Report</a>
    <a href="report_items_on_sale.php">On Sale Report</a>
    <a href="report_users.php">Users</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <h1>Items Currently on Sale</h1>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Highest Bid</th>
                <th>Seller</th>
                <th>Days Listed</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT 
                        i.title,
                        COALESCE(u.username, 'Unknown') AS seller,
                        i.created_at,
                        (
                            SELECT MAX(b.amount) 
                            FROM bids b 
                            WHERE b.item_id = i.id
                        ) AS highest_bid
                    FROM items i
                    LEFT JOIN users u ON i.user_id = u.id
                    WHERE i.end_time > NOW()";
            
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                $days = round((time() - strtotime($row['created_at'])) / 86400);
                $bid = $row['highest_bid'] ? "$" . number_format($row['highest_bid'], 2) : "No bids";
                echo "<tr>
                        <td>{$row['title']}</td>
                        <td>{$bid}</td>
                        <td>{$row['seller']}</td>
                        <td>{$days} days</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
