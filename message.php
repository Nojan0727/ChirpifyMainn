<?php
require"create_messages.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Chirpify</title>
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
        <form action="recommended.php" method="GET" style="display: inline;">
            <button type="submit" name="type" value="">For You</button>
        </form>
        <form action="followers.php" method="GET" style="display: inline;">
            <button type="submit" name="type" value="">Following</button>
        </form>
    </div>
    <div class="rightHeader">
        <form action="post.php" method="GET">
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
        <li><a href="#"><i class="fas fa-search"></i> <span>Search</span></a></li>
        <li><a href="#"><i class="fa-regular fa-compass"></i> <span>Explore</span></a></li>
        <li><a href="message.php"><i class="fa-regular fa-bell"></i> <span>Messages</span></a></li>
        <li><a href="#"><i class="fa-regular fa-envelope"></i> <span>Notification</span></a></li>
        <li><a href="#"><i class="fa-regular fa-square-plus"></i> <span>Create</span></a></li>
        <li><a href="profile.php"><i class="fa-regular fa-user"></i> <span>Profile</span></a></li>
        <li class="down"><a href="#"><i class="fas fa-crown"></i><span>Premium</span></a></li>
        <li class="down"><a href="#"><i class="fa fa-bars"></i><span>More</span></a></li>
        <li class="down"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i><span>Log out</span></a></li>
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
    <div class="messageContainer">
        <div class="userList">
            <h2>Messages</h2>
            <?php foreach ($users as $user): ?>
                <div class="userItem messageTrigger" data-user-id="<?php echo $user['id']; ?>">
                    <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                    <span>@<?php echo htmlspecialchars($user['username']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="chatArea">
            <?php if ($selected_user_id): ?>
                <div class="chatHeader">
                    <?php
                    $stmt = $conn->prepare("SELECT username, profile_picture FROM users WHERE id = :user_id");
                    $stmt->execute([':user_id' => $selected_user_id]);
                    $selected_user = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <img src="<?php echo htmlspecialchars($selected_user['profile_picture']); ?>" alt="Profile Picture">
                    <h3><?php echo htmlspecialchars($selected_user['username']); ?></h3>
                </div>
                <div class="messages">
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $message): ?>
                            <div class="message <?php echo $message['sender_id'] == $_SESSION['id'] ? 'sent' : 'received'; ?>">
                                <img src="<?php echo htmlspecialchars($message['profile_picture']); ?>" alt="Profile Picture">
                                <div class="messageContent">
                                    <p><?php echo htmlspecialchars($message['message_text']); ?></p>
                                    <small><?php echo $message['sent_at']; ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No messages yet. Start the conversation!</p>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Message Pop up -->
<div id="messagePopup" class="messagePopup">
    <div class="messagePopupContent">
        <span class="closeMessagePopup"><i class="fa-solid fa-x"></i></span>
        <form action="" class="messageForm" method="POST">
            <label class="messagePro">
                <img src="<?php echo htmlspecialchars($_SESSION["profile_picture"]); ?>" alt="">
                <strong><?php echo htmlspecialchars($_SESSION["user"]); ?></strong>
                <span>@<?php echo htmlspecialchars($_SESSION["user"]); ?></span>
                <textarea name="message_text" placeholder="Type a message..." required></textarea>
            </label>
            <input type="hidden" name="recipient_id" id="messageRecipientId">
            <button name="send_message">Send</button>
        </form>
    </div>
</div>

</body>
</html>