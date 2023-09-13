<?php

$conn = require __DIR__ . "/database.php";


if (isset($_GET['serialNumber'])) {

    $serialNumber = mysqli_real_escape_string($conn, $_GET['serialNumber']);

    $query = "SELECT * FROM fixing WHERE serialNumber='$serialNumber'";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) == 1) {
        $fixing = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'הטיפול נשלף בהצלחה דרך מ.ז',
            'data' => $fixing
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'מ.ז הטיפול לא נמצא'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['update_fixing'])) {
    $serialNumber = mysqli_real_escape_string($conn, $_POST['serialNumber']);


    $carNumber = mysqli_real_escape_string($conn, $_POST['carNumber']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $fixingDetails = mysqli_real_escape_string($conn, $_POST['fixingDetails']);
    $fixingDate = mysqli_real_escape_string($conn, $_POST['fixingDate']);

    if ($carNumber == NULL || $price == NULL || $fixingDetails == NULL || $fixingDate == NULL) {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
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

    $query = "UPDATE fixing SET carNumber='$carNumber', price='$price', fixingDetails='$fixingDetails', fixingDate='$fixingDate'
                WHERE serialNumber='$serialNumber'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'הטיפול עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'הטיפול לא עודכן'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['delete_fixing'])) {
    $serialNumber = mysqli_real_escape_string($conn, $_POST['serialNumber']);

    $query = "DELETE FROM fixing WHERE serialNumber='$serialNumber'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'הטיפול נמחק בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'הטיפול לא נמחק'
        ];
        echo json_encode($res);
        return;
    }
}
