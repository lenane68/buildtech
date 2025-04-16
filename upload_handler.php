<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html'); // Ensure HTML response

$conn = require __DIR__ . "/database.php";
session_start();

if (!isset($_SESSION["email"])) {
    echo "<p style='color: red;'>Error: User not authenticated</p>";
    exit();
}

$email = mysqli_real_escape_string($conn, $_SESSION['email']);
$query = "SELECT * FROM account WHERE email='$email'";
$result = mysqli_query($conn, $query);

if (!$row = mysqli_fetch_assoc($result)) {
    echo "<p style='color: red;'>Error: Account not found</p>";
    exit();
}

if (!isset($_SESSION['project_id'])) {
    echo "<p style='color: red;'>Error: Project ID not found in session.</p>";
    exit();
}

$projectDir = __DIR__;
$uploadDir = $projectDir . '/img/safety/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$id = $_SESSION['project_id'];

function generateImageName($uploadDir) {
    $counter = 1;
    do {
        $newName = "img{$counter}.jpg";
        $filePath = $uploadDir . $newName;
        $counter++;
    } while (file_exists($filePath));
    return $newName;
}

if (isset($_FILES['images']) && is_array($_FILES['images']['tmp_name']) && !empty($_FILES['images']['tmp_name'][0])) {
    $errors = [];

    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) {
            $errors[] = "File {$key} error code: " . $_FILES['images']['error'][$key];
            continue;
        }

        $newFileName = generateImageName($uploadDir);
        $targetPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($tmpName, $targetPath)) {
            $errors[] = "Failed to move file: " . $tmpName;
        } else {
            $relativePath = 'img/safety/' . $newFileName;
            $stmt = $conn->prepare("INSERT INTO project_images (project_id, image_path, status) VALUES (?, ?, ?)");
$status = 'not_analyzed';
$stmt->bind_param("iss", $id, $relativePath, $status);


            if (!$stmt->execute()) {
                $errors[] = "Database error while inserting file: " . $newFileName;
            }
        }
    }

    if (empty($errors)) {
        echo "<p style='color: green;'>התמונות נשמרו בהצלחה!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . implode('<br>', $errors) . "</p>";
    }
} else {
    echo "<p style='color: red;'>Error: No images uploaded.</p>";
}

$conn->close();
?>
