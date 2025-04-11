<?php
require "create_register.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="header">
    <div class="leftHeader">
        <ul>
            <li><a href="index.php"><img class="chirpifyLogo" src="Image/Chripify.png" alt="Chirpify Logo"><h3>Chirpify</h3></a></li>
        </ul>
    </div>
    <div class="middleHeader">
        <!-- Empty for registration page -->
    </div>
    <div class="rightHeader">
        <!-- Empty for registration page -->
    </div>
</div>
<div class="body">
    <nav class="navBar">
        <ul>
            <li><a href="index.php"><i class="fa-solid fa-house"></i> <span>Home</span></a></li>
        </ul>
    </nav>
    <div class="middleHeader">
        <video class="videoBackground" autoplay muted loop>
            <source src="assets/image/background.mp4" type="video/mp4">
        </video>
        <div class="form">
            <h2>Create an account</h2>
            <?php if (!empty($error)) echo "<p class='error' style='color: red;'>$error</p>"; ?>
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
                <button type="submit" name="submit">Register</button>
            </form>
            <p>Already have an account? <a href="index.php">Login</a></p>
        </div>
    </div>
</div>
</body>
</html>