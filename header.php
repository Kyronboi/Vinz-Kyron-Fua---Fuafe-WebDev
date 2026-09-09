<?php
// filepath: c:\xampp\htdocs\Fuafe website\header.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database/config.php';

$scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$isCommentsPage = strpos($scriptPath, '/Comments/') !== false;
$isOrderPage = strpos($scriptPath, '/Order/') !== false;
$isSubfolderPage = $isCommentsPage || $isOrderPage;

$sitePrefix = $isSubfolderPage ? '../' : '';

$menuUrl = $isOrderPage ? 'menu.php' : ($isCommentsPage ? '../Order/menu.php' : 'Order/menu.php');
$cartUrl = $isOrderPage ? 'cart.php' : ($isCommentsPage ? '../Order/cart.php' : 'Order/cart.php');

$isLoggedIn = isset($_SESSION['user_id']);
$cartCount = 0;

if ($isLoggedIn) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE customer_id = :customer_id");
    $stmt->bindValue(':customer_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $cartCount = $stmt->fetchColumn() ?: 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuafe - Coffee Shop</title>
    <link rel="stylesheet"
      href="<?= $sitePrefix ?>styles.css?v=<?= filemtime(__DIR__ . '/styles.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header class="navbar">
        <div class="logo-group">
            <div class="logo-icon">
                <img src="<?= $sitePrefix ?>pictures/cup.png" alt="Logo Icon">
            </div>
            <?php if ($isLoggedIn): ?>
                <span class="welcome-text">Welcome <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <?php endif; ?>
        </div>
        
        <nav class="nav-links">
            <a href="<?= $sitePrefix ?>index.php#home">Home</a>
            <a href="<?= $menuUrl ?>">Our Menu</a>
            <a href="<?= $sitePrefix ?>index.php#contact">Contact Us</a>
            <a href="<?= $sitePrefix ?>index.php#blog">Blog</a>
        </nav>
        
        <!-- NEW: Header Actions (Cart + Sign In/Out) -->
        <div class="header-actions">
            <?php if ($isLoggedIn): ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="<?= $sitePrefix ?>admin.php" class="admin-link"><i class="fas fa-user-shield"></i> Admin</a>
                <?php endif; ?>
                <a href="<?= $cartUrl ?>" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?= $cartCount ?></span>
                </a>
            <?php endif; ?>
            <div class="signin-btn">
                <?php if ($isLoggedIn): ?>
                    <a href="<?= $sitePrefix ?>logout.php" class="btn-logout">Sign Out</a>
                <?php else: ?>
                    <a href="<?= $sitePrefix ?>login.php" class="btn-signin"><i class="fas fa-user"></i> Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </header>