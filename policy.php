<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/main.css">
    <script src="javascript/cookie.js"></script>
    <title>Policy</title>
</head>
<body>
<div class="policy-page">
    <div class="policy-header">
        <h1>Privacy & Cookie Policy</h1>
        <p class="policy-description">This policy explains what information we collect and how we use, protect, and manage your data. Please read carefully.</p>
    </div>

    <div class="policy-content">
        <section class="policy-section">
            <h2>Information We Collect</h2>
            <p class="policy-description">When you use Chirpify, we may collect the following information:</p>
            <ul class="policy-description">
                <li>Username</li>
                <li>Password (securely hashed)</li>
                <li>Email address (optional)</li>
                <li>Profile photo (optional)</li>
                <li>Age and short bio (if you choose to add them)</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2 class="policy-description">How We Use Your Information</h2>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Allow you to create and manage your account</li>
                <li>Show your profile to other users</li>
                <li>Save your posts, likes, and reposts</li>
                <li>Improve the security and functionality of our platform</li>
                <li>Analyze usage to improve Chirpify (non-personal analytics)</li>
            </ul>
            <p>We do not sell your data to anyone.</p>
        </section>

        <section class="policy-section">
            <h2>Cookies</h2>
            <h3>We use cookies to:</h3>
            <ul>
                <li>Keep you logged in (if you choose “Remember Me”)</li>
                <li>Remember your cookie preferences</li>
                <li>Improve user experience</li>
            </ul>
            <p>When you first visit Chirpify, you will see a cookie consent box. If you accept cookies, we store a small file in your browser to remember your choice. If you reject, you will be redirected away from the site.</p>
        </section>

        <section class="policy-section">
            <h2>How We Protect Your Data</h2>
            <ul>
                <li>Passwords are encrypted using secure hashing algorithms</li>
                <li>User sessions are protected through PHP sessions and optional cookies</li>
                <li>We regularly review our code and data handling for security</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2>Your Rights</h2>
            <ul>
                <li>Access the personal information we have about you</li>
                <li>Request correction or deletion of your data</li>
                <li>Withdraw your cookie consent at any time</li>
                <li>Delete your account permanently (feature coming soon)</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2>Changes to This Policy</h2>
            <p>We may update this policy as Chirpify grows or if we add new features. We’ll notify users of major changes on the login page or via a message.</p>
        </section>
    </div>

    <div class="cookie-actions">
        <button class="accept-btn" onclick="acceptCookies()">Accept</button>
        <button class="reject-btn" onclick="rejectCookies()">Reject</button>
    </div>
</div>
</body>
</html>