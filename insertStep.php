<?php

$conn = require __DIR__ . "/database.php";


if (isset($_POST['insert_step'])) {
    $projectid = mysqli_real_escape_string($conn, $_POST['projectid2']);


    $projectsPercent = mysqli_real_escape_string($conn, $_POST['projectsPercent']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);


    if ($projectsPercent == NULL || $description == NULL) {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    }

    $payment = 'לא שולם';
    $finish = 'לא בוצע';
    $paymentPercent = 0.0;

    $stmt = $conn->prepare("insert into projectstep(projectId, projectsPercent, description, payment, finish, paymentPercent) values(?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("idsssd", $projectid, $projectsPercent, $description, $payment, $finish, $paymentPercent);

    $execval = $stmt->execute();

    if ($execval) {
        $res = [
            'status' => 200,
            'message' => ' השלב הוקלט בהצלחה'
        ];
        echo json_encode($res);


        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'השלב לא הוקלט'
        ];
        echo json_encode($res);
        return;
    }
    echo $execval;
    $stmt->close();
    $conn->close();
}
