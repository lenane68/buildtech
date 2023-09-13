<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = require __DIR__ . "/database.php";

    $stmt = $conn->prepare("SELECT * FROM account WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt_result = $stmt->get_result();

    if ($stmt_result->num_rows > 0) {
        $data = $stmt_result->fetch_assoc();
        echo $password;
        echo $data['password'];
        $password_hash = $data['password'];
        if ($password_hash === $password) {
            if (isset($_POST["remember_me"])) {
                // Set a cookie to remember the user (you can customize the expiration time)
                setcookie("user_email", $email, time() + 86400 * 30, "/"); // 30 days expiration
            }
            header("Location: home.php");
            exit;
        } else {
            // Invalid password; redirect with an error message
            header("Location: index.html?error=password");
            exit;
        }
    } else {
        // Invalid email; redirect with an error message
        header("Location: index.html?error=email");
        exit;
    }
}
