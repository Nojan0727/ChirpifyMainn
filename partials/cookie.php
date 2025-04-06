<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["acceptCookies"])) {
    setcookie("cookieConsent", "accepted", time() + (365 * 24 * 60 * 60), "/");

    echo "success";
    exit();
}
?>