<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("database/database.php");

if (!isset($_SESSION['user']) && basename($_SERVER['PHP_SELF']) !== 'index.php') {
    header("Location: index.php");
    exit();
}

$recommended_posts = [];
if (!empty($_SESSION['posts'])) {
    foreach ($_SESSION['posts'] as $post) {
        if ($post['user'] !== $_SESSION['user']) {
            $recommended_posts[] = $post;
        }
    }
}
?>
