<?php
require "create_register.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<video class="videoBackground" autoplay muted loop>
    <source src="assets/image/background.mp4" type="video/mp4">
</video>
<div class="container">
    <h2>Create an account</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profilePic" id="profile_picture" accept="image/*" required>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <label for="age">Age:</label>
        <input type="number" name="age" id="age" required min="18">
        <label for="bio">Bio:</label>
        <input type="text" name="bio" id="bio" required>
        <input type="submit" name="submit" value="Register">
    </form>
    <p>Already have an account? <a href="index.php">Login</a></p>
</div>
</body>
</html>