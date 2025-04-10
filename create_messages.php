<?php
global $conn;
session_start();
require "database/database.php";

if (!isset($_SESSION['user']) || !isset($_SESSION['id'])) {
    if (basename($_SERVER['PHP_SELF']) !== 'index.php') {
        header("Location: index.php");
        exit();
    }
}

// show all the users
$stmt = $conn->prepare("SELECT id, username, profile_picture FROM users WHERE id != :current_user_id ORDER BY username ASC");
$stmt->execute([':current_user_id' => $_SESSION['id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// selecting user for sending messages
// sending messages to other user
$selected_user_id = null;
$messages = [];
if (isset($_GET['user_id'])) {
    $selected_user_id = (int)$_GET['user_id'];
    $stmt = $conn->prepare("
        SELECT messages.*, users.username, users.profile_picture 
        FROM messages  
        JOIN users ON users.id = messages.sender_id 
        WHERE (messages.sender_id = :current_user_id AND messages.recipient_id = :selected_user_id) 
           OR (messages.sender_id = :selected_user_id AND messages.recipient_id = :current_user_id) 
        ORDER BY messages.sent_at ASC
    ");
    $stmt->execute([
        ':current_user_id' => $_SESSION['id'],
        ':selected_user_id' => $selected_user_id
    ]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// starting new message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'], $_POST['message_text'], $_POST['recipient_id'])) {
    $message_text = trim($_POST['message_text']);
    $recipient_id = (int)$_POST['recipient_id'];

    if (!empty($message_text)) {
        try {
            $sql = "INSERT INTO messages (sender_id, recipient_id, message_text, sent_at) 
                    VALUES (:sender_id, :recipient_id, :message_text, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':sender_id' => $_SESSION['id'],
                ':recipient_id' => $recipient_id,
                ':message_text' => $message_text
            ]);
            header("Location: message.php?user_id=$recipient_id");
            exit();
        } catch (PDOException $e) {
            $error = "Error sending message: " . $e->getMessage();
        }
    } else {
        $error = "Message cannot be empty.";
    }
}
?>