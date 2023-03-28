<?php
    if (empty($_POST["fullName"])) {
        die("fullName is required");
    }
    if (empty($_POST["address"])) {
        die("address is required");
    }
    if (empty($_POST["id"])) {
        die("id is required");
    }
    if (empty($_POST["phoneNumber"])) {
        die("phoneNumber is required");
    }
    if (empty($_POST["email"])) {
        die("email is required");
    }


    //$password_hash = password_hash($_POST["floatingPassword"], PASSWORD_DEFAULT);

    $fullName = $_POST['fullName'];
	$adress = $_POST['address'];
    $id =  $_POST['id'];
    $phoneNumber = $_POST['phoneNumber'];
    $phoneNumber2 = $_POST['phoneNumber2'];
    $email = $_POST['email'];
 
 
	
    $conn = require __DIR__ . "/database.php";

    $stmt = $conn->prepare("insert into supplier(name, address, id, email, phone, phone2) values(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fullName, $adress, $id, $email, $phoneNumber, $phoneNumber2);
    $execval = $stmt->execute();
    if($execval){
        echo "Adding successfully...";
    } else {
        die($conn->error . " " . $conn->errno);
    }
    echo $execval;
    $stmt->close();
    $conn->close();

  



