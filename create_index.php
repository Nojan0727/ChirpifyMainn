<?php
global $conn;
session_start();
require "database/database.php";

// Handle cookie
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cookie_consent'])) {
    $consent = $_POST['cookie_consent'];
    setcookie("cookie_consent", $consent, time() + (365 * 24 * 60 * 60), "/"); // 1 year expiry
    header("Location: index.php");
    exit;
}

// Check if the user is already logged in
if (isset($_SESSION['user']) && isset($_SESSION['id'])) {
    header("Location: post.php");
    exit();
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username)) {
        $error = "Please enter your username.";
    } elseif (empty($password)) {
        $error = "Please enter your password.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id, username, password, profile_picture, age, bio FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && password_verify($password, $row["password"])) {
                $_SESSION['user'] = $row['username'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['profile_picture'] = $row['profile_picture'] ?? 'assets/uploads/default.jpg';
                $_SESSION['age'] = $row['age'];
                $_SESSION['bio'] = $row['bio'] ?? 'No bio yet.';
                header("Location: post.php");
                exit;
            } else {
                $error = "Incorrect username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>