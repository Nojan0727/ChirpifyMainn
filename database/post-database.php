<?php
session_start();


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$upload_dir = "assets/uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
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

    $_SESSION['posts'][] = [
        'user' => $_SESSION['user'],
        'content' => $content,
        'profile_pic' => $_SESSION['profile_pic'],
        'image' => $image_path,
        'likes' => 0,
        'reposts' => 0
    ];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>