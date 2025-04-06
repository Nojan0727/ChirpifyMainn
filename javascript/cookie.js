document.addEventListener("DOMContentLoaded", function () {
    const cookieBox = document.getElementById("cookieBox");
    const cookieOverlay = document.getElementById("cookieOverlay");
    const acceptButton = document.getElementById("acceptCookies");

    function getCookie(name) {
        return document.cookie.split("; ").some((cookie) => cookie.startsWith(name + "="));
    }

    if (getCookie("cookieConsent")) {
        cookieBox.style.display = "none";
        cookieOverlay.style.display = "none";
    }

    acceptButton.addEventListener("click", function () {
        fetch("cookie.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "acceptCookies=true"
        })
            .then(response => response.text())
            .then(data => {
                if (data === "success") {
                    cookieBox.style.display = "none";
                    cookieOverlay.style.display = "none";
                }
            });
    });
});

function toggleTerms() {
    const termsBox = document.getElementById("termsBox");
    termsBox.style.display = termsBox.style.display === "none" ? "block" : "none";
}