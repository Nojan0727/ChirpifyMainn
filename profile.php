<?php
global $conn;
require "create_profile.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo htmlspecialchars($_SESSION['user']); ?></title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/profile-x.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="header">
    <div class="leftHeader">
        <li><a href="profile.php"><img class="chirpifyLogo" src="Image/Chripify.png" alt="">
                <h3>Chirpify</h3></a></li>
    </div>
    <div class="middleHeader">
        <a href="recommended.php">
            <button>For You</button>
        </a>
        <a href="followers.php">
            <button>Following</button>
        </a>
    </div>
    <div class="rightHeader">
        <form action="" method="GET">
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
        <li class="down"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i><span>Log out</span></a></li>
    </ul>
</nav>
<div class="body">
    <div class="profile">
        <div class="banner">
            <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Picture"
                 class="profilePic">
        </div>
        <div class="userInfo">
            <div class="username">
                <h2><?php echo htmlspecialchars($_SESSION['user']); ?></h2>
                <span class="handle">@<?php echo htmlspecialchars($_SESSION['user']); ?></span>
            </div>
            <p class="bio"><?php echo htmlspecialchars($_SESSION['bio']); ?></p>
            <div class="stats">
                <span><strong>150</strong> Followers</span>
                <span><strong>90</strong> Following</span>
            </div>
            <button class="editBtn">Edit Profile</button>
        </div>
        <div class="tabs">
            <button class="buttonTab active">Posts</button>
            <button class="buttonTab">Replies</button>
            <button class="buttonTab">Media</button>
            <button class="buttonTab">Likes</button>
        </div>
        <div class="posts">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <p class="postContent"><?php echo nl2br(htmlspecialchars($post['post_text'])); ?></p>
                        <?php if (!empty($post['images']) && file_exists($post['images'])): ?>
                            <img src="<?php echo htmlspecialchars($post['images']); ?>"
                                 style="max-width: 500px; border-radius: 15px;" alt="Post Image">
                        <?php endif; ?>
                        <small>Posted on: <?php echo $post['post_created_at']; ?></small>
                        <div class="postActions">
                            <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) as like_count FROM likes WHERE post_id = :post_id");
                            $stmt->execute([':post_id' => $post['id']]);
                            $like_count = $stmt->fetch(PDO::FETCH_ASSOC)['like_count'];
                            ?>
                            <span><i class="fa-regular fa-heart"></i> <?php echo $like_count; ?></span>
                            <form action="" method="POST" style="display:inline;">
                                <button class="deleteBtn" type="submit" name="deletePost" value="<?php echo $post['id']; ?>">
                                    <i class="fa-solid fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="post">No posts yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>