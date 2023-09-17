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

$response = array('success' => false);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["file_id"])) {
    $fileId = $_POST["file_id"];

    // Retrieve file information from the database
    $getFileQuery = "SELECT * FROM files WHERE id = '$fileId'";
    $fileResult = mysqli_query($conn, $getFileQuery);

    if (mysqli_num_rows($fileResult) > 0) {
        $file = mysqli_fetch_assoc($fileResult);

        // Delete the physical file from the server
        if (unlink($file['filename'])) {
            // Delete the file record from the database
            $deleteFileQuery = "DELETE FROM files WHERE id = '$fileId'";
            $deleteResult = mysqli_query($conn, $deleteFileQuery);

            if ($deleteResult) {
                $response['success'] = true;
            }
        }
    }
}

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response);
