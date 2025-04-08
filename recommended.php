<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("database/database.php");

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$recommended_posts = [];
if (!empty($_SESSION['posts'])) {
    foreach ($_SESSION['posts'] as $post) {
        if ($post['user'] !== $_SESSION['user']) {
            $recommended_posts[] = $post;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Recommended - Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="header">
    <div class="leftHeader">
        <li><a href="profile.php"><h3>Chirpify</h3></a></li>
    </div>
    <div class="middleHeader">
        <a href="recommended.php">
            <button>For You</button>
        </a>
        <a href="followers.php"> <button>Following</button></a>
    </div>

    <div class="rightHeader">
        <form action="" method="GET">
            <label>
                <input type="text" name="query" style="color: white;" placeholder="Looking for something?">
            </label>
            <button class="searchButton" type="submit">Search</button>
        </form>

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
        <li class="down"><a href="index.php"><i class="fa-solid fa-right-from-bracket"></i><span>Log out</span></a></li>
        <li class="underPro">
            <a href="#">
                <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="">
                <p><?php echo htmlspecialchars($_SESSION['user']); ?></p>
                <span>@<?php echo htmlspecialchars($_SESSION['user']); ?></span>
            </a>
        </li>
    </ul>
</nav>
<h2 class="logo">Chirpify</h2>

<div class="header">
    <div class="leftHeader">
        <li><a href="profile.php"><img class="chirpifyLogo" src="Image/Chripify.png" alt=""></a></li>
    </div>
    <div class="middleHeader">
        <a href="recommended.php">
            <button>For You</button>
        </a>
        <a href="../../Followers.php">
            <button>Following</button>
        </a>
    </div>
</div>

<div class="body">
    <div class="happening">
        <h3 class="recentPost">Recommended Posts</h3>

        <?php if (!empty($recommended_posts)): ?>
            <?php foreach (array_reverse($recommended_posts) as $post): ?>
                <div class="post">
                    <p class="names">
                        <img class="postImg" src="<?php echo $post['profile_picture']; ?>" alt="Profile Picture">
                        <strong><?php echo htmlspecialchars($post['user']); ?></strong>
                        <span>@<?php echo htmlspecialchars($post['user']); ?></span>
                    </p>

                    <p class="content">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </p>

                    <?php if (!empty($post['image'])): ?>
                        <p class="postedImg">
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Image"
                                 style="max-width: 500px; border-radius: 15px; min-width:500px; max-height: 300px; object-fit:cover; object-position: center;">
                        </p>
                    <?php endif; ?>

                    <p>
                        <i class="fa-regular fa-heart like-icon"></i>
                        <span><?php echo $post['likes']; ?></span>

                        <i class="fa-solid fa-retweet repost-icon"></i>
                        <span><?php echo $post['reposts']; ?></span>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="post">No recommended posts yet.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
