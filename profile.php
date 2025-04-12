<?php
global $conn;
require "create_profile.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo htmlspecialchars($_SESSION['user']); ?> - Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
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
            <li class="underPro">
                <a href="profile.php">
                    <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="">
                    <p><?php echo htmlspecialchars($_SESSION['user']); ?></p>
                    <span>@<?php echo htmlspecialchars($_SESSION['user']); ?></span>
                </a>
            </li>
        </ul>
    </nav>
    <main class="middleHeader">
        <div class="profile">
            <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Picture">
            <div class="profile-info">
                <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong>
                <p>@<?php echo htmlspecialchars($_SESSION['user']); ?></p>
                <p><?php echo htmlspecialchars($_SESSION['bio']); ?></p>
            </div>
            <div class="likes">
                <a href="followers.php" class="followersLink">
                    <span>40 Followers</span>
                </a>
                <a href="followers.php" class="followersLink">
                    <span>20 Following</span>
                </a>
            </div>
            <form class="form" method="POST">
                <button type="submit" name="edit_profile">Edit Profile</button>
            </form>
        </div>
        <div class="form">
            <button class="buttonTab active">Posts</button>
            <button class="buttonTab">Replies</button>
            <button class="buttonTab">Media</button>
            <button class="buttonTab">Likes</button>
        </div>
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="names">
                        <img class="profileImg" src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Picture">
                        <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong>
                        <span>@<?php echo htmlspecialchars($_SESSION['user']); ?></span>
                    </div>
                    <p class="content"><?php echo nl2br(htmlspecialchars($post['post_text'])); ?></p>
                    <?php if (!empty($post['images']) && file_exists($post['images'])): ?>
                        <div class="postedImg">
                            <img src="<?php echo htmlspecialchars($post['images']); ?>" alt="Post Image">
                        </div>
                    <?php endif; ?>
                    <small class="date">Posted on: <?php echo date('M j, Y', strtotime($post['post_created_at'])); ?></small>
                    <div class="likes">
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
            <p>No posts yet.</p>
        <?php endif; ?>
    </main>
    <div class="rightHeader">
        <form action="user.php" method="GET">
            <input type="text" name="search" placeholder="Looking for something?">
        </form>
    </div>
</div>
</body>
</html>