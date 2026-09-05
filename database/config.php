<?php
function getConnection(): PDO
{
    $host = '127.0.0.1';
    $port = '3307';
    $db   = 'cafe_db';
    $user = 'root';
    $pass = 'V1nzN0rsu!';

    try {
        $pdo = new PDO(
            "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
            $user,
            $pass
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}