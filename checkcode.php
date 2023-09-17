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

$sqli_notify = "SELECT * FROM notification WHERE DATE(date) >= (DATE(NOW()) - INTERVAL 90 DAY) ORDER BY date DESC LIMIT 5";

$result_notify = $conn->query($sqli_notify);

$query_notify1 = "SELECT * FROM car where testDate <= (DATE(NOW()) + INTERVAL 30 DAY) and testDate >= DATE(NOW())";
$query_notify2 = "SELECT * FROM checks where checkDate <= (DATE(NOW()) + INTERVAL 30 DAY) and checkDate >= DATE(NOW())";

$result_car = $conn->query($query_notify1);
$result_checks = $conn->query($query_notify2);

if ($result_car->num_rows > 0) {
    while ($row_car = $result_car->fetch_assoc()) {
        $stmt = $conn->prepare("INSERT INTO notification (id, title, full_message) VALUES (?, ?, ?)");

        $id = (int) $row_car["number"];
        $title = "טסט רכב";
        $full_message = "תאריך סיום הטסט ברכב שמספרו : " . $row_car["number"] . " הוא : " . $row_car["testDate"];

        $stmt->bind_param("iss", $id, $title, $full_message);

        $stmt->execute();
    }
}

if ($result_checks->num_rows > 0) {
    while ($row_checks = $result_checks->fetch_assoc()) {
        $stmt = $conn->prepare("INSERT INTO notification (id, title, full_message) VALUES (?, ?, ?)");

        $id = (int) $row_checks["id"];
        $title = "פרעון צק";
        $full_message = "התאריך לפירעון הצק שמספרו : " . $row_checks["id"] . " הוא : " . $row_car["checkDate"];

        $stmt->bind_param("iss", $id, $title, $full_message);

        $stmt->execute();
    }
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
