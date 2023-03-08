<?php
    if (empty($_POST["fullName"])) {
        die("fullName is required");
    }
    if (empty($_POST["adress"])) {
        die("adress is required");
    }
    if (empty($_POST["phoneNumber"])) {
        die("phoneNumber is required");
    }
    if (empty($_POST["job"])) {
        die("job is required");
    }
    if (empty($_POST["startDate"])) {
        die("startDate is required");
    }
    if (empty($_POST["Gender"])) {
        die("Gender is required");
    }


    //$password_hash = password_hash($_POST["floatingPassword"], PASSWORD_DEFAULT);

    $fullName = $_POST['fullName'];
	$adress = $_POST['adress'];
    $phoneNumber = $_POST['phoneNumber'];
    $job = $_POST['job'];
    $startDate = $_POST['startDate'];
    $Gender = $_POST['Gender'];
	
    $conn = require __DIR__ . "/database.php";

    $stmt = $conn->prepare("insert into employee(fullName, Adress, phoneNumber, job, startDate, Gender) values(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fullName, $adress, $phoneNumber, $job, $startDate, $Gender);
    $execval = $stmt->execute();
    if($execval){
        echo "Adding successfully...";
    } else {
        die($conn->error . " " . $conn->errno);
    }
    echo $execval;
    $stmt->close();
    $conn->close();

  



