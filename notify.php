<?php
session_start();
if (!isset($_SESSION["email"])) {
    // Redirect to the login page if the user is not logged in
    header('Location: index.php');
    exit();
}


$conn = require __DIR__ . "/database.php";

$sqli_notify = "SELECT * FROM notification WHERE seen=0 ORDER BY date DESC LIMIT 10";

$result_notify = $conn->query($sqli_notify);

$unread_notification_count = $result_notify->num_rows;


$query_notify1 = "SELECT * FROM car where testDate <= (DATE(NOW()) + INTERVAL 30 DAY) and testDate>= DATE(NOW())";
$query_notify2 = "SELECT * FROM checks where checkDate <= (DATE(NOW()) + INTERVAL 30 DAY) and checkDate>= DATE(NOW())";

$result_car = $conn->query($query_notify1);
$result_checks = $conn->query($query_notify2);

if ($result_car->num_rows > 0) {
    while ($row_car = $result_car->fetch_assoc()) {
        $stmt = $conn->prepare("INSERT INTO notification (relative_num, title, full_message,seen) VALUES (?, ?, ?,?)");

        $id = (int) $row_car["number"];
        $title = "טסט רכב";
        $full_message = "תאריך סיום הטסט ברכב שמספרו : " . $row_car["number"] . " הוא : " . $row_car["testDate"];
        $seen = 0;

        $stmt->bind_param("issi", $id, $title, $full_message, $seen);


        $stmt->execute();
    }
}

if ($result_checks->num_rows > 0) {
    while ($row_checks = $result_checks->fetch_assoc()) {
        $stmt = $conn->prepare("INSERT INTO notification (relative_num, title, full_message,seen) VALUES (?, ?, ?,?)");

        $id = (int) $row_checks["id"];
        $title = "פרעון צק";
        $full_message = "התאריך לפירעון הצק שמספרו : " . $row_checks["id"] . " הוא : " . $row_car["checkDate"];
        $seen = 0;

        $stmt->bind_param("issi", $id, $title, $full_message, $seen);

        $stmt->execute();
    }
}

echo $html_content;
