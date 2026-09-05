<?php include 'auth_header.php'; ?>
<section class="auth-section">
    <div class="auth-card">
        <img src="pictures/B&W logo.svg" alt="Fuafe Logo" class="auth-logo">
        <h2>Welcome Back</h2>
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>
        <form method="POST" action="function.php">
            <input type="hidden" name="action" value="login">
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit" name="login-btn" class="btn-auth">Log In</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</section>
<?php include 'auth_footer.php'; ?>