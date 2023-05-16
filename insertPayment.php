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
    $category = 'פרויקטים';

    $stmt2 = $conn->prepare("insert into income(price, date, details, category, projectId) values(?, ?, ?, ?, ?)");
    $stmt2->bind_param("dsssi", $price, $paymentDate, $details, $category,  $projectid);


    $stmt = $conn->prepare("insert into payments(projectNumber, price, date, forWhat) values(?, ?, ?, ?)");
    $stmt->bind_param("idss", $projectid, $price, $paymentDate, $details);

    $execval2 = $stmt2->execute();
    $execval = $stmt->execute();
 

    if($execval && $execval2)
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
    echo $execval2;
    echo $execval;
    $stmt->close();
    $stmt2->close();
    $conn->close();
    
}