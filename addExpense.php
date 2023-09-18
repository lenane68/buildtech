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



if (isset($_POST['add_expense'])) {

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

    $stmt = $conn->prepare("insert into expense(price, date, details, category, projectId) values(?, ?, ?, ?, ?)");
    $stmt->bind_param("dsssi", $price, $date, $details, $category, $projectId);

    try {
        $execval = $stmt->execute();
        if ($execval) {
            $res = [
                'status' => 200,
                'message' => ' ההוצאה הוקלטה בהצלחה'
            ];
            echo json_encode($res);
            return;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) { // Error code for duplicate entry
            $res = [
                'status' => 500,
                'message' =>  "כנראה שההוצאה כבר קיימת במערכת"
            ];
            echo json_encode($res);
            return;
        } else {
            $res = [
                'status' => 500,
                'message' => 'ההוצאה לא הוקלטה'
            ];
            echo json_encode($res);
            return;
        }
    }

    echo $execval;
    $stmt->close();
    $conn->close();
}
