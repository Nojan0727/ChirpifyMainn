<?php
session_start();
$conn = require "database/database.php";

echo "<pre>Session Variables:\n";
var_dump($_SESSION);

if (!$conn) {
    die("Database connection failed.");
}

$base_url = '/';

if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content'])) {
    $content = trim($_POST['content']);
    try {
        $stmt = $conn->prepare("INSERT INTO messages (content, user_id, created_at) VALUES (:content, :user_id, NOW())");
        $stmt->execute([
            ':content' => $content,
            ':user_id' => $user_id
        ]);
        header("Location: messages.php");
        exit();
    } catch (PDOException $e) {
        $error = "Failed to send message: " . $e->getMessage();
    }
}

if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}


$messages = [];
try {
    $stmt = $conn->prepare("SELECT m.id, m.content, m.created_at, u.username 
                           FROM messages m 
                           LEFT JOIN users u ON m.user_id = u.id 
                           ORDER BY m.created_at DESC");
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Failed to fetch messages: " . $e->getMessage();
}

if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}

echo "<pre>Messages:\n";
var_dump($messages);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Chirpify</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="header">
    <div class="leftHeader">
        <li><a href="#"><img class="chirpifyLogo" src="<?php echo $base_url; ?>Image/Chripify.png" alt=""> <h3>Chirpify</h3></a></li>
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
    </div>
</div>
<nav class="navBar">
    <ul>
        <li><a href="post.php"><i class="fa-solid fa-house"></i> <span>Home</span></a></li>
        <li><a href="#"><i class="fas fa-search"></i> <span>Search</span></a></li>
        <li><a href="#"><i class="fa-regular fa-compass"></i> <span>Explore</span></a></li>
        <li><a href="messages.php"><i class="fa-regular fa-bell"></i> <span>Messages</span></a></li>
        <li><a href="#"><i class="fa-regular fa-envelope"></i> <span>Notification</span></a></li>
        <li><a href="#"><i class="fa-regular fa-square-plus"></i> <span>Create</span></a></li>
        <li><a href="profile.php"><i class="fa-regular fa-user"></i> <span>Profile</span></a></li>
        <li class="down"><a href="#"><i class="fas fa-crown"></i><span>Premium</span></a></li>
        <li class="down"><a href="#"><i class="fa fa-bars"></i><span>More</span></a></li>
        <li class="down"><a href="index.php"><i class="fa-solid fa-right-from-bracket"></i><span>Log out</span></a></li>
        <li class="underPro">
            <a href="#">
                <img src="<?php echo $base_url . htmlspecialchars($_SESSION['profile_picture']); ?>" alt="">
                <p><?php echo htmlspecialchars($_SESSION['user']); ?></p>
                <span>@<?php echo htmlspecialchars($_SESSION['user']); ?></span>
            </a>
        </li>
    </ul>
</nav>

<div>
    <h2>Messages</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="content">Post a message:</label><br>
        <textarea name="content" id="content" rows="3" required></textarea><br>
        <button type="submit">Send</button>
    </form>

    <h3>All Messages</h3>
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <div>
                <p><strong><?php echo htmlspecialchars($message['username'] ?? 'Unknown User'); ?></strong>
                    (<?php echo htmlspecialchars($message['created_at']); ?>)</p>
                <p><?php echo htmlspecialchars($message['content']); ?></p>
                <hr>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>
</div>
</body>
</html>