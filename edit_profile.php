<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT full_name, address, credit_card, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

include 'header.php';
?>

<h2>Edit Profile</h2>
<form method="post" action="update_profile.php" style="margin: 20px;">
  <label>Full Name:<br>
    <input type="text" name="full_name" value="<?php echo htmlspecialchars($row['full_name']); ?>" required>
  </label><br><br>

  <label>Shipping Address:<br>
    <input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" required>
  </label><br><br>

  <label>Phone Number:<br>
    <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
  </label><br><br>

  <label>Credit Card Info:<br>
    <input type="text" name="credit_card" value="<?php echo htmlspecialchars($row['credit_card']); ?>">
  </label><br><br>

  <input type="submit" value="Update Profile">
</form>

<p style="margin: 20px;"><a href="profile.php">← Back to Profile</a></p>

<?php include 'footer.php'; ?>
