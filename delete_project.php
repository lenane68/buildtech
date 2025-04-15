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



if (isset($_POST['project_id'])) {
    $project_id = $_POST['project_id'];

    // Execute a DELETE query to remove the project from the database
    $delete_query = "DELETE FROM project WHERE id = $project_id";

    if (mysqli_query($conn, $delete_query)) {
        // Project deleted successfully
        echo "success";
    } else {
        // Error occurred while deleting the project
        echo "Error: " . mysqli_error($conn);
    }
}
