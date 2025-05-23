<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policy - Chirpify</title>
    <link rel="stylesheet" href="css/main.css">
    <script src="javascript/cookie.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="policyContainer">
    <div class="policyContentWrapper">
        <h1>Privacy & Cookie Policy</h1>
        <p class="policyDescription">This policy explains what information we collect and how we use, protect, and manage your data. Please read carefully.</p>
        <section class="policySection">
            <h2>Information We Collect</h2>
            <p class="policyDescription">When you use Chirpify, we may collect the following information:</p>
            <ul class="policyDescription">
                <li>Username</li>
                <li>Password (securely hashed)</li>
                <li>Email address (optional)</li>
                <li>Profile photo (optional)</li>
                <li>Age and short bio (if you choose to add them)</li>
            </ul>
        </section>
        <section class="policySection">
            <h2>How We Use Your Information</h2>
            <p class="policyDescription">We use the information we collect to:</p>
            <ul class="policyDescription">
                <li>Allow you to create and manage your account</li>
                <li>Show your profile to other users</li>
                <li>Save your posts, likes, and reposts</li>
                <li>Improve the security and functionality of our platform</li>
                <li>Analyze usage to improve Chirpify (non-personal analytics)</li>
            </ul>
            <p class="policyDescription">We do not sell your data to anyone.</p>
        </section>
        <section class="policySection">
            <h2>Cookies</h2>
            <h3>We use cookies to:</h3>
            <ul class="policyDescription">
                <li>Keep you logged in (if you choose “Remember Me”)</li>
                <li>Remember your cookie preferences</li>
                <li>Improve user experience</li>
            </ul>
            <p class="policyDescription">When you first visit Chirpify, you will see a cookie consent box. If you accept cookies, we store a small file in your browser to remember your choice. If you reject, you will be redirected away from the site.</p>
        </section>
        <section class="policySection">
            <h2>How We Protect Your Data</h2>
            <ul class="policyDescription">
                <li>Passwords are encrypted using secure hashing algorithms</li>
                <li>User sessions are protected through PHP sessions and optional cookies</li>
                <li>We regularly review our code and data handling for security</li>
            </ul>
        </section>
        <section class="policySection">
            <h2>Your Rights</h2>
            <ul class="policyDescription">
                <li>Access the personal information we have about you</li>
                <li>Request correction or deletion of your data</li>
                <li>Withdraw your cookie consent at any time</li>
                <li>Delete your account permanently (feature coming soon)</li>
            </ul>
        </section>
        <section class="policySection">
            <h2>Changes to This Policy</h2>
            <p class="policyDescription">We may update this policy as Chirpify grows or if we add new features. We’ll notify users of major changes on the login page or via a message.</p>
        </section>
    </div>
    <div class="cookieActions">
        <button class="cookieBtn acceptBtn" onclick="acceptCookies()">Accept</button>
        <button class="cookieBtn rejectBtn" onclick="rejectCookies()">Reject</button>
    </div>
</div>
</body>
</html>