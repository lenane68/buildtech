<?php

// Database connection
$conn = new mysqli('localhost','buildtechroot','Nrmeen123','buildtech');
if($conn->connect_error){
    echo "$conn->connect_error";
    die("Connection Failed : ". $conn->connect_error);
} 

return $conn;