<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<?php include 'auth_header.php'; ?>
<section class="auth-section">
    <div class="auth-card">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
        <p>You are logged in as: <?= htmlspecialchars($_SESSION['email']) ?></p>
        <p><strong>Delivery Location:</strong> <?= htmlspecialchars($_SESSION['location']) ?></p>
        <a href="logout.php" class="btn-auth" style="text-decoration: none;">Logout</a>
    </div>
</section>
<?php include 'auth_footer.php'; ?>