<?php


// Database and session setup (same as before)

$conn = require __DIR__ . "/database.php";
session_start();

if (!isset($_SESSION["email"])) {
    header('Location: index.php');
    exit();
}

$email = mysqli_real_escape_string($conn, $_SESSION['email']);
$query = "SELECT * FROM account WHERE email='$email'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $name = $row['userName'];
    $password = $row['password'];
    $phone = $row['phoneNum'];
    $role = $row['role'];
} else {
    $name = '';
    $password = '';
    $phone = '';
    $role = '';
}

$projectID = $_SESSION['project_id'];
$imagePath = ''; // Initialize the image path

// File upload and analysis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $targetDir = "img/safety/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {

            $imagePath = $targetFile;  // Store image path

            // Run Python script to analyze the image and get the image with rectangles
            $result_analyze = analyze_image($targetFile);  // Assuming this function returns the path of the image with rectangles

            $output_image_path = substr($result_analyze[0], 0, -1);
            $count_unsafe = $result_analyze[1];

            // Here you can store the path of the image with rectangles in the database if needed.

            $image_insertion = "Image path successfully inserted.";
        } else {
            $image_insertion = "Error uploading image.";
        }
    } else {
        $image_insertion = "File is not a valid image.";
        $uploadOk = 0;
    }
} else {
    $image_insertion = "No file uploaded.";
}

$conn->close();

function analyze_image($imagePath)
{
    $pythonPath = '/opt/anaconda3/bin/python';  // Adjust as needed
    $command = escapeshellcmd("$pythonPath analyze_image.py '$imagePath'");
    $output = shell_exec($command . " 2>&1");

    // Trim the output and get the relative path to use in the HTML
    $outputImagePath = trim($output);


    // Make sure to return the relative path so it works with the web server
    $baseURL = 'img/after_analyzing/'; // URL path from the root of your server
    $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $outputImagePath); // Remove the document root part from the path

    $str = $baseURL . basename($relativePath);

    $array = explode(",", $str);
    
    
    return $array; // Return the relative URL path for the image
}


?>

<!DOCTYPE html>
<html lang="he">
<head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="UTF-8">
    <title>DASHMIN - Bootstrap Admin Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />


    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary">אבו <?php echo $name ?></h3>
                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>BUILD-TECH</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo $name ?></h6>
                        <span><?php echo $role ?></span>
                    </div>
                </div>
                <div class="navbar-nav w-100" style="float:right;">
                    <a href="home.php" class="nav-item nav-link "><i class="fa fa-home me-2"></i>ראשי</a>
                    <a href="projectsTable.php" class="nav-item nav-link active"><i class="fa fa-map me-2"></i>פרויקטים</a>
                    <a href="bid1.php" class="nav-item nav-link"><i class="fa fa-superscript"></i>הצעת מחיר</a>
                    <a href="economic.php" class="nav-item nav-link"><i class="fa fa-university me-2"></i>כלכלי</a>
                    <a href="inventory.php" class="nav-item nav-link"><i class="fa fa-cubes me-2"></i>מחסן</a>
                    <a href="addShift.php" class="nav-item nav-link"><i class="fa fa-book me-2"></i>דיווח משמרת</a>
                    <a href="reports.php" class="nav-item nav-link"><i class="far fa-file-alt me-2 me-2"></i>דוחות</a>
                    <a href="notifications.php" class="nav-item nav-link"><i class="far fa-bell me-2 me-2"></i>התראות</a>
                    <a href="profile.php" class="nav-item nav-link"><i class="far fa-user me-2 me-2"></i>עדכון פרופיל</a>
                    <a href="crm/homecrm.php" class="nav-item nav-link"><i class="fa fa-address-book me-2"></i>CRM</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-plus-square me-2"></i>הוספה</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="addEmployee.php" class="dropdown-item">עובד</a>
                            <a href="addClient.php" class="dropdown-item">לקוח</a>
                            <a href="addmaterial.php" class="dropdown-item">חומר</a>
                            <a href="addProject.php" class="dropdown-item">פרויקט</a>
                            <a href="addException.php" class="dropdown-item">חריגה</a>
                            <a href="addSupplier.php" class="dropdown-item">ספק</a>
                            <a href="addVehicle.php" class="dropdown-item">רכב & ציוד צמ"ה</a>
                            <a href="addCheck.php" class="dropdown-item">צ'יק</a>
                            <a href="addReport.php" class="dropdown-item">דו"ח תנועה</a>
                            <a href="addFuel.php" class="dropdown-item">דיווח דלק</a>
                            <a href="carFix.php" class="dropdown-item">טיפול רכב</a>

                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-edit me-2"></i>עריכה & מחיקה</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="editEmployee.php" class="dropdown-item">עובד</a>
                            <a href="editClient.php" class="dropdown-item">לקוח</a>
                            <a href="editMaterial.php" class="dropdown-item">חומר</a>
                            <a href="editShift.php" class="dropdown-item">משמרת</a>
                            <a href="editException.php" class="dropdown-item">חריגה</a>
                            <a href="editSupplier.php" class="dropdown-item">ספק</a>
                            <a href="editCar.php" class="dropdown-item">רכב & ציוד צמ"ה</a>
                            <a href="editCheck.php" class="dropdown-item">צ'יק</a>
                            <a href="editReport.php" class="dropdown-item">דו"ח תנועה</a>
                            <a href="editFuel.php" class="dropdown-item">דיווח דלק</a>
                            <a href="editFixing.php" class="dropdown-item">טיפול רכב</a>
                        </div>
                    </div>


                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <div class="navbar-nav me-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex"><?php echo $name ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="profile.php" class="dropdown-item">הפרופיל שלי</a>
                            <a href="logOut.php" class="dropdown-item">יציאה</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i><span class="position-absolute top-45 start-50 translate-middle badge rounded-pill bg-danger"><?php  ?></span>
                            <span class="d-none d-lg-inline-flex">התראות</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <?php
                            if ($result_notify->num_rows <= 0) {
                                echo "";
                            } else {
                                while (($row_notify = $result_notify->fetch_assoc())) {
                                    echo "<a href='#' class='dropdown-item'>";
                                    echo "<h6 class='fw-normal mb-0'>" . $row_notify["title"] . "</h6>";
                                    echo "<small>" . $row_notify["date"] . "</small>";
                                    echo "</a>";
                                }
                            }
                            ?>
                            <hr class="dropdown-divider">
                            <a href="notifications.php" class="dropdown-item text-center">הצגת כל ההתראות</a>
                        </div>
                    </div>
                </div>
                <div class="navbar-nav ms-auto">

                    <a href="#" class="sidebar-toggler flex-shrink-0 ms-2">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
            </nav>
            <div class="container-fluid pt-4 px-4" dir="rtl">
                <h2>העלאת תמונה לבדיקה</h2>
                <form action="upload_image.php" method="POST" enctype="multipart/form-data">
                    <label for="file">בחר תמונה:</label>
                    <input
                        type="file"
                        name="file"
                        id="file"
                        accept="image/*"
                        class="form-control"
                        required
                        onchange="previewImage(event)">

                    <!-- Image Preview -->
                    <div id="imagePreviewContainer" style="display: none; margin-top: 20px;">
                        <h3>תמונה שהועלתה:</h3>
                        <img id="imagePreview" src="" alt="Uploaded Image" style="max-width: 50%; height: auto;">
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary border-0"
                        style="background-color: rgba(54, 162, 235, 1);">
                        <i class="fa fa-upload me-2"></i>העלה ובדוק
                    </button>
                </form>

                <!-- Uploaded Image Display -->
                <?php if (!empty($imagePath)): ?>
                    <div id="uploadedImageContainer" style="margin-top: 20px;">
                        <h3>תמונה שהועלתה:</h3>
                        <img
                            src="<?php echo htmlspecialchars($imagePath); ?>"
                            alt="Uploaded Image"
                            style="max-width: 50%; height: auto;">
                    </div>
                <?php endif; ?>
            </div>

            <!-- Analysis Results -->
            <div class="container-fluid pt-4 px-4" dir="rtl">
              <?php if (isset($analysisResults)) {
                   while ($row = $analysisResults->fetch_assoc()) {
                    echo "<table class='table table-bordered'>";
                    echo "<tr><th>מספר אנשים</th><td>" . htmlspecialchars($row['persons']) . "</td></tr>";
                    echo "<tr><th>כלי רכב</th><td>" . htmlspecialchars($row['vehicles']) . "</td></tr>";
                    echo "<tr><th>כובע</th><td>" . htmlspecialchars($row['hardhat']) . "</td></tr>";
                    echo "<tr><th>מכונות</th><td>" . htmlspecialchars($row['machineries']) . "</td></tr>";
                    echo "<tr><th>אפוד בטיחות</th><td>" . htmlspecialchars($row['safety_vest']) . "</td></tr>";
                    echo "<tr><th>מסיכה</th><td>" . htmlspecialchars($row['mask']) . "</td></tr>";
                    echo "<tr><th>קונוס בטיחות</th><td>" . htmlspecialchars($row['safety_cone']) . "</td></tr>";
                    echo "</table>";
                }
                }?>
            </div>
            <?php if (!empty($output_image_path)): ?>
    <div class="container-fluid pt-4 px-4" dir="rtl">
        <h3>תמונה לאחר ניתוח:</h3>
        <img
            src="<?php echo htmlspecialchars($output_image_path); ?>"
            alt="Analyzed Image"
            style="max-width: 50%; height: auto;">
    </div>
<?php endif; ?>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];

            // Elements to manipulate
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const uploadedImageContainer = document.getElementById('uploadedImageContainer');
            const analysisResultsContainer = document.querySelector('.container-fluid:nth-of-type(2)'); // Adjusted to target the analysis results container

            // Clear previous image and analysis
            imagePreviewContainer.style.display = 'none';
            imagePreview.src = '';
            if (uploadedImageContainer) uploadedImageContainer.style.display = 'none';
            if (analysisResultsContainer) analysisResultsContainer.innerHTML = '';

            // Display new image preview
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
    </script>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <script src="js/main.js"></script>

</body>

</html>
