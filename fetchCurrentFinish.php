<?php

$conn = require __DIR__ . "/database.php";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['stepId'])) {
    $stepId = $_POST['stepId'];

    // Fetch the currentFinish value from the MySQL table
    $query = "SELECT finish FROM projectstep WHERE id = '$stepId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $currentFinish = $row['finish'];
        echo $currentFinish; // Return the currentFinish value as the response
    } else {
        echo "Error fetching currentFinish";
    }
} else {
    echo "Invalid request";
}

mysqli_close($conn);
?>
