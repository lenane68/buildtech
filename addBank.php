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

if (isset($_POST['add_account'])) {
    $accountNumber = mysqli_real_escape_string($conn, $_POST['accountNumber']);

    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $bank = mysqli_real_escape_string($conn, $_POST['bank']);
    $owner = mysqli_real_escape_string($conn, $_POST['owner']);
    $gold = mysqli_real_escape_string($conn, $_POST['gold']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Check if $gold is not set or empty, and set it to an empty string
    if (!isset($gold) || empty($gold)) {
        $gold = "";
    }

    // Check if $address is not set or empty, and set it to an empty string
    if (!isset($address) || empty($address)) {
        $address = "";
    }

    $stmt = $conn->prepare("insert into bankaccount(accountNumber, branchNumber, bankName, owner, goldNumber, address) values(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $accountNumber, $branch, $bank, $owner, $gold, $address);

    try {
        $execval = $stmt->execute();
        if ($execval) {
            $res = [
                'status' => 200,
                'message' => ' החשבון הוקלט בהצלחה'
            ];
            echo json_encode($res);
            return;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) { // Error code for duplicate entry
            $res = [
                'status' => 500,
                'message' =>  "כנראה שהחשבון כבר קיים במערכת"
            ];
            echo json_encode($res);
            return;
        } else {
            $res = [
                'status' => 500,
                'message' => 'החשבון לא הוקלט'
            ];
            echo json_encode($res);
            return;
        }
    }

    echo $execval;
    $stmt->close();
    $conn->close();
}
