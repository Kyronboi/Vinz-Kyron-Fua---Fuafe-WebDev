<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuafe - Coffee Shop</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo filemtime('styles.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header class="navbar">
        <div class="logo-icon">
            <img src="pictures/cup.png" alt="Logo Icon">
            <?php if ($isLoggedIn): ?>
                <span class="welcome-text">Welcome <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <?php endif; ?>
        </div>
        <nav class="nav-links">
            <a href="index.php#home" class="active">Home</a>
            <a href="menu.php">Our Menu</a>
            <a href="index.php#contact">Contact Us</a>
            <a href="index.php#blog">Blog</a>
        </nav>
        <div class="signin-btn">
            <?php if ($isLoggedIn): ?>
                <a href="logout.php" class="btn-logout">Sign Out</a>
            <?php else: ?>
                <a href="login.php" class="btn-signin"><i class="fas fa-user"></i> Sign In</a>
            <?php endif; ?>
        </div>
    </header>