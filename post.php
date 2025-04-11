<?php
global $conn;
require "create_post.php";
file_put_contents('debug.log', "Accessing " . basename($_SERVER['PHP_SELF']) . " at " . date('Y-m-d H:i:s') . " with session user: " . ($_SESSION['user'] ?? 'none') . "\n", FILE_APPEND);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
    <script defer src="javascript/main.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="header">
    <div class="leftHeader">
        <li><a href="#"><img class="chirpifyLogo" src="Image/Chripify.png" alt=""> <h3>Chirpify</h3></a></li>
    </div>
    <div class="middleHeader">

        <form action="followers.php" method="GET" style="display: inline;">
            <button type="submit" name="type" value="">Following</button>
        </form>
    </div>
    <div class="rightHeader">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <label>
                <input type="text" name="search" style="" placeholder="Looking for something?">
            </label>
            <button class="searchButton" type="submit">Search</button>
        </form>
    </div>
</div>
<nav class="navBar">
    <ul>
        <li><a href="post.php"><i class="fa-solid fa-house"></i> <span>Home</span></a></li>
        <li><a href="construction.php"><i class="fas fa-search"></i> <span>Search</span></a></li>
        <li><a href="construction.php"><i class="fa-regular fa-compass"></i> <span>Explore</span></a></li>
        <li><a href="message.php"><i class="fa-regular fa-bell"></i> <span>Messages</span></a></li>
        <li><a href="construction.php"><i class="fa-regular fa-envelope"></i> <span>Notification</span></a></li>
        <li><a href="construction.php"><i class="fa-regular fa-square-plus"></i> <span>Create</span></a></li>
        <li><a href="profile.php"><i class="fa-regular fa-user"></i> <span>Profile</span></a></li>
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
<div class="mid">
    <div class="happening">
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <img class="profileImg" src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Picture">
        <form class="form" action="" method="post" enctype="multipart/form-data">
            <label class="happeningLabel">
                <textarea name="post_text" placeholder="What's Happening!?" required></textarea>
            </label>
            <input type="file" name="image" accept="image/*">
            <button type="submit" name="create_post">Post!</button>
        </form>
        <div style="position: relative; border-bottom: 1px solid rgb(70, 70, 70); height: 0;">
            <h2 class="recentPost">Recent Posts</h2>
        </div>
        <?php if (!empty($search_results)): ?>
            <?php foreach ($search_results as $result): ?>
                <div class="searchResult">
                    <img class="postImg" src="<?php echo htmlspecialchars($result['profile_picture']); ?>" alt="Profile Picture">
                    <strong><?php echo htmlspecialchars($result['username']); ?></strong>
                    <span>@<?php echo htmlspecialchars($result['username']); ?></span>
                </div>
            <?php endforeach; ?>
        <?php elseif (isset($_GET['search'])): ?>
            <p class="searchQuery">No user found with the name <strong><?php echo htmlspecialchars($_GET['search']); ?></strong></p>
        <?php endif; ?>
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $index => $post): ?>
                <div class="post">
                    <p class="names">
                        <img class="postImg" src="<?php echo htmlspecialchars($post['profile_picture']); ?>" alt="Profile Picture">
                        <strong><?php echo htmlspecialchars($post['username']); ?></strong>
                        <span>@<?php echo htmlspecialchars($post['username']); ?></span>
                    </p>
                    <p class="content">
                        <?php echo nl2br(htmlspecialchars($post['post_text'])); ?>
                    </p>
                    <p class="postedImg">
                        <?php if (!empty($post['images'])): ?>
                            <img src="<?php echo htmlspecialchars($post['images']); ?>" alt="Image" style="max-width: 500px; border-radius: 15px; min-width: 500px; max-height: 300px; object-fit: cover; object-position: center;">
                        <?php endif; ?>
                    </p>
                    <p class="likes">
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id");
                        $stmt->execute([':user_id' => $_SESSION['id'], ':post_id' => $post['id']]);
                        $has_liked = $stmt->fetch(PDO::FETCH_ASSOC);

                        $stmt = $conn->prepare("SELECT COUNT(*) as like_count FROM likes WHERE post_id = :post_id");
                        $stmt->execute([':post_id' => $post['id']]);
                        $like_count = $stmt->fetch(PDO::FETCH_ASSOC)['like_count'];
                        ?>
                        <span class="like">
                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <button type="submit" name="like_post" class="like-button">
                                    <i class="fa-regular fa-heart like-icon <?php echo $has_liked ? 'liked' : ''; ?>" data-index="<?php echo $index; ?>"></i>
                                    <span id="like-count-<?php echo $index; ?>"><?php echo $like_count; ?></span>
                                </button>
                            </form>
                        </span>
                        <span class="commentBlock">
    <span class="commentTrigger" data-post-id="<?php echo $post['id']; ?>">
        <i class="fa-solid fa-comment"></i>
    </span>
</span>
                    </p>

                    <?php
                    $stmt = $conn->prepare("SELECT comments.*, users.username, users.profile_picture
                        FROM comments
                        JOIN users ON comments.user_id = users.id
                        WHERE comments.post_id = :post_id
                        ORDER BY comment_posted_at ASC");
                    $stmt->execute([':post_id' => $post['id']]);
                    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <div class="comments">
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <img class="commentProfile" src="<?php echo htmlspecialchars($comment["profile_picture"]); ?>" alt="Profile picture">
                                <strong><?php echo htmlspecialchars($comment["username"]); ?></strong>
                                <span>@<?php echo htmlspecialchars($comment['username']); ?></span>
                                <?php if ($post['user_id'] == $comment['user_id']): ?>
                                    <div class="admin">Creator</div>
                                <?php endif; ?>
                                <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                                <small class="date">Commented on: <?php echo date('M j, Y', strtotime($comment['comment_posted_at'])); ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php endforeach; ?>
                    <?php else: ?>
                        <p class="post">No posts yet.</p>
                    <?php endif; ?>
                </div>
    </div>

    <!-- COMMENT POPUP -->
    <div id="commentPopup" class="commentPopup" style="display: none;">
        <div class="commentPopupContent">
            <span class="closePopup"><i class="fa-solid fa-x"></i></span>
            <form action="post.php" class="commentForm" method="POST">
                <label class="commentPro">
                    <img src="<?php echo htmlspecialchars($_SESSION["profile_picture"]); ?>" alt="">
                    <strong><?php echo htmlspecialchars($_SESSION["user"]); ?></strong>
                    <span>@<?php echo htmlspecialchars($_SESSION["user"]); ?></span>
                    <textarea name="comment_text" placeholder="Post your reply" required autocomplete="off"></textarea>
                </label>
                <input type="hidden" name="post_id" id="commentPostId">
                <button name="post_comment">Post</button>
            </form>
        </div>
    </div>

</body>
</html>