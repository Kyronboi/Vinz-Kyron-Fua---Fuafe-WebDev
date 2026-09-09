<?php
session_start();
require 'database/config.php';
$isLoggedIn = isset($_SESSION['user_id']);
$cartCount = 0;
if ($isLoggedIn) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE customer_id = :customer_id");
    $stmt->bindValue(':customer_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $cartCount = $stmt->fetchColumn() ?: 0;
}

// Fetch all comments
$pdo = getConnection();
$stmt = $pdo->query("SELECT c.*, cu.username FROM comments c JOIN customer cu ON c.customer_id = cu.id WHERE c.is_hidden = 0 ORDER BY c.created_at DESC");
$all_comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Limit to first 3 comments for the carousel (plus "Find More" card = 4 cards max)
$comments = array_slice($all_comments, 0, 3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuafe Coffee Shop</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo filemtime('styles.css'); ?>">
    <!-- Font Awesome for the arrows and icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <section class="hero-section" id="home">
        <!-- Top Navigation Bar -->
        <header class="navbar">
            <div class="logo-group">
                <div class="logo-icon">
                    <img src="pictures/cup.png" alt="Logo Icon">
                </div>
                <?php if ($isLoggedIn): ?>
                    <span class="welcome-text">Welcome <?= htmlspecialchars($_SESSION['username']) ?>!</span>
                <?php endif; ?>
            </div>
            
            <nav class="nav-links">
                <a href="index.php#home">Home</a>
                <a href="Order/menu.php">Our Menu</a>
                <a href="index.php#contact">Contact Us</a>
                <a href="index.php#blog">Blog</a>
            </nav>
            
            <!-- Header Actions (Cart + Sign In/Out) -->
            <div class="header-actions">
                <?php if ($isLoggedIn): ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="admin.php" class="admin-link"><i class="fas fa-user-shield"></i> Admin</a>
                    <?php endif; ?>
                    <a href="Order/cart.php" class="cart-icon">
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

        <!-- Main Hero Content -->
        <div class="hero-content">
            <!-- Left Side: Logo & Tagline -->
            <div class="hero-left">
                <img src="pictures/MainTwo.svg" alt="Fualfe Logo" class="big-logo">
                <p class="tagline">All you need is coffee, wifi, and a dream.</p>
                <a href="Order/menu.php" class="order-btn">Order Now</a>
            </div>

            <!-- Right Side: Menu Cards -->
            <div class="hero-right">
                <div class="menu-cards-container">
                 <div class="carousel-track">
                    <!-- Espresso Card -->
                    <div class="menu-card">
                        <div class="card-img">
                            <img src="pictures/Asset 1.png" alt="Espresso">
                        </div>
                        <h3>Espresso</h3>
                        <p>Strong, bold, and pure. A classic shot of espresso.</p>
                        <a href="Order/menu.php" class="find-more">Find out more <i class="fas fa-arrow-right"></i></a>
                    </div>

                    <!-- Mocha Card -->
                    <div class="menu-card">
                        <div class="card-img">
                            <img src="pictures/Asset 2.png" alt="Mocha">
                        </div>
                        <h3>Mocha</h3>
                        <p>Rich chocolate combined with our signature espresso.</p>
                        <a href="Order/menu.php" class="find-more">Find out more <i class="fas fa-arrow-right"></i></a>
                    </div>

                    <!-- Latte Card -->
                    <div class="menu-card">
                        <div class="card-img">
                            <img src="pictures/Asset 3.png" alt="Latte">
                        </div>
                        <h3>Latte</h3>
                        <p>Smooth milk with a delicate layer of foam.</p>
                        <a href="Order/menu.php" class="find-more">Find out more <i class="fas fa-arrow-right"></i></a>
                    </div>

                    <!-- NEW: Cappuccino (4th product) -->
                    <div class="menu-card">
                        <div class="card-img"><img src="pictures/cappuccino.jpg" alt="Cappuccino"></div>
                        <h3>Cappuccino</h3>
                        <p>Perfect balance of espresso, steam, and foam.</p>
                        <a href="Order/menu.php" class="find-more">Find out more <i class="fas fa-arrow-right"></i></a>
                    </div>
                 </div>
                </div>
                
                <!-- Navigation Arrows -->
                <div class="navigation-arrows">
                    <button class="arrow left" id="prevBtn"><i class="fas fa-chevron-left"></i></button>
                    <button class="arrow right" id="nextBtn"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>

  <!-- ================= ABOUT US SECTION ================= -->
    <section class="about-section">
        <img src="bg-design/Beans-tl.png" class="deco-top-left" alt="beans">
        <img src="bg-design/Beans-tr.png" class="deco-top-right" alt="beans">

        <img src="bg-design/Leaves-ml.png" class="deco-Lbottom-left" alt="leaves">
        <img src="bg-design/Leaves-mr.png" class="deco-Lbottom-right" alt="leaves">
        <div class="container about-wrapper">
            <div class="about-text">
                <span class="section-label">About Us</span>
                <h2 class="section-title">What is Fuafe?</h2>
                <p>Fuafe is your local, neighborhood coffee delivery service dedicated to bringing fresh, handcrafted drinks right to your doorstep. We believe great days start with great coffee, which is why we brew every order using locally roasted beans and deliver them iced, hot, or blended fast and fresh.</p>
            </div>
            <div class="about-image">
                <!-- REPLACE WITH REAL IMAGE: About Section Image -->
                <img src="pictures/white background.png" alt="Background" class="base-img">
                <img src="pictures/tilted coffee.svg" alt="About Us Coffee" class="overlay-img">
            </div>
        </div>
    </section>

    <!-- ================= WHAT MAKES OUR COFFEE SPECIAL ================= -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">What makes our coffee special</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-mug-hot"></i></div>
                    <h3>The Perfect Cup</h3>
                    <p>Every drink is precisely brewed to order using calibrated recipes to guarantee rich flavor  and smooth texture from the very first sip to the last.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-seedling"></i></div>
                    <h3>The Best Blend</h3>
                    <p>We source ethically grown, freshly roasted beans from local growers to craft signature blends that balanced, aromatic, and bold.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-leaf"></i></div>
                    <h3>Natural Ingredients</h3>
                    <p>From real chocolate drizzles to pure dairy and high-quality syrups, we use natural ingredients without artificial additives or fillers.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fas fa-smile"></i></div>
                    <h3>Made with a Smile</h3>
                    <p>Our baristas and delivery team care about every detail, making sure your favorite coffee arrives quickly and with a touch of local warmth.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= TESTIMONIALS / WHAT OUR CUSTOMERS SAY ================= -->
    <section class="testimonials-section" id="blog">
    <div class="container">
        <img src="bg-design/Beans-ml.png" class="deco-middle-left" alt="beans">
        <img src="bg-design/Beans-mr.png" class="deco-middle-right" alt="beans">
        <img src="bg-design/Leavs-br.png" class="deco-lebottom-left" alt="leaves">
        <img src="bg-design/Leaves-bl.png" class="deco-lebottom-right" alt="leaves">

        <h2 class="section-title">What our customers say</h2>
        
        <!-- The WRAPPER hides the off-screen cards -->
        <div class="testimonials-wrapper" id="commentCarousel">
            <!-- The TRACK slides left and right -->
            <div class="testimonial-track">
                <!-- Loop through comments -->
                <?php foreach ($comments as $comment): ?>
                    <div class="testimonial-card">
                        <div class="testimonial-avatar"><i class="fas fa-user-circle"></i></div>
                        <h4><?= htmlspecialchars($comment['username']) ?></h4>
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $comment['rating'] ? 'active' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p><?= htmlspecialchars($comment['comment']) ?></p>
                    </div>
                <?php endforeach; ?>
                
                <!-- 4th Card: Find More Comments -->
                <div class="testimonial-card find-more-card">
                    <div class="testimonial-avatar"><i class="fas fa-comments"></i></div>
                    <h4>More Reviews</h4>
                    <p>See what other coffee lovers are saying.</p>
                    <a href="Comments/comments.php" class="btn-auth" style="text-decoration:none; margin-top:10px;">Find More Comments</a>
                </div>
            </div>
            
            <div class="testimonial-arrows">
                <button class="arrow left" id="prevComment"><i class="fas fa-chevron-left"></i></button>
                <button class="arrow right" id="nextComment"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>

        <!-- Changed to a Button linking to add_comment.php -->
        <div class="add-comment">
            <a href="Comments/add_comment.php" class="btn-auth">Add a Comment</a>
        </div>
    </div>
</section>

        <!-- ================= CONTACT SECTION ================= -->
    <section class="contact-section" id="contact">
        <!-- Background Image (make sure you have this in your images folder) -->
        <div class="contact-bg"></div>

        <div class="container contact-container">
            <!-- Left: Tilted Coffee Image (polaroid style) -->
            <div class="contact-photo">
                <!-- REPLACE WITH REAL IMAGE: Contact Coffee Image -->
                <img src="pictures/cold-coffee.svg" alt="Iced Coffee">
            </div>

            <!-- Right: Get in Touch Text -->
            <div class="contact-info">
                <h2 class="contact-title">Get in Touch</h2>
                <p>Have questions about our menu, delivery zones, or catering for an event? Drop us a line or give us a call. Our team is always ready to help you get your daily coffee fix delivered straight to your home or office.</p>
            </div>
        </div>

        <!-- Bottom Bar: QR, Address, Phone, Socials -->
        <div class="contact-footer">
            <div class="container footer-content">
                <!-- QR Code -->
                <div class="qr-box">
                    <!-- REPLACE WITH REAL IMAGE: QR Code -->
                    <img src="pictures/Rickrolling_QR_code.png" alt="QR Code">
                </div>

                <!-- Vertical Divider -->
                <div class="divider"></div>

                <!-- Address & Phone -->
                <div class="footer-info left-info">
                    <div class="info-row">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Bajumpandan Dumaguete</span>
                    </div>
                    <div class="info-row">
                        <i class="fas fa-phone-alt"></i>
                        <span>0933-444-5555</span>
                    </div>
                </div>

                <!-- Vertical Divider -->
                <div class="divider"></div>

                <!-- Email & Socials -->
                <div class="footer-info right-info">
                    <div class="info-row">
                        <i class="fas fa-envelope"></i>
                        <span>Fuafe@gmail.com</span>
                    </div>
                    <div class="info-row">
                        <i class="fas fa-globe"></i>
                        <span>Fuafe coffee shop</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="script.js?v=<?php echo filemtime('script.js'); ?>"></script>
</body>
</html>

