<?php
$conn = require __DIR__ . "/database.php";

session_start();

if (!isset($_SESSION["email"])) {
    header('Location: index.php');
    exit();
}

// Update all unseen notifications as seen
$updateQuery = "UPDATE notification SET seen = 1 WHERE seen = 0";
$conn->query($updateQuery);
