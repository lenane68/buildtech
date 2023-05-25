<?php 

$conn = require __DIR__ . "/database.php";

if(isset($_POST['add_account']))
{
    $accountNumber = mysqli_real_escape_string($conn, $_POST['accountNumber']);

    
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $bank = mysqli_real_escape_string($conn, $_POST['bank']);
    $owner = mysqli_real_escape_string($conn, $_POST['owner']);
    $gold = mysqli_real_escape_string($conn, $_POST['gold']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
   
    if($accountNumber == NULL || $branch == NULL ||  $owner == NULL ||  $gold == NULL ||  $address == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    }
    
    $stmt = $conn->prepare("insert into bankaccount(accountNumber, branchNumber, bankName, owner, goldNumber, address) values(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $accountNumber, $branch, $bank, $owner, $gold, $address);

    $execval = $stmt->execute();
 

    if($execval)
    {
        $res = [
            'status' => 200,
            'message' => ' החשבון הוקלט בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'החשבון לא הוקלט'
        ];
        echo json_encode($res);
        return;
    }
    echo $execval;
    $stmt->close();
    $conn->close();
    
}