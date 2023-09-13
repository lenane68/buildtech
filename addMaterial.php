<?php
/*
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the uploaded file
    $image = $_FILES["img"]["tmp_name"];

    // Check if the file was uploaded successfully
    if ($image) {
        // Read the image file
        $imgData = file_get_contents($image);

        // Check if the connection is successful
        if ($conn) {
            // Prepare the SQL statement
            $stmt = mysqli_prepare($conn, "INSERT INTO material (img) VALUES (?)");

            // Bind the image data to the prepared statement
            mysqli_stmt_bind_param($stmt, "s", $imgData);

            // Execute the statement
            mysqli_stmt_execute($stmt);

            // Check if the insertion was successful
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "Image uploaded and inserted into the database.";
            } else {
                echo "Failed to insert image into the database.";
            }

            // Close the statement and connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        } else {
            echo "Failed to connect to the database.";
        }
    } else {
        echo "Failed to upload the image.";
    }
}



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
    $conn->close();*/


    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Check if the form is submitted
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $name = $_POST["name"];
    $price = $_POST["price"];
    $amount = $_POST["amount"];
    $metrics = $_POST["metrics"];
    $image = $_FILES["img"]["tmp_name"];

    // Check if all required fields are filled
    if ($name && $price && $amount && $metrics && $image) {
        // Read the image file
        $imgData = file_get_contents($image);

        $conn = require __DIR__ . "/database.php";

        // Check if the connection is successful
        if ($conn) {
            // Prepare the SQL statement
            $stmt = mysqli_prepare($conn, "INSERT INTO material (name, price, amount, metrics, img) VALUES (?, ?, ?, ?, ?)");

            // Bind the form data to the prepared statement
            mysqli_stmt_bind_param($stmt, "sssss", $name, $price, $amount, $metrics, $imgData);

            // Execute the statement
            mysqli_stmt_execute($stmt);

            // Check if the insertion was successful
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "Data inserted into the 'material' table.";
            } else {
                echo "Failed to insert data into the 'material' table.";
            }

            // Close the statement and connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        } else {
            echo "Failed to connect to the database.";
        }
    } else {
        echo "Please fill in all required fields.";
    }
}
