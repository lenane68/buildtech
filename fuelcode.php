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




if (isset($_GET['serialNumber'])) {

    $serialNumber = mysqli_real_escape_string($conn, $_GET['serialNumber']);

    $query = "SELECT * FROM fuel WHERE serialNumber='$serialNumber'";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) == 1) {
        $fuel = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'הדיווח נשלף בהצלחה דרך מ.ז',
            'data' => $fuel
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'מ.ז הדיווח לא נמצא'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['update_fuel'])) {
    $serialNumber = mysqli_real_escape_string($conn, $_POST['serialNumber']);


    $carNumber = mysqli_real_escape_string($conn, $_POST['carNumber']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $fullDate = mysqli_real_escape_string($conn, $_POST['fullDate']);

    if ($carNumber == NULL || $amount == NULL || $price == NULL || $fullDate == NULL) {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    } else if (!is_numeric($amount)) {
        $res = [
            'status' => 422,
            'message' => ' הכמות חייבת להיות מספר'
        ];
        echo json_encode($res);
        return;
    } else if (!is_numeric($price)) {
        $res = [
            'status' => 422,
            'message' => ' המחיר חייב להיות מספר'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE fuel SET carNumber='$carNumber', amount='$amount', price='$price', fullDate='$fullDate'
                WHERE serialNumber='$serialNumber'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'הדיווח עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'הדיווח לא עודכן'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['delete_fuel'])) {
    $serialNumber = mysqli_real_escape_string($conn, $_POST['serialNumber']);

    $query = "DELETE FROM fuel WHERE serialNumber='$serialNumber'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'הדיווח נמחק בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'הדיווח לא נמחק'
        ];
        echo json_encode($res);
        return;
    }
}
