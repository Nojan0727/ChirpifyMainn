<?php
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=localhost;dbname=learn", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>