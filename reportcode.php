<?php 

$conn = require __DIR__ . "/database.php";


if(isset($_GET['reportNumber']))
{

    $reportNumber = mysqli_real_escape_string($conn, $_GET['reportNumber']);

    $query = "SELECT * FROM report WHERE reportNumber='$reportNumber'";
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) == 1)
    {
        $report = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'הדו"ח נשלף בהצלחה דרך מ.ז',
            'data' => $report
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'מ.ז הדו"ח לא נמצא'
        ];
        echo json_encode($res);
        return;
    }

}

if(isset($_POST['update_report']))
{
    $reportNumber = mysqli_real_escape_string($conn, $_POST['reportNumber']);

    $carNumber = mysqli_real_escape_string($conn, $_POST['carNumber']);
    $reportDate = mysqli_real_escape_string($conn, $_POST['reportDate']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $paid = isset($_POST['paid']) ? 1 : 0;
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
  
    if($carNumber == NULL || $reportDate == NULL || $price == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE report SET carNumber='$carNumber', reportDate='$reportDate', price='$price', paid='$paid', notes='$notes'
                WHERE reportNumber='$reportNumber'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'הדו"ח עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'הדו"ח לא עודכן'
        ];
        echo json_encode($res);
        return;
    }
}



if(isset($_POST['delete_report']))
{
    $reportNumber = mysqli_real_escape_string($conn, $_POST['reportNumber']);

    $query = "DELETE FROM report WHERE reportNumber='$reportNumber'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'הדו"ח נמחק בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'הדו"ח לא נמחק'
        ];
        echo json_encode($res);
        return;
    }
}







