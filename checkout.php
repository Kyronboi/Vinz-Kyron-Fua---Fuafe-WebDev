<?php
session_start();
require 'database/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = getConnection();
$stmt = $pdo->prepare("SELECT SUM(price * quantity) as total FROM cart WHERE customer_id = :customer_id");
$stmt->execute(['customer_id' => $_SESSION['user_id']]);
$total = $stmt->fetchColumn() ?: 0;

// Clear the cart
$stmt = $pdo->prepare("DELETE FROM cart WHERE customer_id = :customer_id");
$stmt->execute(['customer_id' => $_SESSION['user_id']]);
?>
<?php include 'header.php'; ?>
<section class="auth-section">
    <div class="auth-card checkout-card">
        <h2 style="color: green;">Order Placed Successfully!</h2>
        <p>Thank you for your order! We'll deliver to: <strong><?= htmlspecialchars($_SESSION['location']) ?></strong></p>
        <p>Total Charged: <strong>$<?= number_format($total, 2) ?></strong></p>
        <a href="menu.php" class="btn-auth checkout-btn">Continue Shopping</a>
    </div>
</section>
<?php include 'auth_footer.php'; ?>