<?php
session_start();
require 'database/database.php';

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Fetch current user info
$stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute([':username' => $_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If not found or not admin, block access
if (!$user || $user['is_admin'] != 1) {
    echo "⛔ Access denied. Admins only.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #1e1e1e;
            color: #fff;
            padding: 2rem;
        }

        .admin-panel {
            max-width: 800px;
            margin: auto;
            background: #2c2c2c;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 255, 200, 0.1);
        }

        .admin-panel h1 {
            margin-bottom: 1.5rem;
            color: #63BDB5;
        }

        .admin-actions a {
            display: inline-block;
            background: #63BDB5;
            color: #1e1e1e;
            padding: 0.75rem 1.2rem;
            margin: 0.5rem;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .admin-actions a:hover {
            background: #4fa49a;
        }
    </style>
</head>
<body>

<div class="admin-panel">
    <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['user']); ?> 👑</h1>
    <p>This is your admin dashboard.</p>

    <div class="admin-actions">
        <a href="all_users.php">View All Users</a>
        <a href="all_posts.php">Manage Posts</a>
        <a href="logout.php">Log Out</a>
    </div>
</div>

</body>
</html>