<?php
// Database connection
$conn = require __DIR__ . "/database.php";
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Handle image upload (if images are posted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
    $uploaded_images = $_FILES['images'];
    $status = 'to-do'; // Set the image status as 'to-do'
    $success = true;
    $message = '';

    foreach ($uploaded_images['name'] as $index => $file_name) {
        $file_tmp = $uploaded_images['tmp_name'][$index];
        $file_error = $uploaded_images['error'][$index];
        
        if ($file_error === UPLOAD_ERR_OK) {
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif']; // Add more file types if needed
            
            if (in_array($file_ext, $allowed_exts)) {
                $file_new_name = uniqid() . '.' . $file_ext;
                $upload_path = 'uploads/' . $file_new_name;
                
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    // Save image info to the database with 'to-do' status
                    $stmt = $conn->prepare("INSERT INTO images (file_name, status) VALUES (?, ?)");
                    $stmt->bind_param("ss", $file_new_name, $status);
                    if (!$stmt->execute()) {
                        $success = false;
                        $message = 'Error saving to the database.';
                        break;
                    }
                } else {
                    $success = false;
                    $message = 'Error moving the uploaded file.';
                    break;
                }
            } else {
                $success = false;
                $message = 'Invalid file type.';
                break;
            }
        } else {
            $success = false;
            $message = 'Error uploading file.';
            break;
        }
    }
    
    // Redirect back to project.php with the success or error message
    $message = $message ?: 'Files uploaded successfully.';
    header("Location: project.php?status=" . ($success ? "success" : "error") . "&message=" . urlencode($message));
    exit;
}

// Check for images with "to-do" status
$sql = "SELECT * FROM images WHERE status = 'to-do'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    // Process each image (e.g., change status to 'processed' or any other task)
    // For demonstration, we just update the status to 'processed'
    
    $stmt = $conn->prepare("UPDATE images SET status = 'processed' WHERE id = ?");
    $stmt->bind_param("i", $row['id']);
    $stmt->execute();
}

// Close the database connection
$conn->close();
?>
