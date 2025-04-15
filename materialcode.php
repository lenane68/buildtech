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




if (isset($_GET['material_id'])) {

    $material_id = mysqli_real_escape_string($conn, $_GET['material_id']);

    $query = "SELECT * FROM material WHERE id='$material_id'";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) == 1) {
        $material = mysqli_fetch_array($query_run);
        $filtered_material = [];
        $keys_to_keep = ['id', 'name', 'price', 'amount', 'metrics'];
        foreach ($material as $key => $value) {
            if (in_array($key, $keys_to_keep)) {
                $filtered_material[$key] = $value;
            }
        }

        $res = [
            'status' => 200,
            'message' => 'החומר נשלף בהצלחה דרך מ.ז',
            'data' => $filtered_material
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'מ.ז החומר לא נמצא'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['material_id'])) {
    $material_id = mysqli_real_escape_string($conn, $_POST['material_id']);


    $name = mysqli_real_escape_string($conn, $_POST['materialName']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $metrics = mysqli_real_escape_string($conn, $_POST['metrics']);

    if ($name == NULL || $price == NULL || $amount == NULL || $metrics == NULL) {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE material SET name='$name', price='$price', amount='$amount', metrics='$metrics'
                WHERE id='$material_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'החומר עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'החומר לא עודכן'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['delete_material'])) {
    $material_id = mysqli_real_escape_string($conn, $_POST['material_id']);

    $query = "DELETE FROM material WHERE id='$material_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'החומר נמחק בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'החומר לא נמחק'
        ];
        echo json_encode($res);
        return;
    }
}
