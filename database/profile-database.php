<?php

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$upload_dir = "assets/uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

function storePostInSession(string $content, string $image_path): void {
    $_SESSION['posts'][] = [
        'user' => $_SESSION['user'],
        'content' => $content,
        'profile_pic' => $_SESSION['profile_picture'] ?? '',
        'image' => $image_path,
        'likes' => 10,
        'reposts' => 0
    ];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['content'])) {
        $content = trim($_POST['content']);
        $image_path = "";

        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file_name = $upload_dir . $_SESSION['user'] . '_' . time() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $file_name)) {
                $image_path = $file_name;
            } else {
                echo "<p class='error'>Error uploading image.</p>";
            }
        }

        storePostInSession($content, $image_path);
    }

    if (isset($_POST['delete_post'])) {
        $index = (int)$_POST['delete_post'];
        if (isset($_SESSION['posts'][$index]) && $_SESSION['posts'][$index]['user'] === $_SESSION['user']) {
            array_splice($_SESSION['posts'], $index, 1);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
session_start();

$username = $_SESSION['user'] ?? '';
$age = $_SESSION['age'] ?? 'N/A';
$bio = $_SESSION['bio'] ?? 'No bio available';
$profile_picture = $_SESSION['profile_picture'] ?? 'default-profile.png';
