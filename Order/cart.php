<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../database/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$pdo = getConnection();
$stmt = $pdo->prepare("
    SELECT c.id as cart_id, c.size, c.quantity, c.price, p.name, p.image_path
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.customer_id = :customer_id
");
$stmt->execute(['customer_id' => $_SESSION['user_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<?php include __DIR__ . '/../header.php'; ?>
<section class="cart-section">
    <div class="container">
        <h1 class="page-title">Your Cart</h1>
        <?php if (empty($cart_items)): ?>
            <p class="empty-cart">Your cart is empty. <a href="menu.php">Browse our menu</a></p>
        <?php else: ?>
            <div class="cart-table">
                <table>
                    <tr><th>Item</th><th>Size</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Action</th></tr>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><img src="../<?= htmlspecialchars($item['image_path']) ?>" width="50" height="50"> <?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['size']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            <td><a href="remove_from_cart.php?id=<?= $item['cart_id'] ?>" class="btn-remove">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="cart-total">
                    <strong>Total: $<?= number_format($total, 2) ?></strong>
                    <a href="checkout.php" class="btn-auth">Proceed to Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/../auth_footer.php'; ?>