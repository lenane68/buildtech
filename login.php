<?php

   $email = $_POST['email'];
   $password = $_POST['password'];

   $conn = require __DIR__ . "/database.php";

   $stmt = $conn->prepare("select * from account where email = ?");
   $stmt->bind_param("s", $email);
   $stmt->execute();
   $stmt_result = $stmt->get_result();
   if($stmt_result->num_rows > 0){
    $data = $stmt_result->fetch_assoc();
    echo $password;
    echo $data['password'];
    $password_hash = $data['password'];
    if ($password_hash === $password){
        header("Location: home.html");
        exit;
    }
   } else{
    echo "Invalid email";
   }
