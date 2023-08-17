<?php

$conn = require __DIR__ . "/database.php";

if (isset($_POST['updateStepId']) && isset($_POST['newFinish'])) {
    $updateStepId = $_POST['updateStepId'];
    $newFinish = $_POST['newFinish'];

    // Perform the update query
    $updateQuery = "UPDATE projectstep SET finish='$newFinish' WHERE id='$updateStepId'";
    if (mysqli_query($conn, $updateQuery)) {
        $updateResponse = array('status' => 200, 'message' => 'סטטוס העבודה עודכן בהצלחה');
    } else {
        $updateResponse = array('status' => 500, 'message' => 'שגיאה בעדכון הסטטוס');
    }

    echo json_encode($updateResponse);
}
?>
