<?php
session_start();
require "database/database.php";

if (!isset($_SESSION['user']) || !isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Dummy users
$following = [
    'john_doe' => ['name' => 'John Doe', 'image' => 'assets/image/profileimg.png'],
    'jane_smith' => ['name' => 'Jane Smith', 'image' => 'assets/image/profileimg.png'],
    'michael_tree' => ['name' => 'Michael Tree', 'image' => 'assets/image/profileimg.png'],
    'jenny_street' => ['name' => 'Jenny Street', 'image' => 'assets/image/profileimg.png']
];

// Logged-in user
$current_user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Followers</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="header">
    <div class="leftHeader">
        <li><a href="profile.php"><img class="chirpifyLogo" src="Image/Chripify.png" alt=""><h3>Chirpify</h3></a></li>
    </div>
    <div class="middleHeader">
        <a href="recommended.php">
            <button>For You</button>
        </a>
        <a href="followers.php"><button>Following</button></a>
    </div>
    <div class="rightHeader">
        <form action="" method="GET">
            <label>
                <input type="text" name="query" style="color: white;" placeholder="Looking for something?">
            </label>
            <button class="searchButton" type="submit">Search</button>
        </form>
        <?php if (isset($_GET['query'])): ?>
            <p class="searchQuery">You searched for: <strong><?php echo htmlspecialchars($_GET['query']); ?></strong></p>
        <?php endif; ?>
    </div>
</div>
<nav class="navBar">
    <ul>
        <li><a href="post.php"><i class="fa-solid fa-house"></i> <span>Home</span></a></li>
        <li><a href="#"><i class="fas fa-search"></i> <span>Search</span></a></li>
        <li><a href="#"><i class="fa-regular fa-compass"></i> <span>Explore</span></a></li>
        <li><a href="message.php"><i class="fa-regular fa-bell"></i> <span>Messages</span></a></li>
        <li><a href="#"><i class="fa-regular fa-envelope"></i> <span>Notification</span></a></li>
        <li><a href="#"><i class="fa-regular fa-square-plus"></i> <span>Create</span></a></li>
        <li><a href="profile.php"><i class="fa-regular fa-user"></i> <span>Profile</span></a></li>
        <li class="down"><a href="#"><i class="fas fa-crown"></i><span>Premium</span></a></li>
        <li class="down"><a href="#"><i class="fa fa-bars"></i><span>More</span></a></li>
        <li class="down"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i><span>Log out</span></a></li>
        <li class="underPro">
            <a href="#">
                <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="">
                <p><?php echo htmlspecialchars($_SESSION['user']); ?></p>
                <span>@<?php echo htmlspecialchars($_SESSION['user']); ?></span>
            </a>
        </li>
    </ul>
</nav>
<div class="mid">
    <div class="followingList">
        <?php if (!empty($following)): ?>
            <ul>
                <?php foreach ($following as $username => $user): ?>
                    <li class="followItem">
                        <img class="profileImg" src="<?php echo htmlspecialchars($user['image'] ?? 'assets/image/profileimg.png'); ?>" alt="Profile Picture">
                        <div class="followUserInfo">
                            <p><strong><?php echo htmlspecialchars($user['name']); ?></strong> (@<?php echo htmlspecialchars($username); ?>)</p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You are not following anyone yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>