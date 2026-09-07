<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'database/config.php';
$isLoggedIn = isset($_SESSION['user_id']);

$pdo = getConnection();
$sql = "SELECT id, name, description, price, image_path FROM products ORDER BY id ASC";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Our Menu - Fualfe</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo filemtime('styles.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header class="navbar">
        <div class="logo-group">
            <div class="logo-icon"><img src="pictures/cup.png" alt="Logo Icon"></div>
            <?php if ($isLoggedIn): ?>
                <span class="welcome-text">Welcome <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <?php endif; ?>
        </div>
        <nav class="nav-links">
            <a href="index.php#home">Home</a>
            <a href="menu.php" class="active">Our Menu</a>
            <a href="index.php#contact">Contact Us</a>
            <a href="index.php#blog">Blog</a>
        </nav>
        
        <!-- NEW: Header Actions (Cart + Sign In/Out) -->
        <div class="header-actions">
            <?php if ($isLoggedIn): ?>
                <a href="cart.php" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?= $cartCount ?></span>
                </a>
            <?php endif; ?>
            
            <div class="signin-btn">
                <?php if ($isLoggedIn): ?>
                    <a href="logout.php" class="btn-logout">Sign Out</a>
                <?php else: ?>
                    <a href="login.php" class="btn-signin"><i class="fas fa-user"></i> Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <section class="full-menu-section">
        <div class="container">
            <h1 class="page-title">Our Coffee Menu</h1>
            <p class="page-subtitle">Click a product to see more details.</p>
            
            <div class="menu-grid">
                <?php if (empty($products)): ?>
                    <p style="color: #fff;">No products found.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="menu-card-lg">
                            <a href="product.php?id=<?= $product['id'] ?>" class="card-link">
                                <div class="card-img">
                                    <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                </div>
                                <h3><?= htmlspecialchars($product['name']) ?></h3>
                                <p><?= htmlspecialchars($product['description']) ?></p>
                                <!-- Fetch Regular size price (assume it exists) -->
                                <?php 
                                $stmt = $pdo->prepare("SELECT price FROM product_sizes WHERE product_id = :id AND size_name = 'Regular' LIMIT 1");
                                $stmt->execute(['id' => $product['id']]);
                                $regularPrice = $stmt->fetchColumn();
                                if (!$regularPrice) $regularPrice = $product['price']; 
                                ?>
                                <span class="price">$<?= number_format($regularPrice, 2) ?></span>
                            </a>
                            <!-- Add to Cart Form -->
                            <form action="add_to_cart.php" method="POST" class="add-cart-form">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="size" value="Regular">
                                <input type="hidden" name="price" value="<?= $regularPrice ?>">
                                <button type="submit" class="btn-add-cart">Add to Cart</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>
</html>