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





if (isset($_POST['insert_payment'])) {
    $projectstepid = $_POST['projectstepid'];


    $payment =  $_POST['payment'];
    $paymentPercent = $_POST['paymentPercent'];


    if ($payment == NULL || $paymentPercent == NULL) {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    }


    $query = "UPDATE projectstep SET payment='$payment', paymentPercent='$paymentPercent'
    WHERE id='$projectstepid'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'סטטוס התשלום עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'סטטוס התשלום לא עודכן'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_GET['projectid'])) {

    $projectid = mysqli_real_escape_string($conn, $_GET['projectid']);

    $query = "SELECT * FROM projectstep WHERE id='$projectid'";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) == 1) {
        $step = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'השלב נשלף בהצלחה דרך השם',
            'data' => $step
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'מס השלב לא נמצא'
        ];
        echo json_encode($res);
        return;
    }
}
