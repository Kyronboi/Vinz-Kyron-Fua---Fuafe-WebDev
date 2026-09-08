<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'database/config.php';

// Access control
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Define isLoggedIn for the header logic
$isLoggedIn = isset($_SESSION['user_id']);
$cartCount = 0;

$pdo = getConnection();
$stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE customer_id = :customer_id");
$stmt->bindValue(':customer_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$cartCount = $stmt->fetchColumn() ?: 0;

$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$productId) {
    header('Location: menu.php');
    exit;
}

// Fetch product details INCLUDING stock
$sql = "SELECT id, name, description, price, image_path, stock FROM products WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $productId, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: menu.php');
    exit;
}

// Fetch sizes
$sql = "SELECT size_name, price FROM product_sizes WHERE product_id = :id ORDER BY price ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $productId, PDO::PARAM_INT);
$stmt->execute();
$sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Stock logic
$outOfStock = $product['stock'] <= 0;
$lowStock = $product['stock'] > 0 && $product['stock'] <= 10;
$status = $_GET['status'] ?? null; // For error messages from add_to_cart.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Fuafe</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo filemtime('styles.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header class="navbar">
        <div class="logo-group">
            <div class="logo-icon"><img src="pictures/cup.png" alt="Logo Icon"></div>
            <span class="welcome-text">Welcome <?= htmlspecialchars($_SESSION['username']) ?>!</span>
        </div>
        <nav class="nav-links">
            <a href="index.php#home">Home</a>
            <a href="menu.php" class="active">Our Menu</a>
            <a href="index.php#contact">Contact Us</a>
            <a href="index.php#blog">Blog</a>
        </nav>
        
        <div class="header-actions">
            <?php if ($isLoggedIn): ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin.php" class="admin-link"><i class="fas fa-user-shield"></i> Admin</a>
                <?php endif; ?>
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

    <section class="product-detail-section">
        <div class="container">
            <a href="menu.php" class="back-link">← Back to Menu</a>
            
            <?php if ($status === 'outofstock'): ?>
                <p class="stock-message out-of-stock" style="text-align: center;">Sorry, this item is out of stock!</p>
            <?php endif; ?>

            <div class="product-detail-card">
                <div class="product-detail-image">
                    <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <div class="product-detail-info">
                    <h1><?= htmlspecialchars($product['name']) ?></h1>
                    <p class="product-full-desc"><?= htmlspecialchars($product['description']) ?></p>
                    
                    <!-- Stock Status Display -->
                    <?php if ($outOfStock): ?>
                        <p class="stock-message out-of-stock">Out of Stock</p>
                    <?php elseif ($lowStock): ?>
                        <p class="stock-message low-stock">Low stock: <?= $product['stock'] ?> left</p>
                    <?php endif; ?>

                    <div class="size-selection">
                        <h3>Choose your size:</h3>
                        <?php if (empty($sizes)): ?>
                            <p>No size options available. Price: $<?= number_format($product['price'], 2) ?></p>
                            <!-- If no sizes but stock exists, still show the button -->
                            <?php if (!$outOfStock): ?>
                                <form method="POST" action="add_to_cart.php">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="size" value="Regular">
                                    <button type="submit" class="btn-auth">Add to Cart</button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if (!$outOfStock): ?>
                                <form method="POST" action="add_to_cart.php">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <?php foreach ($sizes as $size): ?>
                                        <div class="size-option">
                                            <input type="radio" id="size-<?= $size['size_name'] ?>" name="size" value="<?= htmlspecialchars($size['size_name']) ?>" required>
                                            <label for="size-<?= $size['size_name'] ?>">
                                                <span class="size-label"><?= htmlspecialchars($size['size_name']) ?></span>
                                                <span class="size-price">$<?= number_format($size['price'], 2) ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                    <!-- Disable button if out of stock -->
                                    <button type="submit" class="btn-auth" <?= $outOfStock ? 'disabled' : '' ?>>
                                        <?= $outOfStock ? 'Out of Stock' : 'Add to Cart' ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>