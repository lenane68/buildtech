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




if (isset($_GET['account_number'])) {

    $account_number = mysqli_real_escape_string($conn, $_GET['account_number']);

    $query = "SELECT * FROM bankaccount WHERE accountNumber='$account_number'";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) == 1) {
        $account = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'החשבון נשלף בהצלחה דרך השם',
            'data' => $account
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'מס החשבון לא נמצא'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['update_bank'])) {
    $accountNumber = mysqli_real_escape_string($conn, $_POST['accountNumber']);


    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $bank = mysqli_real_escape_string($conn, $_POST['bank']);
    $owner = mysqli_real_escape_string($conn, $_POST['owner']);
    $gold = mysqli_real_escape_string($conn, $_POST['gold']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    if ($accountNumber == NULL || $branch == NULL || $bank == NULL || $owner == NULL) {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE bankaccount SET accountNumber='$accountNumber', branchNumber='$branch', bankName='$bank', owner='$owner', goldNumber='$gold', address='$address'
                WHERE accountNumber='$accountNumber'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'החשבון עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'החשבון לא עודכן'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['delete_account'])) {
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);

    $query = "DELETE FROM bankaccount WHERE accountNumber='$account_number'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'החשבון נמחק בהצלחה'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'החשבון לא נמחק'
        ];
        echo json_encode($res);
        return;
    }
}
