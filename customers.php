<?php
require 'database/config.php';
$pdo  = getConnection();
$sql  = "SELECT id, username, email, age, location FROM customer ORDER BY id ASC";
$stmt = $pdo->query($sql);
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'auth_header.php'; ?>
<div class="auth-section">
    <div class="auth-card" style="max-width: 600px;">
        <h2>All Customers</h2>
        <p><a href="register.php">Add New Customer</a></p>
        <?php if (empty($customers)): ?>
            <p>No customers registered yet.</p>
        <?php else: ?>
            <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; text-align: left;">
                <tr><th>ID</th><th>Username</th><th>Email</th><th>Age</th><th>Location</th></tr>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= htmlspecialchars($customer['id']) ?></td>
                        <td><?= htmlspecialchars($customer['username']) ?></td>
                        <td><?= htmlspecialchars($customer['email']) ?></td>
                        <td><?= htmlspecialchars($customer['age']) ?></td>
                        <td><?= htmlspecialchars($customer['location']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php include 'auth_footer.php'; ?>