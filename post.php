<?php
global $conn;
require "create_post.php";
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
        <div class="form">
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <img class="profileImg" src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Picture">
            <form class="form" action="" method="post" enctype="multipart/form-data">
                <textarea name="post_text" placeholder="What's Happening!?" required></textarea>
                <input type="file" name="image" accept="image/*">
                <button type="submit" name="create_post">Post!</button>
            </form>
        </div>
        <?php if (!empty($search_results)): ?>
            <?php foreach ($search_results as $result): ?>
                <div class="searchResult">
                    <img class="profileImg" src="<?php echo htmlspecialchars($result['profile_picture']); ?>" alt="Profile Picture">
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
                    <div class="names">
                        <img class="profileImg" src="<?php echo htmlspecialchars($post['profile_picture']); ?>" alt="Profile Picture">
                        <strong><?php echo htmlspecialchars($post['username']); ?></strong>
                        <span>@<?php echo htmlspecialchars($post['username']); ?></span>
                    </div>
                    <p class="content">
                        <?php echo nl2br(htmlspecialchars($post['post_text'])); ?>
                    </p>
                    <?php if (!empty($post['images'])): ?>
                        <div class="postedImg">
                            <img src="<?php echo htmlspecialchars($post['images']); ?>" alt="Image">
                        </div>
                    <?php endif; ?>
                    <div class="likes">
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
                    </div>
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
                                <div class="commentUser">
                                    <img src="<?php echo htmlspecialchars($comment['profile_picture']); ?>" alt="Profile picture">
                                    <span>@<?php echo htmlspecialchars($comment['username']); ?></span>
                                </div>
                                <div class="commentText">
                                    <?php if ($post['user_id'] == $comment['user_id']): ?>
                                        <div class="admin">Creator</div>
                                    <?php endif; ?>
                                    <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                                    <small class="date">Commented on: <?php echo date('M d, Y', strtotime($comment['comment_posted_at'])); ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="post">No posts yet.</p>
        <?php endif; ?>
    </main>
    <div class="rightHeader">
        <form action="user.php" method="GET">
            <input type="text" name="search" placeholder="Looking for something?">
        </form>
    </div>
    <!-- Comment popup -->
    <div id="commentPopup" class="commentPopup" style="display: none;">
        <div class="commentPopupContent">
            <span class="closePopup"><i class="fa-solid fa-x"></i></span>
            <form action="" class="commentForm" method="POST">
                <label class="commentPro">
                    <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="">
                    <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong>
                    <span>@<?php echo htmlspecialchars($_SESSION['user']); ?></span>
                    <textarea name="comment_text" placeholder="Post your reply" required autocomplete="off"></textarea>
                </label>
                <input type="hidden" name="post_id" id="commentPostId">
                <button type="submit" name="post_comment">Post</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>