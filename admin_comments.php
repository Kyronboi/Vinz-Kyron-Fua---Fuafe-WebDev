<?php
require_once 'database/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Admin access control
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

$pdo = getConnection();
$message = '';

// Handle admin actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $comment_id = (int)($_POST['comment_id'] ?? 0);
    if (!$comment_id) exit;

    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = :id");
        $stmt->execute(['id' => $comment_id]);
        $message = "Comment deleted.";
    } elseif ($action === 'hide') {
        $stmt = $pdo->prepare("UPDATE comments SET is_hidden = 1 WHERE id = :id");
        $stmt->execute(['id' => $comment_id]);
        $message = "Comment hidden.";
    } elseif ($action === 'show') {
        $stmt = $pdo->prepare("UPDATE comments SET is_hidden = 0 WHERE id = :id");
        $stmt->execute(['id' => $comment_id]);
        $message = "Comment unhidden.";
    }
}

// Fetch all comments (including hidden)
$comments = $pdo->query("SELECT c.*, cu.username FROM comments c JOIN customer cu ON c.customer_id = cu.id ORDER BY c.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'header.php'; ?>

<section class="admin-section">
    <div class="container">
        <h1 class="page-title">Manage Comments</h1>
        <p class="admin-message"><?= htmlspecialchars($message) ?></p>

        <div class="admin-panel">
            <h2>All Comments</h2>
            <div class="table-scroll-wrapper">
                <table class="admin-table">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Comment</th>
                        <th>Rating</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($comments as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= htmlspecialchars($c['username']) ?></td>
                        <td style="max-width: 300px;"><?= htmlspecialchars($c['comment']) ?></td>
                        <td><?= $c['rating'] ?>★</td>
                        <td><?= date('M d, Y', strtotime($c['created_at'])) ?></td>
                        <td>
                            <?php if ($c['is_hidden']): ?>
                                <span style="color:red; font-weight:bold;">Hidden</span>
                            <?php else: ?>
                                <span style="color:green;">Visible</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Delete Form -->
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this comment?')">
                                <input type="hidden" name="comment_id" value="<?= $c['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                            
                            <!-- Hide/Show Form -->
                            <?php if ($c['is_hidden']): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="comment_id" value="<?= $c['id'] ?>">
                                    <input type="hidden" name="action" value="show">
                                    <button type="submit" class="btn-small">Show</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="comment_id" value="<?= $c['id'] ?>">
                                    <input type="hidden" name="action" value="hide">
                                    <button type="submit" class="btn-small">Hide</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <p><a href="admin.php" style="color:orange;">← Back to Admin Dashboard</a></p>
    </div>
</section>

<?php include 'auth_footer.php'; ?>