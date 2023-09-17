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

if (isset($_POST['add_income'])) {

    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $projectId = mysqli_real_escape_string($conn, $_POST['projectId']);

    if ($category == "פרויקטים") {
        if ($projectId == (-1)) {
            $res = [
                'status' => 500,
                'message' => 'צריך לבחור שם פרויקט'
            ];
            echo json_encode($res);
            return;
        }
    }

    $stmt = $conn->prepare("insert into income(price, date, details, category, projectId) values(?, ?, ?, ?, ?)");
    $stmt->bind_param("dsssi", $price, $date, $details, $category, $projectId);

    try {
        $execval = $stmt->execute();
        if ($execval) {
            $res = [
                'status' => 200,
                'message' => ' ההכנסה הוקלטה בהצלחה'
            ];
            echo json_encode($res);
            return;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) { // Error code for duplicate entry
            $res = [
                'status' => 500,
                'message' =>  "כנראה שההכנסה כבר קיימת במערכת"
            ];
            echo json_encode($res);
            return;
        } else {
            $res = [
                'status' => 500,
                'message' => 'ההכנסה לא הוקלטה'
            ];
            echo json_encode($res);
            return;
        }
    }

    echo $execval;
    $stmt->close();
    $conn->close();
}
