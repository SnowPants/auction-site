<?php
session_start();
include 'connection.php';

// Restrict to admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle form input
$category_id = $_GET['category_id'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Get categories for dropdown
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Items Sold Report</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 20px; }
        h1 { margin-bottom: 10px; }
        form { margin-bottom: 20px; }
        label { margin-right: 10px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background: #eee; }
    </style>
</head>
<body>

<nav style="background-color: #2c3e50; color: white; padding: 1rem; font-family: Arial, sans-serif;">
    <strong style="font-size: 18px;">Auction Site Admin Dashboard</strong> &nbsp; | &nbsp;
    <a href="index.php" style="color: #00aced; text-decoration: none; margin-right: 15px;">Main Site</a>
    <a href="report_items_sold.php" style="color: white; text-decoration: none; margin-right: 15px;">Items Sold</a>
    <a href="report_items_on_sale.php" style="color: white; text-decoration: none; margin-right: 15px;">Items on Sale</a>
    <a href="report_users.php" style="color: white; text-decoration: none; margin-right: 15px;">User Table</a>
    <a href="logout.php" style="color: #e74c3c; text-decoration: none;">Logout</a>
</nav>

<h1>Items Sold Report</h1>

<form method="GET">
    <label for="category_id">Category:</label>
    <select name="category_id" id="category_id">
        <option value="">All Categories</option>
        <?php while ($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['id'] ?>" <?= $category_id == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">

    <label for="end_date">End Date:</label>
    <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">

    <button type="submit">Run Report</button>
</form>

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Sold Price</th>
            <th>Buyer</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $params = [];
    $types = '';
    $where = "WHERE i.end_time < NOW()";

    if ($start_date && $end_date) {
        $where .= " AND DATE(i.end_time) BETWEEN ? AND ?";
        $params[] = $start_date;
        $params[] = $end_date;
        $types .= 'ss';
    } elseif ($start_date) {
        $where .= " AND DATE(i.end_time) >= ?";
        $params[] = $start_date;
        $types .= 's';
    } elseif ($end_date) {
        $where .= " AND DATE(i.end_time) <= ?";
        $params[] = $end_date;
        $types .= 's';
    }

    if ($category_id) {
        $where .= " AND i.category_id = ?";
        $params[] = $category_id;
        $types .= 'i';
    }

    $sql = "SELECT 
                i.title,
                c.name AS category,
                b.amount AS sold_price,
                u.username AS buyer
            FROM items i
            JOIN categories c ON i.category_id = c.id
            JOIN bids b ON b.item_id = i.id
            JOIN users u ON b.user_id = u.id
            $where
            AND b.amount = (
                SELECT MAX(b2.amount)
                FROM bids b2
                WHERE b2.item_id = i.id
            )
            GROUP BY i.id";

    $stmt = $conn->prepare($sql);
    if ($types && count($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<tr><td colspan='4'>No items sold in this date range and category.</td></tr>";
    } else {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['title']) . "</td>
                    <td>" . htmlspecialchars($row['category']) . "</td>
                    <td>$" . number_format($row['sold_price'], 2) . "</td>
                    <td>" . htmlspecialchars($row['buyer']) . "</td>
                  </tr>";
        }
    }
    ?>
    </tbody>
</table>

</body>
</html>
