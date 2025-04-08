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

    echo "Debug: Form submitted.<br>";
    echo "Debug: Username: " . htmlspecialchars($username) . "<br>";
    echo "Debug: Profile picture uploaded: " . ($profilePic ? "Yes, name: " . $profilePic['name'] : "No") . "<br>";

    if (empty($username) || empty($password) || empty($profilePic['name']) || empty($bio) || empty($age)) {
        $error = "Please fill in all fields.";
        echo "Debug: Validation failed - missing fields.<br>";
    } else {
        $upload_dir = "assets/uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
            echo "Debug: Created directory: $upload_dir<br>";
        } else {
            echo "Debug: Directory already exists: $upload_dir<br>";
        }

        // Validate the file extension
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($profilePic["name"], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions)) {
            $error = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            echo "Debug: Validation failed - invalid file type.<br>";
        } elseif (getimagesize($profilePic["tmp_name"]) === false) {
            $error = "File is not an image.";
            echo "Debug: Validation failed - not an image.<br>";
        } elseif ($profilePic["size"] > 5000000) {
            $error = "File is too big (max 5MB).";
            echo "Debug: Validation failed - file too big.<br>";
        } else {
            // Generate a unique filename to avoid issues
            $unique_name = uniqid('profile_', true) . '.' . $file_extension;
            $file_name = $upload_dir . $unique_name;
            echo "Debug: Target file path: $file_name<br>";

            if (move_uploaded_file($profilePic["tmp_name"], $file_name)) {
                echo "Debug: File uploaded successfully to: $file_name<br>";
                $hash = password_hash($password, PASSWORD_DEFAULT);
                try {
                    $stmt = $conn->prepare("INSERT INTO users (username, password, age, bio, profile_picture) VALUES (:username, :password, :age, :bio, :profile_picture)");
                    $stmt->execute([
                        ':username' => $username,
                        ':password' => $hash,
                        ':age' => $age,
                        ':bio' => $bio,
                        ':profile_picture' => $file_name
                    ]);
                    echo "Debug: Database insert successful.<br>";
                    header("Location: index.php");
                    exit();
                } catch (PDOException $e) {
                    $error = "Database error: " . $e->getMessage();
                    echo "Debug: Database insert failed: " . $e->getMessage() . "<br>";
                }
            } else {
                $error = "Error uploading profile picture.";
                echo "Debug: Failed to move uploaded file. Check permissions on $upload_dir.<br>";
            }
        }
    }
}
