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
