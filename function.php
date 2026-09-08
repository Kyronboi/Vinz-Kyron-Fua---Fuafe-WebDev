<?php
require_once 'database/config.php';
require_once 'validation.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$action = $_POST['action'] ?? null;

// === REGISTRATION ===
if ($action === 'register') {
    if (!isset($_POST['add-customer'])) {
        header('Location: register.php');
        exit;
    }

    $result = validateCustomerInput($_POST);
    $errors = $result['errors'];

    if (!empty($errors)) {
        $message = implode(' ', $errors);
        header('Location: register.php?status=error&message=' . urlencode($message));
        exit;
    }

    $password = $_POST['password'] ?? '';
    if (strlen($password) < 6) {
        header('Location: register.php?status=error&message=' . urlencode("Password must be at least 6 characters."));
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $pdo = getConnection();
        
        $stmt = $pdo->prepare("SELECT id FROM customer WHERE email = :email");
        $stmt->bindValue(':email', $result['data']['email']);
        $stmt->execute();
        if ($stmt->fetch()) {
            header('Location: register.php?status=error&message=' . urlencode("Email already registered."));
            exit;
        }

        $sql = "INSERT INTO customer (username, email, age, location, password, role)
                VALUES (:username, :email, :age, :location, :password, 'customer')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $result['data']['username']);
        $stmt->bindValue(':email', $result['data']['email']);
        $stmt->bindValue(':age', $result['data']['age'], PDO::PARAM_INT);
        $stmt->bindValue(':location', $result['data']['location']);
        $stmt->bindValue(':password', $hashedPassword);
        $stmt->execute();

        $newId = $pdo->lastInsertId();
        header('Location: success.php?id=' . $newId);
        exit;
    } catch (PDOException $e) {
        header('Location: register.php?status=error&message=' . urlencode($e->getMessage()));
        exit;
    }
}

// === LOGIN ===
if ($action === 'login') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header('Location: login.php?error=' . urlencode("Email and password are required."));
        exit;
    }

    try {
        $pdo = getConnection();
        $sql = "SELECT id, username, email, age, location, password, role FROM customer WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            header('Location: login.php?error=' . urlencode("Invalid email or password."));
            exit;
        }

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['location'] = $user['location'];
        $_SESSION['role'] = $user['role'];

        // Redirect admin to admin dashboard
        if ($user['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } catch (PDOException $e) {
        header('Location: login.php?error=' . urlencode($e->getMessage()));
        exit;
    }
}

// No action, redirect to home
header('Location: index.php');
exit;