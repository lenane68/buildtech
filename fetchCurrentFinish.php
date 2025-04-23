<?php

$conn = require __DIR__ . "/database.php";

session_start();
if (!isset($_SESSION["email"])) {
    // Redirect to the login page if the user is not logged in
    header('Location: index.php');
    exit();
}

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



if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['stepId'])) {
    $stepId = $_POST['stepId'];

    // Fetch the currentFinish value from the MySQL table
    $query = "SELECT finish FROM projectstep WHERE id = '$stepId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $currentFinish = $row['finish'];
        echo $currentFinish; // Return the currentFinish value as the response
    } else {
        echo "Error fetching currentFinish";
    }
} else {
    echo "Invalid request";
}

mysqli_close($conn);
