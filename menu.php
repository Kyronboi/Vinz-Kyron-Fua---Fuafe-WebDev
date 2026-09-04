<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Menu - Fualfe</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo filemtime('styles.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <!-- Header (same as homepage, but "Our Menu" is active) -->
    <header class="navbar">
        <div class="logo-icon">
            <img src="pictures/cup.png" alt="Logo Icon">
        </div>
        <nav class="nav-links">
            <a href="test.php#home">Home</a>
            <a href="menu.php" class="active">Our Menu</a>
            <a href="test.php#contact">Contact Us</a>
            <a href="test.php#blog">Blog</a>
        </nav>
        <div class="signin-btn">
            <a href="#"><i class="fas fa-user"></i> Sign In</a>
        </div>
    </header>

    <!-- Full Menu Section -->
    <section class="full-menu-section">
        <div class="container">
            <h1 class="page-title">Fuafe Coffee Menu</h1>
            <p class="page-subtitle">Discover our handcrafted coffee, made with love.</p>
            
            <div class="menu-grid">
                <!-- Product 1: Espresso -->
                <div class="menu-card-lg">
                    <div class="card-img"><img src="images/espresso.jpg" alt="Espresso"></div>
                    <h3>Espresso</h3>
                    <p>Strong, bold, and pure. A classic shot of espresso.</p>
                    <span class="price">$3.50</span>
                    <a href="#" class="find-more">Order Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Product 2: Mocha -->
                <div class="menu-card-lg">
                    <div class="card-img"><img src="images/mocha.jpg" alt="Mocha"></div>
                    <h3>Mocha</h3>
                    <p>Rich chocolate combined with our signature espresso.</p>
                    <span class="price">$4.50</span>
                    <a href="#" class="find-more">Order Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Product 3: Latte -->
                <div class="menu-card-lg">
                    <div class="card-img"><img src="images/latte.jpg" alt="Latte"></div>
                    <h3>Latte</h3>
                    <p>Smooth milk with a delicate layer of foam.</p>
                    <span class="price">$4.00</span>
                    <a href="#" class="find-more">Order Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Product 4: Cappuccino -->
                <div class="menu-card-lg">
                    <div class="card-img"><img src="images/cappuccino.jpg" alt="Cappuccino"></div>
                    <h3>Cappuccino</h3>
                    <p>Perfect balance of espresso, steam, and foam.</p>
                    <span class="price">$4.25</span>
                    <a href="#" class="find-more">Order Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Product 5: Americano -->
                <div class="menu-card-lg">
                    <!-- REPLACE WITH REAL IMAGE: images/americano.jpg -->
                    <div class="card-img"><img src="https://placehold.co/300x200?text=Americano" alt="Americano"></div>
                    <h3>Americano</h3>
                    <p>Espresso diluted with hot water for a lighter taste.</p>
                    <span class="price">$3.75</span>
                    <a href="#" class="find-more">Order Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Product 6: Macchiato -->
                <div class="menu-card-lg">
                    <!-- REPLACE WITH REAL IMAGE: images/macchiato.jpg -->
                    <div class="card-img"><img src="https://placehold.co/300x200?text=Macchiato" alt="Macchiato"></div>
                    <h3>Macchiato</h3>
                    <p>Espresso "stained" with a spoonful of milk foam.</p>
                    <span class="price">$3.95</span>
                    <a href="#" class="find-more">Order Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Product 7: Cold Brew -->
                <div class="menu-card-lg">
                    <!-- REPLACE WITH REAL IMAGE: images/coldbrew.jpg -->
                    <div class="card-img"><img src="https://placehold.co/300x200?text=Cold+Brew" alt="Cold Brew"></div>
                    <h3>Cold Brew</h3>
                    <p>Steeped for 18 hours, served over ice.</p>
                    <span class="price">$4.75</span>
                    <a href="#" class="find-more">Order Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Product 8: Flat White -->
                <div class="menu-card-lg">
                    <!-- REPLACE WITH REAL IMAGE: images/flatwhite.jpg -->
                    <div class="card-img"><img src="https://placehold.co/300x200?text=Flat+White" alt="Flat White"></div>
                    <h3>Flat White</h3>
                    <p>Velvety microfoam with a double shot of espresso.</p>
                    <span class="price">$4.20</span>
                    <a href="#" class="find-more">Order Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Product 9: Caramel Latte -->
                <div class="menu-card-lg">
                    <!-- REPLACE WITH REAL IMAGE: images/caramellatte.jpg -->
                    <div class="card-img"><img src="https://placehold.co/300x200?text=Caramel+Latte" alt="Caramel Latte"></div>
                    <h3>Caramel Latte</h3>
                    <p>Our classic latte with a swirl of caramel syrup.</p>
                    <span class="price">$4.80</span>
                    <a href="#" class="find-more">Order Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Product 10: Iced Coffee -->
                <div class="menu-card-lg">
                    <!-- REPLACE WITH REAL IMAGE: images/icedcoffee.jpg -->
                    <div class="card-img"><img src="https://placehold.co/300x200?text=Iced+Coffee" alt="Iced Coffee"></div>
                    <h3>Iced Coffee</h3>
                    <p>Chilled coffee with milk and sugar on request.</p>
                    <span class="price">$3.50</span>
                    <a href="#" class="find-more">Order Now <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

</body>
</html>