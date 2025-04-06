<?php
global $conn;
session_start();
require "database/database.php";

if (!isset($_COOKIE["cookie_consent"])) {
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Chirpify - Cookie Consent</title>
        <link rel="stylesheet" href="css/main.css">
        <script defer src="javascript/cookie.js"></script>
        <script defer src="Main.js"></script>
    </head>
    <body>
    <div class="cookieOverlay" id="cookieOverlay"></div>
    <div class="cookieBox" id="cookieBox">
        <p>This website uses cookies to enhance your experience. For more information, please read our policy.</p>
        <button onclick="toggleTerms()">Show Terms & Conditions</button>
        <div id="termsBox" style="display: none;">
            <h1>Algemene voorwaarden</h1>
            <p>Wetgeving in Nederland (en Europa)</p>
            <p>De Nederlandse Auteurswet (1912): Copyright ontstaat automatisch bij het creëren van een werk.</p>
            <p>Rechten van de auteur: Het recht om je werk te reproduceren en te verspreiden.</p>
            <p>Bescherming duurt tot 70 jaar na de dood van de auteur.</p>
            <p>Software Richtlijn (91/250/EEG): Beschermt softwarecode als een "literaire creatie".</p>
            <p>Database Richtlijn (96/9/EG): Beschermt databanken tegen kopiëren van "substantiële delen".</p>
        </div>
        <button onclick="acceptCookies()">Accept</button>
        <button onclick="rejectCookies()">Reject</button>
    </div>
    </body>
    </html>
    <?php
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username)) {
        $error = "Please enter your username";
    } elseif (empty($password)) {
        $error = "Please enter your password";
    } else {
        $stmt = $conn->prepare("SELECT username, password, profile_picture, age, bio FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo "Debug: Fetched profile_picture from database: " . htmlspecialchars($row['profile_picture'] ?? 'NULL') . "<br>";
            if (password_verify($password, $row["password"])) {
                $_SESSION['user'] = $row['username'];
                $_SESSION['profile_picture'] = $row['profile_picture'] ?? 'assets/uploads/default.jpg';
                $_SESSION['age'] = $row['age'];
                $_SESSION['bio'] = $row['bio'] ?? 'No bio yet.';
                echo "Debug: Set session profile_picture: " . htmlspecialchars($_SESSION['profile_picture']) . "<br>";
                header("Location: post.php");
                exit();
            } else {
                $error = "Incorrect username or password";
            }
        } else {
            $error = "Incorrect username or password";
            echo "Debug: No user found with username: " . htmlspecialchars($username) . "<br>";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chirpify - Login</title>
    <link rel="stylesheet" href="css/main.css">
    <script defer src="Main.js"></script>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p class='loginError'>$error</p>"; ?>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" placeholder="Username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <input type="submit" name="submit" value="Log In"><br>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>
</body>
</html>