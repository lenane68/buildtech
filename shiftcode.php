<?php

$conn = require __DIR__ . "/database.php";


if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "SELECT * FROM shift WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) == 1) {
        $report = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'המשמרת נשלפה בהצלחה  ',
            'data' => $report
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'המשמרת לא נמצאת'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['update_shift'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $workDate = mysqli_real_escape_string($conn, $_POST['workDate']);
    $employeeName = mysqli_real_escape_string($conn, $_POST['employeeName']);
    $dayType = mysqli_real_escape_string($conn, $_POST['dayType']);
    $hours = mysqli_real_escape_string($conn, $_POST['hours']);
    $onAccount = mysqli_real_escape_string($conn, $_POST['onAccount']);

    if ($workDate == NULL || $employeeName == NULL || $dayType == NULL) {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    } else if (!is_numeric($hours) && ($hours != null)) {
        $res = [
            'status' => 422,
            'message' => ' השעות חייבות להיות מספר'
        ];
        echo json_encode($res);
        return;
    } else if (!is_numeric($onAccount) && ($onAccount != null)) {
        $res = [
            'status' => 422,
            'message' => ' על החשבון חייב להיות מספר'
        ];
        echo json_encode($res);
        return;
    } else if (($dayType == 'רק שעות') && ($hours == null)) {
        $res = [
            'status' => 422,
            'message' => 'צריך להזין כמה שעות עבד'
        ];
        echo json_encode($res);
        return;
    } else if (($dayType == 'לא עבד') && ($onAccount == null)) {
        $res = [
            'status' => 422,
            'message' => 'צריך להזין כמה לקח על החשבון'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE shift SET workDate='$workDate', employeeName='$employeeName', dayType='$dayType', `hours`='$hours', onAccount='$onAccount'
                WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'המשמרת עודכנה בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'המשמרת לא עודכנה'
        ];
        echo json_encode($res);
        return;
    }
}



if (isset($_POST['delete_shift'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $query = "DELETE FROM shift WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'המשמרת נמחקה בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'המשמרת לא נמחקה'
        ];
        echo json_encode($res);
        return;
    }
}
