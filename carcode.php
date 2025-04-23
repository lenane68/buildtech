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




if (isset($_GET['car_number'])) {

    $car_number = mysqli_real_escape_string($conn, $_GET['car_number']);

    $query = "SELECT * FROM car WHERE number='$car_number'";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) == 1) {
        $car = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'הרכב/ הכלי נשלף בהצלחה דרך מספרו',
            'data' => $car
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'מ.ז הרכב לא נמצא'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['update_car'])) {
    $car_number = mysqli_real_escape_string($conn, $_POST['car_number']);


    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $testDate = mysqli_real_escape_string($conn, $_POST['testDate']);
    $insuranceDate = mysqli_real_escape_string($conn, $_POST['insuranceDate']);
    $careDate = mysqli_real_escape_string($conn, $_POST['careDate']);
    $fuelType = mysqli_real_escape_string($conn, $_POST['fuelType']);

    if ($type == NULL || $year == NULL || $color == NULL || $testDate == NULL || $insuranceDate == NULL || $careDate == NULL || $fuelType == NULL) {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    } else if ((!is_numeric($year)) || $year < 1900 || $year > date("Y")) {
        $res = [
            'status' => 422,
            'message' => 'שנת ייצור לא חוקית'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE car SET type='$type', year='$year', color='$color', testDate='$testDate', insuranceDate='$insuranceDate', careDate='$careDate', fuelType='$fuelType'
                WHERE number='$car_number'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'הרכב עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'הרכב לא עודכן'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['delete_car'])) {
    $car_number = mysqli_real_escape_string($conn, $_POST['car_number']);

    $query = "DELETE FROM car WHERE number='$car_number'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'הרכב נמחק בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'הרכב לא נמחק'
        ];
        echo json_encode($res);
        return;
    }
}
