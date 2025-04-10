document.addEventListener("DOMContentLoaded", function () {
    console.log("Cookie JavaScript Loaded!");

    // Check if cookie consent is already set
    if (!document.cookie.includes("cookie_consent")) {
        document.getElementById("cookieBox").style.display = "block";
        document.getElementById("cookieOverlay").style.display = "block";
    }
});

function acceptCookies() {
    console.log("Accept button clicked!");
    document.cookie = "cookie_consent=accepted; path=/; max-age=" + (60);

    if (document.cookie.includes("cookie_consent=accepted")) {
        console.log("Cookie was set successfully!");
        location.href = "index.php";
    } else {
        console.log("Failed to set cookie.");
    }

    document.getElementById("cookieBox").style.display = "none";
    document.getElementById("cookieOverlay").style.display = "none";
    location.reload();
}

function rejectCookies() {
    console.log("Reject button clicked!");

    document.cookie = "cookie_consent=rejected; path=/; max-age=" + (60);
    window.location.href = "https://www.google.com";
}

function toggleTerms() {
    let termsBox = document.getElementById("termsBox");
    if (termsBox.style.display === "none" || termsBox.style.display === "") {
        termsBox.style.display = "block";
    } else {
        termsBox.style.display = "none";
    }
}