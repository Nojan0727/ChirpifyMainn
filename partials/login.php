<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'database/database.php';

// Check form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validate inputs
    if (empty($username)) {
        echo "Please enter your username";
    } elseif (empty($password)) {
        echo "Please enter your password";
    } else {
        // Query the database for the user
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check user exists and the password is correct
        if ($user && password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['user'] = $user['username'];
            $_SESSION['profile_pic'] = $user['profile_pic'];
            $_SESSION['age'] = $user['age'];
            $_SESSION['bio'] = $user['bio'];

            // Redirect to the post.php
            header("Location: post.php");
            exit; // Always call exit after header redirect to prevent further execution
        } else {
            // Display an error message if credentials are incorrect
            echo "<p class='loginError'>Incorrect username or password</p>";
        }
    }
}
?>