<?php
require "create_recommended.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommended - Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="header">
    <div class="leftHeader">
        <ul>
            <li><a href="profile.php"><img class="chirpifyLogo" src="Image/Chripify.png" alt="Chirpify Logo"><h3>Chirpify</h3></a></li>
        </ul>
    </div>
    <div class="middleHeader">
        <form action="recommended.php" method="GET" style="display: inline;">
            <button type="submit" name="type" value="">For You</button>
        </form>
        <form action="followers.php" method="GET" style="display: inline;">
            <button type="submit" name="type" value="">Following</button>
        </form>
    </div>
    <div class="rightHeader">
        <form action="" method="GET">
            <input type="text" name="query" placeholder="Looking for something?">
        </form>
    </div>
</div>
<div class="body">
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
        </ul>
        <div class="underPro">
            <a href="#">
                <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Picture">
                <p><?php echo htmlspecialchars($_SESSION['user']); ?></p>
                <span>@<?php echo htmlspecialchars($_SESSION['user']); ?></span>
            </a>
        </div>
    </nav>
    <div class="middleHeader">
        <h3>Recommended Posts</h3>
        <?php if (!empty($recommended_posts)): ?>
            <?php foreach (array_reverse($recommended_posts) as $post): ?>
                <div class="post">
                    <div class="names">
                        <img class="profileImg" src="<?php echo $post['profile_picture']; ?>" alt="Profile Picture">
                        <strong><?php echo htmlspecialchars($post['user']); ?></strong>
                        <span>@<?php echo htmlspecialchars($post['user']); ?></span>
                    </div>
                    <p class="content">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </p>
                    <?php if (!empty($post['image'])): ?>
                        <p class="postedImg">
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Image">
                        </p>
                    <?php endif; ?>
                    <p class="likes">
                        <i class="fa-regular fa-heart like-icon"></i>
                        <span><?php echo $post['likes']; ?></span>
                        <i class="fa-solid fa-retweet repost-icon"></i>
                        <span><?php echo $post['reposts']; ?></span>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No recommended posts yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>