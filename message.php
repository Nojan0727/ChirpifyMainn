<?php
global $conn;
session_start();
require("database/database.php");

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$current_user = $_SESSION['user'];
$recipient = $_GET['user'] ?? null;

// Get users from DB (excluding self)
$stmt = $conn->prepare("SELECT username, profile_picture FROM users WHERE username != :current_user");
$stmt->bindParam(':current_user', $current_user);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Messages</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<div class="header">
    <div class="leftHeader">
        <li><a href="profile.php"><h3>Chirpify</h3></a></li>
    </div>
    <div class="middleHeader">
        <a href="recommended.php"><button>For You</button></a>
        <a href="followers.php"><button>Following</button></a>
    </div>
    <div class="rightHeader">
        <form action="" method="GET">
            <label>
                <input type="text" name="query" style="color: white;" placeholder="Looking for something?">
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
                <img src="<?= htmlspecialchars($_SESSION['profile_picture']) ?>" alt="">
                <p><?= htmlspecialchars($_SESSION['user']) ?></p>
                <span>@<?= htmlspecialchars($_SESSION['user']) ?></span>
            </a>
        </li>
    </ul>
</nav>

<div class="body">
    <div class="messagePage">
        <div class="chatSidebar">
            <h4>Users</h4>
            <ul class="userList">
                <?php foreach ($users as $user): ?>
                    <li>
                        <a class="chatUser" href="message.php?user=<?= htmlspecialchars($user['username']) ?>">
                            <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="">
                            <div>
                                <strong><?= htmlspecialchars($user['username']) ?></strong>
                                <span>@<?= htmlspecialchars($user['username']) ?></span>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="chatArea">
            <?php if ($recipient): ?>
                <div class="chatHeader">
                    <h3>Chat with @<?= htmlspecialchars($recipient) ?></h3>
                </div>
                <div class="chatBox">
                    <!-- Dummy messages -->
                    <div class="chatMessage received"><p>Damn, you nail it!</p></div>
                    <div class="chatMessage sent"><p>Yeee!</p></div>
                </div>
                <form method="POST" class="chatForm">
                    <input type="hidden" name="to_user" value="<?= htmlspecialchars($recipient) ?>">
                    <input type="text" name="message_text" placeholder="Write a message..." required>
                    <button type="submit" name="send_message">Send</button>
                </form>
            <?php else: ?>
                <p class="selectUserMsg">Please select a user to start chatting.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $to_user = $_POST['to_user'];
    $message = trim($_POST['message_text']);
    echo "<script>alert('Message sent to @$to_user!'); window.location.href='message.php?user=$to_user';</script>";
    exit;
}
?>
</body>
</html>