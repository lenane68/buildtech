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




if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "SELECT * FROM checks WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) == 1) {
        $check = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'הציק נשלף בהצלחה דרך המספר',
            'data' => $check
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'מספר הציק לא נמצא'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['update_check'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);


    $forName = mysqli_real_escape_string($conn, $_POST['forName']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $checkDate = mysqli_real_escape_string($conn, $_POST['checkDate']);

    if ($id == NULL || $forName == NULL || $price == NULL || $checkDate == NULL) {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    } else if (!is_numeric($price)) {
        $res = [
            'status' => 422,
            'message' => ' הסכום חייב להיות מספר'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE checks SET forName='$forName', price='$price', checkDate='$checkDate'
                WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'הציק עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'הציק לא עודכן'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['delete_check'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $query = "DELETE FROM checks WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'הציק נמחק בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'הציק לא נמחק'
        ];
        echo json_encode($res);
        return;
    }
}
