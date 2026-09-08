<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../database/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$cart_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($cart_id) {
    $pdo = getConnection();

    // 1. Get the product_id and quantity from the cart BEFORE deleting
    $stmt = $pdo->prepare("SELECT product_id, quantity FROM cart WHERE id = :id AND customer_id = :customer_id");
    $stmt->execute(['id' => $cart_id, 'customer_id' => $_SESSION['user_id']]);
    $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cartItem) {
        $product_id = $cartItem['product_id'];
        $quantity = $cartItem['quantity'];

        // 2. Add the stock back to the products table
        $stmt = $pdo->prepare("UPDATE products SET stock = stock + :qty WHERE id = :pid");
        $stmt->execute(['qty' => $quantity, 'pid' => $product_id]);

        // 3. Delete the item from the cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = :id AND customer_id = :customer_id");
        $stmt->execute(['id' => $cart_id, 'customer_id' => $_SESSION['user_id']]);
    }
}

header('Location: cart.php');
exit;