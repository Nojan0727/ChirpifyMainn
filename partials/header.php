<?php
include("database/database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chirpify</title>
    <link rel="stylesheet" href="../../css/main.css">
</head>
<body>

<header>
    <div class="logo">
        <img class="chirpifyLogo" src="assets/image/Chirpify.png" alt="Chirpify Logo" <h1 class="logo">Chirpify</h1>
    </div>
    <nav>
        <ul>
            <li><a href="../../register.php">Register</a></li>
            <?php if (isset($_SESSION['user'])): ?>
            <?php endif; ?>
        </ul>
    </nav>
</header>

</body>
</html>