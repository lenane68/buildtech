<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (isset($_SESSION["email"])) {
    header('location: home.php');
}

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

            $_SESSION["email"] = $email;

            if (isset($_POST["remember_me"])) {
                // Set a cookie to remember the user (you can customize the expiration time)
                setcookie("user_email", $email, time() + 86400 * 30, "/"); // 30 days expiration
            }
            header("Location: home.php");
            exit;
        } else {
            echo "<script>alert('Wrong password');</script>";
            echo "<noscript>Wrong password</noscript>";
            // Invalid password; redirect with an error message
            //header("Location: index.php?error=password");
            exit;
        }
    } else {
        echo "<script>alert('unvalid email');</script>";
        echo "<noscript>unvalid email</noscript>";

        // Invalid email; redirect with an error message
        //header("Location: index.php?error=email");
        exit;
    }
}
