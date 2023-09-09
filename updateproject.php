<?php
    $conn = require __DIR__ . "/database.php";

    
    if(isset($_POST['update_project'])){

        $projectId = mysqli_real_escape_string($conn, $_POST['id']);
        
        $projectName = mysqli_real_escape_string($conn, $_POST['projectName']);
        $client = mysqli_real_escape_string($conn, $_POST['clientName']);
        $projectAddress = mysqli_real_escape_string($conn, $_POST['address']);
        $start = mysqli_real_escape_string($conn, $_POST['startDate']);
        $finishDate = mysqli_real_escape_string($conn, $_POST['endDate']);

        if($projectName == NULL || $client == NULL || $projectAddress == NULL || $start == NULL)
        {
            $res = [
                'status' => 422,
                'message' => 'שדה חובה ריק'
            ];
            echo json_encode($res);
            return;
        }

        $query = "UPDATE project SET name='$projectName', clientName='$client', address='$projectAddress', startDate='$start', finishDate='$finishDate'
                        WHERE id='$projectId'";
        $query_run = mysqli_query($conn, $query);

        $query2 = "UPDATE data_location SET descr='$projectAddress' WHERE projectName='$projectName'";
        $query_run2 = mysqli_query($conn, $query2);

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