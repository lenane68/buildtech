<?php

// Database connection
$conn = new mysqli('db4free.net','buildtechroot','Nrmeen123','buildtech');
if($conn->connect_error){
    echo "$conn->connect_error";
    die("Connection Failed : ". $conn->connect_error);
} 

return $conn;