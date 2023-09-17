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

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["pdfFile"]) && isset($_POST["projectName"])) {
    $uploadedFile = $_FILES["pdfFile"];
    $projectName = $_POST["projectName"];

    // Check if the file is a PDF
    $fileExtension = strtolower(pathinfo($uploadedFile["name"], PATHINFO_EXTENSION));

    if ($fileExtension === "pdf") {
        $uploadDirectory = "projectPrograms/"; // Specify your upload directory here
        $filename = $uploadDirectory . basename($uploadedFile["name"]);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($uploadedFile["tmp_name"], $filename)) {
            $insertFileQuery = "INSERT INTO files (project_name, filename) VALUES ('$projectName', '$filename')";
            if (mysqli_query($conn, $insertFileQuery)) {
                $response = array('status' => 200, 'message' => 'File inserted successfully');
            } else {
                $response = array('status' => 500, 'message' => 'Error inserting file');
            }
        } else {
            $response = array('status' => 500, 'message' => 'Error uploading file');
        }
    } else {
        $response = array('status' => 422, 'message' => 'Only PDF files are allowed');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
