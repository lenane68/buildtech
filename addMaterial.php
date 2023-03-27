<?php
    if (empty($_POST["name"])) {
        die("name is required");
    }
    if (empty($_POST["price"])) {
        die("price is required");
    }
    if (empty($_POST["amount"])) {
        die("amount is required");
    }
    if (empty($_POST["metrics"])) {
        die("metrics is required");
    }



   

    $name = $_POST['name'];
	$price = $_POST['price'];
    $amount =  $_POST['amount'];
    $metrics = $_POST['metrics'];
   
 
 
	
    $conn = require __DIR__ . "/database.php";

    $stmt = $conn->prepare("insert into material(name, price, amount, metrics) values(?, ?, ?, ?)");
    $stmt->bind_param("siis", $name, $price, $amount, $metrics);
    $execval = $stmt->execute();
    if($execval){
        echo "Adding successfully...";
    } else {
        die($conn->error . " " . $conn->errno);
    }
    echo $execval;
    $stmt->close();
    $conn->close();

  



