<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':username' => $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row["password"])) {
        $_SESSION['user'] = $row['username'];
        $_SESSION['profile_picture'] = $row['profile_picture'] ?? 'assets/uploads/default.jpg';
        $_SESSION['age'] = $row['age'];
        $_SESSION['bio'] = $row['bio'] ?? 'No bio yet.';
        header("Location: post.php");
        exit();
    } else {
        $error_message = "Incorrect username or password.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username)){
        echo "Please enter your username";
    } elseif (empty($password)){
        echo "Please enter your password";
    } else {
        $sql = "SELECT user, password, profile_pic, age, bio FROM users WHERE user = '$username'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $hash = $row["password"];

            if (password_verify($password, $hash)) {

                $_SESSION['user'] = $row['user'];
                $_SESSION['profile_pic'] = $row['profile_pic'];
                $_SESSION['age'] = $row['age'];
                $_SESSION['bio'] = $row['bio'];

                echo "Login successful";
                header("location: post.php");
                exit;

            } else {
                echo "<p class = 'loginError'>Incorrect username or password</p>";
            }
        } else {
            echo "<p class = 'loginError'>Incorrect username or password</p>";

        }
    }
}

mysqli_close($conn);

?>
