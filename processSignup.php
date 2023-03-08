<?php
    if (empty($_POST["userName"])) {
        die("Name is required");
    }
    
    if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        die("Valid email is required");
    }
    
    if (strlen($_POST["floatingPassword"]) < 8) {
        die("Password must be at least 8 characters");
    }
    
    if ( ! preg_match("/[a-z]/i", $_POST["floatingPassword"])) {
        die("Password must contain at least one letter");
    }
    
    if ( ! preg_match("/[0-9]/", $_POST["floatingPassword"])) {
        die("Password must contain at least one number");
    }
    
    if ($_POST["floatingPassword"] !== $_POST["passwordConfirmation"]) {
        die("Passwords must match");
    }

    //$password_hash = password_hash($_POST["floatingPassword"], PASSWORD_DEFAULT);

    $userName = $_POST['userName'];
	$email = $_POST['email'];
    $password = $_POST['floatingPassword'];
	
    $conn = require __DIR__ . "/database.php";

    $stmt = $conn->prepare("insert into account(userName, email, password) values(?, ?, ?)");
    $stmt->bind_param("sss", $userName, $email, $password);
    $execval = $stmt->execute();
    if($execval){
        echo "Registration successfully...";
    } else {
        if ($conn->errno === 1062) {
            die("email already taken");
        } else {
            die($conn->error . " " . $conn->errno);
        }
    }
    echo $execval;
    $stmt->close();
    $conn->close();

  



