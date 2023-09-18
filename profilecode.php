<?php

$conn = require __DIR__ . "/database.php";

session_start();
if (!isset($_SESSION["email"])) {
    // Redirect to the login page if the user is not logged in
    header('Location: index.php');
    exit();
}
include_once 'notify.php';
$email = mysqli_real_escape_string($conn, $_SESSION['email']);
$query = "SELECT * FROM account WHERE email='$email'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $name = $row['userName'];
    $password = $row['password'];
    $phone = $row['phoneNum'];
    $role = $row['role'];
} else {
    // Handle case where email is not found in the database
    $name = '';
    $password = '';
    $phone = '';
    $role = '';
}


if (isset($_POST['userEmail'])) {

    echo 'hello';

    $name = mysqli_real_escape_string($conn, $_POST['userName']);
    $email = mysqli_real_escape_string($conn, $_POST['userEmail']);
    $password = mysqli_real_escape_string($conn, $_POST['floatingPassword']);
    $phone = mysqli_real_escape_string($conn, $_POST['userPhone']);

    if ($name == NULL || $phone == null || $password == null) {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE user SET full_name='$name', phoneNum='$phone', password='$password'
                WHERE email='$email'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'החומר עודכן בהצלחה'
        ];
        echo 'hello';
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'החומר לא עודכן'
        ];
        echo 'hello';
        echo json_encode($res);
        return;
    }
}
