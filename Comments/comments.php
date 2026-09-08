<?php
require_once __DIR__ . '/../database/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$pdo = getConnection();
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';

// Fetch comments - If not admin, hide the hidden ones
if ($isAdmin) {
    $stmt = $pdo->query("SELECT c.*, cu.username FROM comments c JOIN customer cu ON c.customer_id = cu.id ORDER BY c.created_at DESC");
} else {
    $stmt = $pdo->query("SELECT c.*, cu.username FROM comments c JOIN customer cu ON c.customer_id = cu.id WHERE c.is_hidden = 0 ORDER BY c.created_at DESC");
}
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../header.php'; ?>

<section class="comments-page-section">
    <div class="container">
        <h1 class="page-title">Customer Comments</h1>
        <div style="text-align: center; margin-bottom: 30px;">
            <a href="add_comment.php" class="btn-auth">Add a Comment</a>
        </div>

        <?php if (empty($comments)): ?>
            <p style="color: #fff; text-align: center;">No comments yet. Be the first!</p>
        <?php else: ?>
            <div class="comments-grid">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-box">
                        <div class="testimonial-avatar"><i class="fas fa-user-circle"></i></div>
                        <h4><?= htmlspecialchars($comment['username']) ?></h4>
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $comment['rating'] ? 'active' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p><?= htmlspecialchars($comment['comment']) ?></p>
                        <small><?= date('M d, Y', strtotime($comment['created_at'])) ?></small>

                        <?php if ($isAdmin && $comment['is_hidden']): ?>
                            <br><span style="color: red; font-weight: bold;">(Hidden)</span>
                        <?php endif; ?>

                        <!-- Delete button if user owns the comment, or if admin -->
                        <?php if (isset($_SESSION['user_id']) && ($comment['customer_id'] == $_SESSION['user_id'] || $isAdmin)): ?>
                            <form method="POST" action="delete_comment.php" onsubmit="return confirm('Delete your comment?')" style="margin-top:10px;">
                                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                <button type="submit" class="btn-danger" style="width:auto; padding:5px 15px;">Delete</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../auth_footer.php'; ?>