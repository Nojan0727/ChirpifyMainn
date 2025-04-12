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
<div class="chirpify-body register-container">
    <div class="videoContaine">
        <video class="videoBackground" autoplay muted loop playsinline>
            <source src="assets/image/background.mp4" type="video/mp4">
        </video>
        <h1 class="cont">Chirpify, Where Creativity Meets the Web
            <link href="https://fonts.googleapis.com/css2?family=Neue&family=Orbitron:wght@700&family=Rubik+Mono+One&display=swap" rel="stylesheet">
        </h1>
        <div class="chirpifyregForm">

            <h2 class="log">Create an Account</h2>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <form class="registrationForm" action="" method="POST" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>

                <label for="age">Age:</label>
                <input type="number" name="age" id="age" required min="18">

                <label for="bio">Bio:</label>
                <input type="text" name="bio" id="bio" required>

                <input  type="submit" name="submit" value="Register">
            </form>
            <p class="login-link">Already have an account? <a href="index.php">Login</a></p>
        </div>
    </div>
</div>
</body>
</html>