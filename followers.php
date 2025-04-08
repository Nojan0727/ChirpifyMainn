<?php
session_start();
require("database/database.php");
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

//data for users
$following = [
    'john_doe' => ['name' => 'John Doe', 'image' => 'assets/image/profileimg.png'],
    'jane_smith' => ['name' => 'Jane Smith', 'image' => 'assets/image/profileimg.png'],
    'michael_Tree' => ['name' => 'Michael Tree', 'image' => 'assets/image/profileimg.png'],
    'Jenny_Street' => ['name' => 'Jenny Street', 'image' => 'assets/image/profileimg.png']
];

//current user
$current_user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Followers</title>
    <link rel="stylesheet" href="css/main.css">
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
    <div class="rightHeader">
        <form action="" method="GET">
            <label>
                <input type="text" name="query" style="color: white;" placeholder="Looking for something?">
            </label>
            <button class="searchButton" type="submit">Search</button>
        </form>

        <?php
        if (isset($_GET['query'])) {
            $searchTerm = htmlspecialchars($_GET['query']);
            echo "<p class='searchQuery'>You searched for: <strong>" . $searchTerm . "</strong></p>";
        }
        ?>
    </div>
</div>
<div class="body">
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