<?php
// Connect to the database
$conn = require __DIR__ . "/database.php";

if (isset($_POST['deleteStepId'])) {
    $deleteStepId = $_POST['deleteStepId'];

     // Fetch the paymentPercent for the step
     $paymentPercentQuery = "SELECT paymentPercent FROM projectstep WHERE id='$deleteStepId'";
     $paymentPercentResult = mysqli_query($conn, $paymentPercentQuery);
 
     if ($paymentPercentResult) {
         $row = mysqli_fetch_assoc($paymentPercentResult);
         $paymentPercent = $row['paymentPercent'];

         if ($paymentPercent == 0) {
             // Perform the deletion query
            $deleteQuery = "DELETE FROM projectstep WHERE id='$deleteStepId'";
            if (mysqli_query($conn, $deleteQuery)) {
                $response = array('status' => 200, 'message' => 'השלב נמחק בהצלחה');
            } else {
                $response = array('status' => 500, 'message' => 'שגיאה במחיקת השלב');
            } 
         } else {
            $response = array('status' => 422, 'message' => 'אי אפשר למחוק שלב שיש עבורו תשלום קיים');
         }
     }  else {
        $response = array('status' => 500, 'message' => 'שגיאה בשליפת אחוז תשלום');
    }

   

     echo json_encode($response);
}
