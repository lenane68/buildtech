<?php 

$conn = require __DIR__ . "/database.php";


if(isset($_GET['employee_id']))
{

    $employee_id = mysqli_real_escape_string($conn, $_GET['employee_id']);

    $query = "SELECT * FROM employee WHERE id='$employee_id'";
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) == 1)
    {
        $employee = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'העובד נשלף בהצלחה דרך מ.ז',
            'data' => $employee
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'מ.ז העובד לא נמצא'
        ];
        echo json_encode($res);
        return;
    }

}

if(isset($_POST['update_employee']))
{
    
    $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);

    $name = mysqli_real_escape_string($conn, $_POST['employeeName']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $job = mysqli_real_escape_string($conn, $_POST['job']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    //$startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
    //$active = mysqli_real_escape_string($conn, $_POST['active']);
    //$salary = mysqli_real_escape_string($conn, $_POST['salary']);

    //$salary_float_value = floatval($salary);
    //$active1 = ($active == "checked" ? 1 : 0);

    if($name == NULL || $address == NULL || $job == NULL || $gender == NULL || $startDate == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE employee SET fullName='$name', Address='$address', phoneNumber='$phone', job='$job', startDate='2019-07-01', Gender='$gender', Active='FALSE', salary='500'
                WHERE id='$employee_id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'העובד עודכן בהצלחה'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'העובד לא עודכן'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_POST['delete_employee']))
{
    $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);

    $query = "DELETE FROM employee WHERE id='$employee_id'";
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







