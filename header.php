<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fualfe Coffee Shop</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo filemtime('styles.css'); ?>">
    <!-- Font Awesome for the arrows and icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <section class="hero-section" id="home">
        <!-- Top Navigation Bar -->
        <header class="navbar">
            <div class="logo-icon">
                <img src="pictures/cup.png" alt="Logo Icon">
            </div>
           <nav class="nav-links">
                <a href="#" data-target="home">Home</a>
                <a href="menu.php" class="active">Our Menu</a>
                <a href="#" data-target="contact">Contact Us</a>
                <a href="#" data-target="blog">Blog</a>
            </nav>
            <div class="signin-btn">
              <?php if ($isLoggedIn): ?>
                  <a href="dashboard.php">My Account</a>
                  <a href="logout.php" style="background-color: #333;">Logout</a>
              <?php else: ?>
                  <a href="login.php"><i class="fas fa-user"></i> Sign In</a>
              <?php endif; ?>
            </div>
        </header>