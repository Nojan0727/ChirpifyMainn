<?php
require "create_index.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify - Login</title>
    <link rel="stylesheet" href="css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Neue&family=Orbitron:wght@700&family=Rubik+Mono+One&display=swap" rel="stylesheet">
    <script src="javascript/cookie.js"></script>
</head>
<body>
<?php if (!isset($_COOKIE["cookie_consent"])): ?>
    <div class="cookieOverlay" id="cookieOverlay">
    <div class="cookieBox" id="cookieBox">
        <p>This website uses cookies to enhance your experience. For more information, please read our policy.</p>
        <button onclick="toggleTerms()">Show Terms & Conditions</button>
        <div id="termsBox">
            <h1>Algemene voorwaarden</h1>
            <p>Wetgeving in Nederland (en Europa)</p>
            <p>De Nederlandse Auteurswet (1912): Copyright ontstaat automatisch bij het creëren van een werk.</p>
            <p>Rechten van de auteur: Het recht om je werk te reproduceren en te verspreiden.</p>
            <p>Bescherming duurt tot 70 jaar na de dood van de auteur.</p>
            <p>Software Richtlijn (91/250/EEG): Beschermt softwarecode als een "literaire creatie".</p>
            <p>Database Richtlijn (96/9/EG): Beschermt databanken tegen kopiëren van "substantiële delen".</p>
            <p><a href="policy.php">Read more</a></p>
        </div>
        <form method="POST" action="">
            <input type="hidden" name="cookie_consent" value="accepted">
            <button type="submit">Accept</button>
        </form>
        <form method="POST" action="">
            <input type="hidden" name="cookie_consent" value="rejected">
            <button type="submit">Reject</button>
        </form>
    </div>
    </div>
</body>
<?php else: ?>
    <body class="indx">
    <div class="chirpify-body"></div>
    <div class="videoContaine">
        <video class="videoBackground" autoplay muted loop playsinline>
            <source src="assets/image/background.mp4" type="video/mp4">
        </video>
        <h1 class="cont">Chirpify, Where Creativity Meets the Web
            <link href="https://fonts.googleapis.com/css2?family=Neue&family=Orbitron:wght@700&family=Rubik+Mono+One&display=swap" rel="stylesheet">
        </h1>
        <div class="chirpifylogForm">
            <h2 class="log">Login</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form class="login-form" action="" method="POST">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Username" required>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <button type="submit" name="submit" class="chirpify-post-button">Log In</button>
            </form>
            <p class="register-link">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
    </body>
<?php endif; ?>
</html>