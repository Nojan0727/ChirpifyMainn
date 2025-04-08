<?php
global $conn;
session_start();
require "database/database.php";
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$upload_dir = "assets/uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
if (!isset($_SESSION['posts'])) {
    $_SESSION['posts'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content'])) {
    $content = trim($_POST['content']);
    $image_path = "";

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_name = $upload_dir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_name)) {
            $image_path = $file_name;
        }
    }
    $post_id = time() . "_" . count($_SESSION['posts']);
    $_SESSION['posts'][] = [
        'post_id' => $post_id,
        'user' => $_SESSION['user'],
        'content' => $content,
        'profile_picture' => $_SESSION['profile_picture'],
        'image' => $image_path,
        'likes' => 0,
        'reposts' => 0
    ];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET['search'])) {
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
    $stmt = $conn->prepare("SELECT username, profile_picture FROM users WHERE username LIKE :search ORDER BY username DESC");
    $stmt->execute([':search' => "%$search%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($results)) {
        echo "<p class='searchQuery'>No user found with the name <strong>" . htmlspecialchars($search) . "</strong></p>";
    } else {
        foreach ($results as $result) {
            echo "<div class='searchResult'>";
            echo "<img class='postImg' src='" . htmlspecialchars($result['profile_picture']) . "' alt='Profile Picture'>";
            echo "<strong>" . htmlspecialchars($result['username']) . "</strong>";
            echo "<span>@" . htmlspecialchars($result['username']) . "</span>";
            echo "</div>";
        }
    }
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_comment']) && isset($_POST['post_id']) && $_POST['post_id'] === $post['post_id']) {
    $comment_posted_at = date('Y-m-d H:i:s');
    $sql = "INSERT INTO comments (comment_text, post_id, user_id, comment_posted_at)
                                VALUES (:comment_text, :post_id, :user_id, :comment_posted_at)";
    $binding = $conn->prepare($sql);
    $binding->bindParam(':comment_text', $_POST['comment_text']);
    $binding->bindParam(':post_id', $_POST['post_id']);
    $binding->bindParam(':user_id', $_SESSION['id']);
    $binding->bindParam(':comment_posted_at', $comment_posted_at);
    $binding->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$sql = "SELECT comments.*, users.id AS user_id, users.profile_picture AS profile_picture " .
    "FROM comments " .
    "JOIN users ON comments.user_id = users.id " .
    "WHERE comments.post_id = :post_id " .
    "ORDER BY comment_posted_at ASC";

$binding = $conn->prepare($sql);
$binding->bindParam(':post_id', $post['post_id']);
$binding->execute();
$comments = $binding->fetchAll(PDO::FETCH_ASSOC);

foreach ($comments as $comment) {
    echo "<div class='comment'>";
    echo '<img class="commentProfile" src="' . htmlspecialchars($comment["profile_picture"]) . '" alt="">';
    echo '<strong>' . htmlspecialchars($comment["user_id"]) . '</strong>';
    echo "<span>@" . htmlspecialchars($comment['user_id']) . "</span>";
    if ($post['user'] === $comment['user_id']) { // Changed to compare usernames
        echo '<div class="admin">Creator</div>';
    }
    echo "<p>" . htmlspecialchars($comment['comment_text']) . "</p>";
    echo "</div>";
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_comment']) && isset($_POST['post_id']) && $_POST['post_id'] === $post['post_id']) {
    $comment_posted_at = date('Y-m-d H:i:s');
    $sql = "INSERT INTO comments (comment_text, post_id, user_id, comment_posted_at)
                                VALUES (:comment_text, :post_id, :user_id, :comment_posted_at)";
    $binding = $conn->prepare($sql);
    $binding->bindParam(':comment_text', $_POST['comment_text']);
    $binding->bindParam(':post_id', $_POST['post_id']);
    $binding->bindParam(':user_id', $_SESSION['id']);
    $binding->bindParam(':comment_posted_at', $comment_posted_at);
    $binding->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$sql = "SELECT comments.*, users.id AS user_id, users.profile_picture AS profile_picture " .
    "FROM comments " .
    "JOIN users ON comments.user_id = users.id " .
    "WHERE comments.post_id = :post_id " .
    "ORDER BY comment_posted_at ASC";

$binding = $conn->prepare($sql);
$binding->bindParam(':post_id', $post['post_id']);
$binding->execute();
$comments = $binding->fetchAll(PDO::FETCH_ASSOC);

foreach ($comments as $comment) {
    echo "<div class='comment'>";
    echo '<img class="commentProfile" src="' . htmlspecialchars($comment["profile_picture"]) . '" alt="">';
    echo '<strong>' . htmlspecialchars($comment["user_id"]) . '</strong>';
    echo "<span>@" . htmlspecialchars($comment['user_id']) . "</span>";
    if ($post['user'] === $comment['user_id']) { // Changed to compare usernames
        echo '<div class="admin">Creator</div>';
    }
    echo "<p>" . htmlspecialchars($comment['comment_text']) . "</p>";
    echo "</div>";
}
?>
