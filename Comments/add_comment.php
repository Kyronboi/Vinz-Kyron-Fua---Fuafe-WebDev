<?php
session_start();
require_once __DIR__ . '/../database/config.php';

// Security: Ensure user is logged in to comment
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
        $pdo = getConnection();
        $stmt = $pdo->prepare("INSERT INTO comments (customer_id, rating, comment) VALUES (:cid, :rating, :comment)");
        $stmt->execute([
            'cid' => $_SESSION['user_id'],
            'rating' => $rating,
            'comment' => $comment
        ]);
        header('Location: comments.php');
        exit;
    } else {
        $message = "Please select a star rating (1-5) and write a comment.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Comment - Fuafe</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <?php include __DIR__ . '/../header.php'; ?>

    <section class="auth-section">
        <div class="auth-card">
            <h2>Leave a Review</h2>
            
            <?php if ($message): ?>
                <p style="color: red;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <form method="POST" action="add_comment.php">
                <label>Your Rating (1-5 Stars)</label>
                
                <!-- Using radio buttons for 100% reliable submission -->
                <div class="star-rating-input">
                  <input type="radio" id="star5" name="rating" value="5">
                  <label for="star5"><i class="fas fa-star"></i></label>
                  
                  <input type="radio" id="star4" name="rating" value="4">
                  <label for="star4"><i class="fas fa-star"></i></label>
                  
                  <input type="radio" id="star3" name="rating" value="3">
                  <label for="star3"><i class="fas fa-star"></i></label>
                  
                  <input type="radio" id="star2" name="rating" value="2">
                  <label for="star2"><i class="fas fa-star"></i></label>
                  
                  <input type="radio" id="star1" name="rating" value="1">
                  <label for="star1"><i class="fas fa-star"></i></label>
              </div>
                <br>

                <label>Your Comment</label>
                <textarea name="comment" rows="5" required placeholder="Tell us about your experience..."></textarea>

                <button type="submit" class="btn-auth">Submit Comment</button>
            </form>
        </div>
    </section>

    <script>
        const stars = document.querySelectorAll('#starWrapper .star');
        const ratingInput = document.getElementById('ratingInput');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                ratingInput.value = value;
                // Highlight all stars up to the clicked one
                stars.forEach(s => {
                    if (s.getAttribute('data-value') <= value) {
                        s.style.color = '#f8a84a';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });

            star.addEventListener('mouseenter', function() {
                const value = this.getAttribute('data-value');
                stars.forEach(s => {
                    if (s.getAttribute('data-value') <= value) {
                        s.style.color = '#f8a84a';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });
        });

        document.getElementById('starWrapper').addEventListener('mouseleave', function() {
            const selected = ratingInput.value;
            stars.forEach(s => {
                if (s.getAttribute('data-value') <= selected) {
                    s.style.color = '#f8a84a';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    </script>

    <?php include '../auth_footer.php'; ?>
</body>
</html>