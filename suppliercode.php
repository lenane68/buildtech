<?php 

$conn = require __DIR__ . "/database.php";


if(isset($_GET['supplier_id']))
{

    $supplier_id = mysqli_real_escape_string($conn, $_GET['supplier_id']);

    $query = "SELECT * FROM supplier WHERE serialNumber='$supplier_id'";
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) == 1)
    {
        $supplier = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'הספק נשלף בהצלחה דרך מ.ז',
            'data' => $supplier
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'מ.ז הספק לא נמצא'
        ];
        echo json_encode($res);
        return;
    }

}

if(isset($_POST['update_supplier']))
{
    $supplier_id = mysqli_real_escape_string($conn, $_POST['supplier_id']);

   
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['supplierName']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $phone2 = mysqli_real_escape_string($conn, $_POST['phone2']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if($name == NULL || $address == NULL || $id == NULL || $phone == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE supplier SET name='$name', address='$address', id='$id', phone='$phone', phone2='$phone2', email='$email'
                WHERE serialNumber='$supplier_id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'הספק עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'הספק לא עודכן'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_POST['delete_supplier']))
{
    $supplier_id = mysqli_real_escape_string($conn, $_POST['supplier_id']);

    $query = "DELETE FROM supplier WHERE serialNumber='$supplier_id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'הספק נמחק בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'הספק לא נמחק'
        ];
        echo json_encode($res);
        return;
    }
}







