<?php
global $conn;
session_start();
require"database/database.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT username, bio, profile_picture, age FROM users WHERE username = :username");
$stmt->execute([':username' => $_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $_SESSION['user'] = $user['username'];
    $_SESSION['bio'] = $user['bio'] ?? 'No bio yet.';
    $_SESSION['profile_picture'] = $user['profile_picture'] ?? 'assets/uploads/default.jpg';
    $_SESSION['age'] = $user['age'];
}

// Handle post deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletePost'])) {
    $index = (int)$_POST['deletePost'];
    if (isset($_SESSION['posts'][$index]) && $_SESSION['posts'][$index]['user'] === $_SESSION['user']) {
        array_splice($_SESSION['posts'], $index, 1);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
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
        <a href="recommended.php"><button>For You</button></a>
        <a href="Followers.php"><button>Following</button></a>
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
        <li><a href="#"><i class="fa-regular fa-bell"></i> <span>Messages</span></a></li>
        <li><a href="#"><i class="fa-regular fa-envelope"></i> <span>Notification</span></a></li>
        <li><a href="#"><i class="fa-regular fa-square-plus"></i> <span>Create</span></a></li>
        <li><a href="profile.php"><i class="fa-regular fa-user"></i> <span>Profile</span></a></li>
        <li class="down"><a href="#"><i class="fas fa-crown"></i><span>Premium</span></a></li>
        <li class="down"><a href="#"><i class="fa fa-bars"></i><span>More</span></a></li>
        <li class="down"><a href="index.php"><i class="fa-solid fa-right-from-bracket"></i><span>Log out</span></a></li>
    </ul>
</nav>
<div class="body">
    <div class="profile">
        <div class="banner">
            <!-- Asking Kelvin -->
            <?php
            echo "Profile picture path: " . htmlspecialchars($_SESSION['profile_picture']) . "<br>";
            $full_path = $_SERVER['DOCUMENT_ROOT'] . $_SESSION['profile_picture'];
            echo "Full server path: $full_path<br>";
            echo "File exists: " . (file_exists($full_path) ? "Yes" : "No") . "<br>";
            ?>
            <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile Picture" class="profilePic">
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
            <?php $posts = $_SESSION['posts'] ?? []; ?>
            <?php foreach ($posts as $index => $post): ?>
                <?php if ($post['user'] === $_SESSION['user']): ?>
                    <div class="post">
                        <p class="postContent"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        <?php if (!empty($post['image']) && file_exists($post['image'])): ?>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" style="max-width: 500px; border-radius: 15px;" alt="Post Image">
                        <?php endif; ?>
                        <div class="postActions">
                            <span><i class="fa-regular fa-heart"></i> <?php echo $post['likes'] ?? 0; ?></span>
                            <span><i class="fa-solid fa-retweet"></i> <?php echo $post['reposts'] ?? 0; ?></span>
                            <form action="" method="post" style="display:inline;">
                                <button class="deleteBtn" type="submit" name="deletePost" value="<?php echo $index; ?>">
                                    <i class="fa-solid fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if (empty(array_filter($posts, fn($p) => $p['user'] === $_SESSION['user']))): ?>
                <p class="post">No posts yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>