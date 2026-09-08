<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Placed</title>
    <link rel="stylesheet"
      href="../styles.css?v=<?= filemtime(__DIR__ . '/../styles.css') ?>">
</head>
<body>
    <div class="auth-section">
        <div class="auth-card">
            <h2 style="color: green;">Order Placed Successfully!</h2>
            <p>Thank you for your order! We'll deliver it to: <strong><?= htmlspecialchars($_SESSION['location']) ?></strong></p>
            <p><a href="menu.php">Back to Menu</a></p>
        </div>
    </div>
</body>
</html>