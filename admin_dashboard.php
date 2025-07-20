<?php
session_start();
include 'connection.php';

// Optional: restrict access to only logged-in admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f9fc;
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

        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 30px;
            padding: 30px;
        }

        .widget {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-height: 400px;
            overflow-y: auto;
        }

        .widget h2 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #ecf0f1;
            color: #333;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #2980b9;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 10px;
}
.btn:hover {
    background-color: #1c5980;
}

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


<div class="dashboard-container">

   <div class="dashboard-container">

    <div class="widget">
        <h2>Items Sold by Category</h2>
        <p>View all sold items by category and date with buyer details.</p>
        <a href="report_items_sold.php" class="btn">View Report</a>
    </div>

    <div class="widget">
        <h2>Items Currently on Sale</h2>
        <p>See items currently listed along with seller info and bids.</p>
        <a href="report_items_on_sale.php" class="btn">View Report</a>
    </div>

    <div class="widget">
        <h2>User Table Dump</h2>
        <p>See all registered users with full account info.</p>
        <a href="report_users.php" class="btn">View Report</a>
    </div>

</div>

    </div>

</body>
</html>
