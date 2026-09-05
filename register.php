<?php include 'auth_header.php'; ?>
<section class="auth-section">
    <div class="auth-card">
        <img src="pictures/B&W logo.svg" alt="Fuafe Logo" class="auth-logo">
        <h2>Create Account</h2>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['message']) ?></p>
        <?php endif; ?>
        <form method="POST" action="function.php">
            <input type="hidden" name="action" value="register">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Age</label>
            <input type="number" name="age" required>
            <label>Delivery Location</label>
            <input type="text" name="location" placeholder="e.g. Dumaguete City" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit" name="add-customer" class="btn-auth">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Log In</a></p>
    </div>
</section>
<?php include 'auth_footer.php'; ?>