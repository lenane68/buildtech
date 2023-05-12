<?php 

$conn = require __DIR__ . "/database.php";

if(isset($_POST['insert_payment']))
{
    $projectid = mysqli_real_escape_string($conn, $_POST['projectid']);

    
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $paymentDate = mysqli_real_escape_string($conn, $_POST['paymentDate']);
   
    if($price == NULL || $details == NULL ||  $paymentDate == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    }

    $stmt = $conn->prepare("insert into payments(projectNumber, price, date, forWhat) values(?, ?, ?, ?)");
    $stmt->bind_param("idss", $projectid, $price, $paymentDate, $details);

    $execval = $stmt->execute();
    if($execval)
    {
        $res = [
            'status' => 200,
            'message' => ' התשלום הוקלט בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'התשלום לא הוקלט'
        ];
        echo json_encode($res);
        return;
    }
    echo $execval;
    $stmt->close();
    $conn->close();
    
}