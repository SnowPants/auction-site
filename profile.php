<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch current user's details
$user_id = $_SESSION['user_id'];
$sql = "SELECT full_name, address, credit_card, username, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Mask credit card
function maskCreditCard($number) {
    if (!$number) return "Not provided";
    $last4 = substr($number, -4);
    return str_repeat('**** ', 3) . $last4;
}

include 'header.php';
?>

<h2>My Profile</h2>
<div style="margin: 20px;">
  <p><strong>Full Name:</strong> <?php echo htmlspecialchars($row['full_name']); ?></p>
  <p><strong>Username:</strong> <?php echo htmlspecialchars($row['username']); ?></p>
  <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
  <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($row['phone']); ?></p>
  <p><strong>Credit Card:</strong> <?php echo maskCreditCard($row['credit_card']); ?></p>
</div>
<p><a href="edit_profile.php" class="button"> Edit My Profile</a></p>

<?php include 'footer.php'; ?>
