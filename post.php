<?php
global $conn;
session_start();
require "database/database.php";
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$upload_dir = "assets/uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content'])) {
    $content = trim($_POST['content']);
    $image_path = "";

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_name = $upload_dir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_name)) {
            $image_path = $file_name;
        }
    }

    $_SESSION['posts'][] = [
        'user' => $_SESSION['user'],
        'content' => $content,
        'profile_picture' => $_SESSION['profile_picture'],
        'image' => $image_path,
        'likes' => 0,
        'reposts' => 0
    ];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="header">
    <div class="leftHeader">
        <li><a href="#"><img class="chirpifyLogo" src="Image/Chripify.png" alt=""> <h3>Chirpify</h3></a></li>
    </div>
    <div class="middleHeader">
        <button>For You</button>
        <button>Following</button>
    </div>
    <div class="rightHeader">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <label>
                <input type="text" name="search" style="color: white;" placeholder="Looking for something?">
            </label>
            <button class="searchButton" type="submit">Search</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET['search'])) {
            $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
            $stmt = $conn->prepare("SELECT username, profile_picture FROM users WHERE username LIKE :search ORDER BY username DESC");
            $stmt->execute([':search' => "%$search%"]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($results)) {
                echo "<p class='searchQuery'>No user found with the name <strong>" . htmlspecialchars($search) . "</strong></p>";
            } else {
                foreach ($results as $result) {
                    echo "<div class='searchResult'>";
                    echo "<img class='postImg' src='" . htmlspecialchars($result['profile_picture']) . "' alt='Profile Picture'>";
                    echo "<strong>" . htmlspecialchars($result['username']) . "</strong>";
                    echo "<span>@" . htmlspecialchars($result['username']) . "</span>";
                    echo "</div>";
                }
            }
        }
        ?>
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
        <!-- asking Kelvin -->

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
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="post">No posts yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>