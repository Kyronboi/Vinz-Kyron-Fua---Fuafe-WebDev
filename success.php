<?php
require 'database/config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: register.php');
    exit;
}

$pdo  = getConnection();
$sql  = "SELECT id, username, email, age, location FROM customer WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    header('Location: register.php');
    exit;
}
?>
<?php include 'auth_header.php'; ?>

<div class="auth-section">
    <div class="auth-card">
        <h2 style="color: green;">Customer Registered!</h2>
        <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; text-align: left; margin: 20px 0;">
            <tr><th>ID</th><td><?= htmlspecialchars($customer['id']) ?></td></tr>
            <tr><th>Username</th><td><?= htmlspecialchars($customer['username']) ?></td></tr>
            <tr><th>Email</th><td><?= htmlspecialchars($customer['email']) ?></td></tr>
            <tr><th>Age</th><td><?= htmlspecialchars($customer['age']) ?></td></tr>
            <tr><th>Delivery Location</th><td><?= htmlspecialchars($customer['location']) ?></td></tr>
        </table>
        <p><a href="login.php">Go to Login</a> | <a href="register.php">Register another</a></p>
    </div>
</div>

<?php include 'auth_footer.php'; ?>