<?php

$conn = require __DIR__ . "/database.php";

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
