<?php
global $conn;
session_start();
require "database/database.php";

if (!isset($_SESSION['user']) || !isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// user details
$stmt = $conn->prepare("SELECT id, username, bio, profile_picture, age FROM users WHERE username = :username");
$stmt->execute([':username' => $_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $_SESSION['id'] = $user['id'];
    $_SESSION['user'] = $user['username'];
    $_SESSION['bio'] = $user['bio'] ?? 'No bio yet.';
    $_SESSION['profile_picture'] = $user['profile_picture'] ?? 'assets/uploads/default.jpg';
    $_SESSION['age'] = $user['age'];
}

// posts from the database for the current user
$stmt = $conn->prepare("SELECT posts.*, users.username, users.profile_picture 
                        FROM posts 
                        JOIN users ON posts.user_id = users.id 
                        WHERE posts.user_id = :user_id 
                        ORDER BY posts.post_created_at DESC");
$stmt->execute([':user_id' => $_SESSION['id']]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletePost'])) {
    $post_id = (int)$_POST['deletePost'];

    // check the post user wanna delete belong to the user or other user
    $stmt = $conn->prepare("SELECT user_id FROM posts WHERE id = :post_id");
    $stmt->execute([':post_id' => $post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post && $post['user_id'] == $_SESSION['id']) {
        try {

            $stmt = $conn->prepare("DELETE FROM posts WHERE id = :post_id");
            $stmt->execute([':post_id' => $post_id]);
            header("Location: profile.php");
            exit();
        } catch (PDOException $e) {
            $error = "Error deleting post: " . $e->getMessage();
        }
    }
}
?>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>