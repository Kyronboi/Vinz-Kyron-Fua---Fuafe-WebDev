<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'database/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$size = trim($_POST['size'] ?? '');

if (!$product_id || !$size) {
    header('Location: menu.php');
    exit;
}

$pdo = getConnection();

// SECURE: Look up the price from the database instead of trusting the form
$stmt = $pdo->prepare("SELECT price FROM product_sizes WHERE product_id = :pid AND size_name = :size");
$stmt->execute(['pid' => $product_id, 'size' => $size]);
$price = $stmt->fetchColumn();

if (!$price) {
    header('Location: menu.php');
    exit;
}

// Check if item already exists for this customer
$stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE customer_id = :customer_id AND product_id = :product_id AND size = :size");
$stmt->execute(['customer_id' => $_SESSION['user_id'], 'product_id' => $product_id, 'size' => $size]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    $newQty = $existing['quantity'] + 1;
    $stmt = $pdo->prepare("UPDATE cart SET quantity = :qty WHERE id = :id");
    $stmt->execute(['qty' => $newQty, 'id' => $existing['id']]);
} else {
    $stmt = $pdo->prepare("INSERT INTO cart (customer_id, product_id, size, quantity, price) VALUES (:customer_id, :product_id, :size, 1, :price)");
    $stmt->execute([
        'customer_id' => $_SESSION['user_id'],
        'product_id' => $product_id,
        'size' => $size,
        'price' => $price
    ]);
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'menu.php'));
exit;