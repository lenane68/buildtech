<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'database.php';

$projectID = $_SESSION['project_id'];
// File upload and analysis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $targetDir = "img/safety/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check !== false) {
        // Proceed with upload
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            $sql = "INSERT INTO project_images (project_id, image_path) VALUES ('$projectID', '$targetFile')";
            if ($conn->query($sql) === TRUE) {
                // Call Python script for analysis
                $output = analyze_image($targetFile);
                
                // Process the output after analysis
                if (!empty($output)) {
                    // Clean output by removing unnecessary characters
                    $clean_output = preg_replace('/[\x00-\x1F\x7F]/', '', $output);

                    $results = json_decode($clean_output, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $analysisResults = $results;  // Store results for display
                    } else {
                        // Handle JSON decode error
                        $analysisResults = ["error" => "JSON Decode Error: " . json_last_error_msg()];
                    }
                } else {
                    $analysisResults = ["error" => "No output returned from Python script."];
                }
            } else {
                $message = "Error updating the database: " . $conn->error;
            }
        } else {
            $message = "Sorry, there was an error uploading your file.";
            $uploadOk = 0;
        }
    } else {
        $message = "The file is not an image.";
        $uploadOk = 0;
    }
}

$conn->close();

// Function to call Python for image analysis
function analyze_image($imagePath) {
    $pythonPath = '/opt/anaconda3/bin/python3';  // Correct Python path
    $command = escapeshellcmd("$pythonPath pythonForSafety/analyze_image.py '$imagePath' 2>&1");
    return shell_exec($command);  // Execute command and return output
}

// Display results after analysis
function display_results($results) {
    echo "<div id='analysisResults'>";
    echo "<h3>תוצאות הניתוח:</h3>";
    if (isset($results['safety']) && $results['safety'] == 'safe') {
        echo "<p style='color: green;'>האתר בטוח.</p>";
    } else {
        echo "<p style='color: red;'>האתר אינו בטוח.</p>";
        echo "<p>הבעיות שזוהו הן:</p>";
        echo "<ul>";
        if (isset($results['issues']) && is_array($results['issues'])) {
            foreach ($results['issues'] as $issue) {
                echo "<li>$issue</li>";
            }
        }
        echo "</ul>";
    }
    echo "</div>";
}

?>

<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>העלאת תמונה לבדיקה</ה2>
        <form action="upload_image.php" method="POST" enctype="multipart/form-data">
            <label for="file">בחר תמונה:</label>
            <input type="file" name="file" id="file" accept="image/*" class="form-control" required>
            <button type="submit" class="btn btn-primary border-0" style="background-color: rgba(54, 162, 235, 1);">
                <i class="fa fa-upload me-2"></i>העלה ובדוק
            </button>
        </form>
        
        <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
    </div>

    <!-- Display analysis results after image upload and processing -->
    <?php if (isset($analysisResults)) { display_results($analysisResults); } ?>

</body>
</html>
