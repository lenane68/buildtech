<?php
    $conn = require __DIR__ . "/database.php";

    
    if(isset($_GET["submit"])){

        $projectId = mysqli_real_escape_string($conn, $_GET['id']);

        $projectName = $_GET['projectName'];
        $client = $_GET['clientName'];
        $projectAddress = $_GET['address'];
        $start = $_GET['startDate'];

        if($projectName == NULL || $client == NULL || $projectAddress == NULL || $start == NULL)
        {
            $res = [
                'status' => 422,
                'message' => 'שדה חובה ריק'
            ];
            echo json_encode($res);
            return;
        }

        $query = "UPDATE project SET name='$projectName', clientName='$client', address='$projectAddress', startDate='$start'
                        WHERE id='$projectId'";
        $query_run = mysqli_query($conn, $query);
        
        if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'הפרויקט עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'הפרויקט לא עודכן'
        ];
        echo json_encode($res);
        return;
    }

       
    }