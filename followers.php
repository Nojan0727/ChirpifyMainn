<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
$following = [
    'john_doe' => ['name' => 'John Doe', 'image' => 'assets/image/profileimg.png'],
    'jane_smith' => ['name' => 'Jane Smith', 'image' => 'assets/image/profileimg.png'],
    'michael_tree' => ['name' => 'Michael Tree', 'image' => 'assets/image/profileimg.png'],
    'jenny_street' => ['name' => 'Jenny Street', 'image' => 'assets/image/profileimg.png']
];
$current_user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Following-Chirpify</title>
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
            <div class="follow-list">
                <?php if (!empty($following)): ?>
                    <?php foreach ($following as $username => $user): ?>
                        <div class="follower">
                            <img class="profileImg" src="<?php echo htmlspecialchars($user['image']); ?>" alt="Profile Picture">
                            <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                            <span>@<?php echo htmlspecialchars($username); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>You are not following anyone yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <div class="rightHeader">
        <form action="user.php" method="GET">
            <input type="text" name="search" placeholder="Looking for something?">
        </form>
    </div>
</div>
</body>
</html>