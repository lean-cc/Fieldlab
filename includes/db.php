<?php

session_start();

$host = '127.0.0.1';
$db = 'fieldlab';
$user = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$pdo = new PDO($dsn, $user);

if (isset($_SESSION['loggedInUser'])) {
    $loggedInUserId = $_SESSION['loggedInUser'];
}

$usersStmt = $pdo->prepare("SELECT * FROM users WHERE userId = :id;");
$usersStmt->bindParam(':id', $loggedInUserId, PDO::PARAM_INT);

try {
    $usersStmt->execute();
    $user = $usersStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error: " . $e->getMessage());
    die();
}