<?php
global $conn;
session_start();
require "database/database.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT username, bio, profile_picture, age FROM users WHERE username = :username");
$stmt->execute([':username' => $_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $_SESSION['user'] = $user['username'];
    $_SESSION['bio'] = $user['bio'] ?? 'No bio yet.';
    $_SESSION['profile_picture'] = $user['profile_picture'] ?? 'assets/uploads/default.jpg';
    $_SESSION['age'] = $user['age'];
}

// Handle post deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletePost'])) {
    $index = (int)$_POST['deletePost'];
    if (isset($_SESSION['posts'][$index]) && $_SESSION['posts'][$index]['user'] === $_SESSION['user']) {
        array_splice($_SESSION['posts'], $index, 1);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>