<?php
global $conn;
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
    echo "⛔︎ Access denied. Go away!";
    exit;
}
if (isset($_GET['delete_post'])) {
    $post_id = $_GET['delete_post'];
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->execute([':id' => $post_id]);
    echo "<p>Post deleted successfully!</p>";
}
if (isset($_GET['delete_user_posts'])) {
    $user_id = $_GET['delete_user_posts'];
    $stmt = $conn->prepare("DELETE FROM posts WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    echo "<p>All posts by this user deleted!</p>";
}
if (isset($_GET['delete_all_posts'])) {
    $stmt = $conn->prepare("DELETE FROM posts");
    $stmt->execute();
    echo "<p>All posts deleted!</p>";
}
$stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY post_created_at DESC");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="indx">
<div class="videoContaine">
    <video class="videoBackground" autoplay muted loop playsinline>
        <source src="assets/image/background.mp4" type="video/mp4">
    </video>
</div>
<div class="chirpifylogForm">
    <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['user']); ?></h1>
    <p>Check your panel</p>

    <div class="admin-actions">
        <a href="?delete_all_posts=1" onclick="return confirm('Are we gonna delete all the posts and make the users cry like a baby?');">Delete All Posts</a>
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-item">
                    <div class="post-username"><?php echo htmlspecialchars($post['username']); ?></div>
                    <div class="post-content"><?php echo htmlspecialchars($post['post_text']); ?></div>
                    <div class="post-date"><?php echo $post['post_created_at']; ?></div>
                    <div class="post-actions">
                        <a href="?delete_post=<?php echo $post['id']; ?>" onclick="return confirm('Delete this post?');">Delete Post</a>
                        <a href="?delete_user_posts=<?php echo $post['user_id']; ?>" onclick="return confirm('Delete all posts by this user?');">Delete All by User</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-posts">No posts found.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>