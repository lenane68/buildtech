<?php 

$conn = require __DIR__ . "/database.php";


if(isset($_GET['material_id']))
{

    $material_id = mysqli_real_escape_string($conn, $_GET['material_id']);

    $query = "SELECT * FROM material WHERE id='$material_id'";
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) == 1)
    {
        $material = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'החומר נשלף בהצלחה דרך מ.ז',
            'data' => $material
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'מ.ז החומר לא נמצא'
        ];
        echo json_encode($res);
        return;
    }

}

if(isset($_POST['update_material']))
{
    $material_id = mysqli_real_escape_string($conn, $_POST['material_id']);

   
    $name = mysqli_real_escape_string($conn, $_POST['materialName']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $metrics = mysqli_real_escape_string($conn, $_POST['metrics']);
    $img = mysqli_real_escape_string($conn,$_POST['img']);
   
    if($name == NULL || $price == NULL || $amount == NULL || $metrics == NULL|| $img== NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE material SET name='$name', price='$price', amount='$amount', metrics='$metrics'
                WHERE id='$material_id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'החומר עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'החומר לא עודכן'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_POST['delete_material']))
{
    $material_id = mysqli_real_escape_string($conn, $_POST['material_id']);

    $query = "DELETE FROM material WHERE id='$material_id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'החומר נמחק בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'החומר לא נמחק'
        ];
        echo json_encode($res);
        return;
    }
}







