<?php 

$conn = require __DIR__ . "/database.php";


if(isset($_GET['exception_id']))
{

    $exception_id = mysqli_real_escape_string($conn, $_GET['exception_id']);

    $query = "SELECT * FROM exception WHERE id='$exception_id'";
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) == 1)
    {
        $exception = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'החריגה נשלפה בהצלחה דרך מ.ז',
            'data' => $exception
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'מ.ז החריגה לא נמצא'
        ];
        echo json_encode($res);
        return;
    }

}

if(isset($_POST['update_exception']))
{
    $exception_id = mysqli_real_escape_string($conn, $_POST['exception_id']);

   
    $name = mysqli_real_escape_string($conn, $_POST['projectName']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
   
    if($name == NULL || $description == NULL || $price == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    } else if (!is_numeric($price)) {
        $res = [
            'status' => 422,
            'message' => 'המחיר חייב להיות מספר'
        ];
        echo json_encode($res);
        return;
    } 

    $query = "UPDATE exception SET projectName='$name', description='$description', price='$price'
                WHERE id='$exception_id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'החריגה עודכנה בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'החריגה לא עודכנה'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_POST['delete_exception']))
{
    $exception_id = mysqli_real_escape_string($conn, $_POST['exception_id']);

    $query = "DELETE FROM exception WHERE id='$exception_id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'החריגה נמחקה בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'החריגה לא נמחקה'
        ];
        echo json_encode($res);
        return;
    }
}







