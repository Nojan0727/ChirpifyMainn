function acceptCookies() {
    console.log("Accept button clicked!");
    document.cookie = "cookie_consent=accepted; path=/; max-age=" + (24);


    if (document.cookie.includes("cookie_consent=accepted")) {
        console.log("Cookie was set successfully!");
    } else {
        console.log("Failed to set cookie.");
    }

    document.getElementById("cookieBox").style.display = "none";
    location.reload();
}

function rejectCookies() {
    console.log("Reject button clicked!");
    window.location.href = "https://www.google.com";
}

document.addEventListener("DOMContentLoaded", function () {
    console.log("JavaScript Loaded!");
});
function toggleTerms() {
    let termsBox = document.getElementById("termsBox");
    if (termsBox.style.display === "none" || termsBox.style.display === "") {
        termsBox.style.display = "block";
    } else {
        termsBox.style.display = "none";
    }
}