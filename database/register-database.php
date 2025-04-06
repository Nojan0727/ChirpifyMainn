<?php
session_start();


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

        $file_name = $upload_dir . basename($profilePic["name"]);
        if (getimagesize($profilePic["tmp_name"]) === false) {
            $error = "File is not an image.";
        } elseif ($profilePic["size"] > 5000000) {
            $error = "File is too big (max 5MB).";
        } elseif (move_uploaded_file($profilePic["tmp_name"], $file_name)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (user, password, age, bio, profile_pic) VALUES (:username, :password, :age, :bio, :profile_pic)");
            $stmt->execute([
                ':username' => $username,
                ':password' => $hash,
                ':age' => $age,
                ':bio' => $bio,
                ':profile_pic' => $file_name
            ]);

            header("Location: index.php");
            exit();
        } else {
            $error = "Error uploading profile picture.";
        }
    }
}
?>