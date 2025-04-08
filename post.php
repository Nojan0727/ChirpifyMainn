<?php
require "create_post.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
    <script src="javascript/main.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="header">
    <div class="leftHeader">
        <li><a href="#"><img class="chirpifyLogo" src="Image/Chripify.png" alt=""> <h3>Chirpify</h3></a></li>
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <label>
                <input type="text" name="search" style="color: white;" placeholder="Looking for something?">
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
<div class="body">
    <div class="happening">
        <img class="profileImg" src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Picture">
        <form class="form" action="" method="post" enctype="multipart/form-data">
            <label class="happeningLabel">
                <textarea name="content" placeholder="What's Happening!?" required></textarea>
            </label>
            <input type="file" name="image" accept="image/*">
            <button type="submit">Post!</button>
        </form>
        <div style="position: relative; border-bottom: 1px solid rgb(70, 70, 70); height: 0;">
            <h2 class="recentPost">Recent Posts</h2>
        </div>
        <?php if (!empty($_SESSION['posts'])): ?>
            <?php foreach (array_reverse($_SESSION['posts']) as $index => $post): ?>
                <div class="post">
                    <p class="names">
                        <img class="postImg" src="<?php echo htmlspecialchars($post['profile_picture']); ?>" alt="Profile Picture">
                        <strong><?php echo htmlspecialchars($post['user']); ?></strong>
                        <span>@<?php echo htmlspecialchars($post['user']); ?></span>
                    </p>
                    <p class="content">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </p>
                    <p class="postedImg">
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Image" style="max-width: 500px; border-radius: 15px; min-width: 500px; max-height: 300px; object-fit: cover; object-position: center;">
                        <?php endif; ?>
                    </p>
                    <p class="likes">
                        <span class="like">
                            <i class="fa-regular fa-heart like-icon" data-index="<?php echo $index; ?>"></i>
                            <span id="like-count-<?php echo $index; ?>"><?php echo $post['likes']; ?></span>
                        </span>
                        <span class="repost">
                            <i class="fa-solid fa-retweet repost-icon" data-index="<?php echo $index; ?>"></i>
                            <span id="repost-count-<?php echo $index; ?>"><?php echo $post['reposts']; ?></span>
                        </span>
                        <span class="commentBlock">
                            <span class="commentTrigger"><i class="fa-solid fa-comment"></i></span>
                            <form class="commentForm" method="POST" action="" style="display: none;">
                                <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['post_id']); ?>">
                                <button type="submit" name="post_comment">Post</button>
                            </form>
                        </span>
                    </p>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" class="commentForm" id="commentform<?php echo $index; ?>" style="display:none;" method="post">
                        <span class="commentBlock">
                            <span class="closeComment"><i class="fa-solid fa-x"></i></span>
                        </span>
                        <label class="commentPro">
                            <img src="<?php echo htmlspecialchars($_SESSION["profile_picture"]); ?>" alt="">
                            <strong><?php echo htmlspecialchars($_SESSION["user"]); ?></strong>
                            <span>@<?php echo htmlspecialchars($_SESSION["user"]); ?></span>
                            <textarea name="comment_text" placeholder="Post your reply" required autocomplete="off"></textarea>
                        </label>
                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['post_id']); ?>">
                        <button name="post_comment">Post</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="post">No posts yet.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>