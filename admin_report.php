<?php
session_start();
include 'connection.php';

// You can optionally add an admin check here
// if ($_SESSION['username'] !== 'admin') { die("Access denied."); }

function maskCreditCard($number) {
    if (!$number) return "Not provided";
    $last4 = substr($number, -4);
    return str_repeat('**** ', 3) . $last4;
}

$sql = "SELECT id, full_name, username, address, credit_card FROM users ORDER BY id";
$result = $conn->query($sql);

include 'header.php';
?>

<h2>Admin Report: Registered Users</h2>

<table border="1" cellpadding="10" style="margin: 20px; background-color: #fff;">
  <thead>
    <tr>
      <th>ID</th>
      <th>Full Name</th>
      <th>Username</th>
      <th>Address</th>
      <th>Credit Card (masked)</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
        <td><?php echo htmlspecialchars($row['username']); ?></td>
        <td><?php echo htmlspecialchars($row['address']); ?></td>
        <td><?php echo maskCreditCard($row['credit_card']); ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include 'footer.php'; ?>
