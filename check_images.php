<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/database.php';

use Mpdf\Mpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Query not_analyzed images grouped by project
$query = "SELECT p.name AS project_name, pi.id, pi.image_path 
          FROM project_images pi
          JOIN project p ON pi.project_id = p.id
          WHERE pi.status = 'not_analyzed'
          ORDER BY p.name";

$result = mysqli_query($conn, $query);
$groupedImages = [];
$hasImages = false;

// Analyze images and prepare HTML
$html = '<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            text-align: right;
            background-color: #f9f9f9;
            color: #333;
            padding: 40px;
        }
        h1 {
            color: #2c3e50;
            font-size: 28px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }
        h2 {
            color: #2980b9;
            margin-top: 40px;
            font-size: 22px;
            border-bottom: 1px solid #2980b9;
            padding-bottom: 5px;
        }
        .image-container {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .footer {
            margin-top: 60px;
            font-size: 14px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
<h1>דוח ניתוח תמונות - בטיחות באתרי בנייה</h1>
<p>הדוח כולל תמונות שנותחו ממספר פרויקטים ונשמרו לאחר סימון אוטומטי של גורמי בטיחות או חריגות.</p>';


$currentProject = '';

while ($row = mysqli_fetch_assoc($result)) {
    $hasImages = true;
    $projectName = htmlspecialchars($row['project_name']);
    $imageId = $row['id'];
    $originalPath = $row['image_path'];

    // Call your Python script
    $result_analyze = analyze_image($originalPath);
    $output_image_path = trim($result_analyze[0]);

    // אם זה פרויקט חדש
    if ($projectName !== $currentProject) {
        if (!empty($currentProject)) {
            $html .= '<pagebreak />';
        }
        $html .= "<h2>פרויקט: $projectName</h2>";
        $currentProject = $projectName;
    }

    $html .= "<div class='image-container'><img src='$output_image_path' alt='Analyzed image'></div>";

    // עדכון מסד הנתונים
    $update_query = "UPDATE project_images 
                     SET status='analyzed', path_after_analyzing='" . mysqli_real_escape_string($conn, $output_image_path) . "'
                     WHERE id=$imageId";
    mysqli_query($conn, $update_query);
}

$html .= '<div class="footer">הדוח נוצר באופן אוטומטי בתאריך ' . date("d/m/Y H:i") . '</div>';
$html .= '</body></html>';

// If no images were analyzed, do nothing
if (!$hasImages) {
    echo "No new images to analyze.";
    exit;
}

// Generate PDF
$pdfPath = __DIR__ . "/project_images_report.pdf";
$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'direction' => 'rtl',
    'tempDir' => __DIR__ . '/tmp'
]);

$mpdf->WriteHTML($html);
$mpdf->Output($pdfPath, \Mpdf\Output\Destination::FILE);

// Send Email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'lena.nemah.99@gmail.com'; // replace with your email
    $mail->Password = 'xhifynupfpmpfjse';        // use App Password (not your real password)
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->CharSet = 'UTF-8'; // זה חשוב מאוד לעברית!
    $mail->setFrom('no-reply@yourdomain.com', 'מערכת בדיקות בטיחות');
    $mail->addAddress('lena.mhamed@hotmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'דו"ח בטיחות מצורף';
    $mail->Body = '<h2>מצורף הדו"ח לאחר ניתוח התמונות</h2><p>ניתן לצפות בקובץ המצורף.</p>';
    $mail->AltBody = 'מצורף דו"ח PDF לאחר ניתוח התמונות.';

    $mail->addAttachment($pdfPath);

    $mail->send();
    echo "Email with PDF sent successfully!";
} catch (Exception $e) {
    echo "Email failed: {$mail->ErrorInfo}";
}

// Helper function: Analyze image
function analyze_image($imagePath)
{
    $pythonPath = '/opt/anaconda3/bin/python'; // Adjust as needed
    $command = escapeshellcmd("$pythonPath analyze_image.py '$imagePath'");
    $output = shell_exec($command . " 2>&1");
    $outputImagePath = trim($output);
    $baseURL = 'img/after_analyzing/';
    $relativePath = $baseURL . basename($outputImagePath);
    return [$relativePath];
}
?>
