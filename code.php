<?php 

$conn = require __DIR__ . "/database.php";


if(isset($_GET['client_name']))
{

    $client_name = mysqli_real_escape_string($conn, $_GET['client_name']);

    $query = "SELECT * FROM client WHERE fullName='$client_name'";
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) == 1)
    {
        $client = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'הלקוח נשלף בהצלחה דרך השם',
            'data' => $client
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'שם הלקוח לא נמצא'
        ];
        echo json_encode($res);
        return;
    }

}

if(isset($_POST['update_client']))
{
    $clientName = mysqli_real_escape_string($conn, $_POST['clientName']);

   
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $phone2 = mysqli_real_escape_string($conn, $_POST['phone2']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if($clientName == NULL || $address == NULL || $id == NULL || $phone == NULL || $email == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE client SET fullName='$clientName', address='$address', id='$id', gender='$gender', phone='$phone', phone2='$phone2', email='$email'
                WHERE fullName='$clientName'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'הלקוח עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'הלקוח לא עודכן'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_POST['delete_client']))
{
    $client_name = mysqli_real_escape_string($conn, $_POST['client_name']);

    $query = "DELETE FROM client WHERE fullName='$client_name'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'הלקוח נמחק בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'הלקוח לא נמחק'
        ];
        echo json_encode($res);
        return;
    }
}







