<?php
session_start();
require 'database/database.php';

// if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute([':username' => $_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// for not finding the admin account
if (!$user || $user['is_admin'] != 1) {
    echo "⛔︎ Access denied. Admins only.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<Body class="adminBody">
<div class="admin-panel">
    <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['user']); ?></h1>
    <p>This is your admin dashboard.</p>

    <div class="admin-actions">
        <a href="all_users.php">View All Users</a>
        <a href="all_posts.php">Manage Posts</a>
        <a href="logout.php">Log Out</a>
    </div>
</div>

</Body>
</html>