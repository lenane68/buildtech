<?php

$conn = require __DIR__ . "/database.php";

if (isset($_POST['project_id'])) {
    $project_id = $_POST['project_id'];

    // Execute a DELETE query to remove the project from the database
    $delete_query = "DELETE FROM project WHERE id = $project_id";

    if (mysqli_query($conn, $delete_query)) {
        // Project deleted successfully
        echo "success";
    } else {
        // Error occurred while deleting the project
        echo "Error: " . mysqli_error($conn);
    }
}
