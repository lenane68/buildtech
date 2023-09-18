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



if (isset($_POST['updateStepId']) && isset($_POST['newFinish'])) {
    $updateStepId = $_POST['updateStepId'];
    $newFinish = $_POST['newFinish'];

    // Perform the update query
    $updateQuery = "UPDATE projectstep SET finish='$newFinish' WHERE id='$updateStepId'";
    if (mysqli_query($conn, $updateQuery)) {
        $updateResponse = array('status' => 200, 'message' => 'סטטוס העבודה עודכן בהצלחה');
    } else {
        $updateResponse = array('status' => 500, 'message' => 'שגיאה בעדכון הסטטוס');
    }

    echo json_encode($updateResponse);
}
