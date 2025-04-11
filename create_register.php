<?php
global $conn;
session_start();
require "database/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $age = filter_input(INPUT_POST, "age", FILTER_SANITIZE_NUMBER_INT);
    $bio = filter_input(INPUT_POST, "bio", FILTER_SANITIZE_SPECIAL_CHARS);
    $profilePic = $_FILES["profilePic"] ?? null;

    if (empty($username) || empty($password) || empty($profilePic['name']) || empty($bio) || empty($age)) {
        $error = "Please fill in all fields.";
    } else {
        $upload_dir = "assets/uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Validate the file extension
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($profilePic["name"], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions)) {
            $error = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        } elseif (getimagesize($profilePic["tmp_name"]) === false) {
            $error = "File is not an image.";
        } elseif ($profilePic["size"] > 5000000) {
            $error = "File is too big (max 5MB).";
        } else {
            // Generate a unique filename to avoid issues
            $unique_name = uniqid('profile_', true) . '.' . $file_extension;
            $file_name = $upload_dir . $unique_name;

            if (move_uploaded_file($profilePic["tmp_name"], $file_name)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                try {
                    // Check if username already exists
                    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
                    $stmt->execute([':username' => $username]);
                    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                        $error = "Username already exists.";
                    } else {
                        $stmt = $conn->prepare("INSERT INTO users (username, password, age, bio, profile_picture) VALUES (:username, :password, :age, :bio, :profile_picture)");
                        $stmt->execute([
                            ':username' => $username,
                            ':password' => $hash,
                            ':age' => $age,
                            ':bio' => $bio,
                            ':profile_picture' => $file_name
                        ]);

                        // Auto login the user
                        $stmt = $conn->prepare("SELECT id, username, profile_picture, age, bio FROM users WHERE username = :username");
                        $stmt->execute([':username' => $username]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($user) {
                            $_SESSION['user'] = $user['username'];
                            $_SESSION['id'] = $user['id'];
                            $_SESSION['profile_picture'] = $user['profile_picture'] ?? 'assets/uploads/default.jpg';
                            $_SESSION['age'] = $user['age'];
                            $_SESSION['bio'] = $user['bio'] ?? 'No bio yet.';
                            header("Location: post.php");
                            exit();
                        } else {
                            $error = "Error retrieving user after registration.";
                        }
                    }
                } catch (PDOException $e) {
                    $error = "Database error: " . $e->getMessage();
                }
            } else {
                $error = "Error uploading profile picture.";
            }
        }
    }
}
?>