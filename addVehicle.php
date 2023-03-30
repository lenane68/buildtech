<?php
    if (empty($_POST["number"])) {
        die("number is required");
    }
    if (empty($_POST["type"])) {
        die("type is required");
    }
    if (empty($_POST["year"])) {
        die("year is required");
    }
    if (empty($_POST["color"])) {
        die("color is required");
    }
    if (empty($_POST["testFinishDate"])) {
        die("test finish date is required");
    }
    if (empty($_POST["insuranceFinishDate"])) {
        die("insurance finish date is required");
    }
    if (empty($_POST["careDate"])) {
        die("care date is required");
    }

    //$password_hash = password_hash($_POST["floatingPassword"], PASSWORD_DEFAULT);

    $number = $_POST['number'];
	$type = $_POST['type'];
    $year = $_POST['year'];
    $color = $_POST['color'];
    $testFinishDate = $_POST['testFinishDate'];
    $insuranceFinishDate = $_POST['insuranceFinishDate'];
    $careDate = $_POST['careDate'];
    $fuelType = $_POST['fuelType'];
 
	
    $conn = require __DIR__ . "/database.php";

    $stmt = $conn->prepare("insert into car(number, type, year, color, testDate, insuranceDate, careDate, fuelType) values(?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $number, $type, $year, $color, $testFinishDate, $insuranceFinishDate, $careDate, $fuelType);
    $execval = $stmt->execute();
    if($execval){
        echo "Adding successfully...";
    } else {
        die($conn->error . " " . $conn->errno);
    }
    echo $execval;
    $stmt->close();
    $conn->close();

  



