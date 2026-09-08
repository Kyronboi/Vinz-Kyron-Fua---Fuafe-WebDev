<?php
require_once __DIR__ . '/../database/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get comment ID
$comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
if (!$comment_id) {
    header('Location: comments.php');
    exit;
}

$pdo = getConnection();

// Security check: If not admin, they can only delete their own comment
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';

$stmt = $pdo->prepare("SELECT customer_id FROM comments WHERE id = :id");
$stmt->execute(['id' => $comment_id]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comment) {
    header('Location: comments.php');
    exit;
}

if (!$isAdmin && $comment['customer_id'] != $_SESSION['user_id']) {
    header('Location: comments.php?error=permission');
    exit;
}

// Delete the comment
$stmt = $pdo->prepare("DELETE FROM comments WHERE id = :id");
$stmt->execute(['id' => $comment_id]);

header('Location: comments.php');
exit;