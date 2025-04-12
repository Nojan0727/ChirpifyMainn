<?php
global $conn;
include 'database/database.php';
if (isset($_GET['search'])) {
    $search = htmlspecialchars($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE :username LIMIT 1");
    $stmt->execute([':username' => '%' . $search . '%']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $user = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
    <script defer src="javascript/main.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="postBody">
    <nav class="navBar">
        <div class="leftHeader">
            <h3 class="postH">Chirpify <a href="post.php"></a></h3>
        </div>
        <ul>
            <li><a href="post.php"><i class="fa-solid fa-house"></i><span>Home</span></a></li>
            <li><a href="construction.php"><i class="fas fa-search"></i><span>Search</span></a></li>
            <li><a href="construction.php"><i class="fa-regular fa-compass"></i><span>Explore</span></a></li>
            <li><a href="message.php"><i class="fa-regular fa-bell"></i><span>Messages</span></a></li>
            <li><a href="construction.php"><i class="fa-regular fa-envelope"></i><span>Notification</span></a></li>
            <li><a href="construction.php"><i class="fa-regular fa-square-plus"></i><span>Create</span></a></li>
            <li><a href="profile.php"><i class="fa-regular fa-user"></i><span>Profile</span></a></li>
            <li class="down"><a href="construction.php"><i class="fas fa-crown"></i><span>Premium</span></a></li>
            <li class="down"><a href="construction.php"><i class="fa fa-bars"></i><span>More</span></a></li>
            <li class="down"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i><span>Log out</span></a></li>
            <li class="underPro"></li>
        </ul>
    </nav>
    <main class="middleHeader">
        <?php if ($user): ?>
            <div class="profile">
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                <p>Bio: <?php echo htmlspecialchars($user['bio']); ?></p>
                <p>Age: <?php echo htmlspecialchars($user['age']); ?></p>
            </div>
        <?php else: ?>
            <p>No user found with that username.</p>
        <?php endif; ?>
    </main>
</div>
</body>
</html>