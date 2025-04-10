<?php
session_start();
require "database/database.php";

if (!isset($_SESSION['user']) || !isset($_SESSION['id'])) {
    if (basename($_SERVER['PHP_SELF']) !== 'index.php') {
        header("Location: index.php");
        exit();
    }
}

$upload_dir = "assets/uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// === create post ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post']) && !empty($_POST['post_text'])) {
    $post_text = trim($_POST['post_text']);
    $image_path = "";

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_name = uniqid('post_', true) . '.' . strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = $target_path;
        } else {
            $error = "Error uploading image.";
        }
    }

    if (!isset($error)) {
        try {
            $sql = "INSERT INTO posts (user_id, post_text, images) VALUES (:user_id, :post_text, :images)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $_SESSION['id'],
                ':post_text' => $post_text,
                ':images' => $image_path
            ]);
            header("Location: post.php");
            exit();
        } catch (PDOException $e) {
            $error = "Error creating post: " . $e->getMessage();
        }
    }
}

// === comment ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_comment'], $_POST['post_id'], $_POST['comment_text'])) {
    $comment_posted_at = date('Y-m-d H:i:s');
    try {
        $sql = "INSERT INTO comments (comment_text, post_id, user_id, comment_posted_at)
                VALUES (:comment_text, :post_id, :user_id, :comment_posted_at)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':comment_text' => $_POST['comment_text'],
            ':post_id' => $_POST['post_id'],
            ':user_id' => $_SESSION['id'],
            ':comment_posted_at' => $comment_posted_at
        ]);
        header("Location: post.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error posting comment: " . $e->getMessage();
    }
}

// === likes ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_post'], $_POST['post_id'])) {
    $post_id = (int)$_POST['post_id'];
    $user_id = $_SESSION['id'];

    $stmt = $conn->prepare("SELECT id FROM posts WHERE id = :post_id");
    $stmt->execute([':post_id' => $post_id]);
    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        $error = "Invalid post ID.";
        header("Location: post.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id");
    $stmt->execute([':user_id' => $user_id, ':post_id' => $post_id]);
    $like = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($like) {
        try {
            $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id");
            $stmt->execute([':user_id' => $user_id, ':post_id' => $post_id]);
        } catch (PDOException $e) {
            $error = "Error unliking post: " . $e->getMessage();
        }
    } else {
        try {
            $sql = "INSERT INTO likes (user_id, post_id, created_date) VALUES (:user_id, :post_id, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':user_id' => $user_id, ':post_id' => $post_id]);
        } catch (PDOException $e) {
            $error = "Error liking post: " . $e->getMessage();
        }
    }
    header("Location: post.php");
    exit();
}

// === retrieve posts ===
$stmt = $conn->prepare("SELECT posts.*, users.username, users.profile_picture 
  FROM posts 
  JOIN users ON posts.user_id = users.id 
  ORDER BY posts.post_created_at DESC");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// === retrieve comments ===
$comments_by_post = [];
if (!empty($posts)) {
    $post_ids = array_column($posts, 'id');
    $in_placeholders = implode(',', array_fill(0, count($post_ids), '?'));

    $stmt = $conn->prepare("SELECT comments.*, users.username 
                            FROM comments 
                            JOIN users ON comments.user_id = users.id 
                            WHERE comments.post_id IN ($in_placeholders)
                            ORDER BY comments.comment_posted_at ASC");
    $stmt->execute($post_ids);
    $all_comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($all_comments as $comment) {
        $comments_by_post[$comment['post_id']][] = $comment;
    }
}

// === USER SEARCH FEATURE ===
$search_results = [];
if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET['search'])) {
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
    $stmt = $conn->prepare("SELECT username, profile_picture FROM users WHERE username LIKE :search ORDER BY username ASC");
    $stmt->execute([':search' => "%$search%"]);
    $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>