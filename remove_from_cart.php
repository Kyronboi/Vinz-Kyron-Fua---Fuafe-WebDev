<?php
session_start();
require 'database/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$cart_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($cart_id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = :id AND customer_id = :customer_id");
    $stmt->execute(['id' => $cart_id, 'customer_id' => $_SESSION['user_id']]);
}

header('Location: cart.php');
exit;