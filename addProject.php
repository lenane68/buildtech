<?php 
session_start();

$errorMessage = "";
$successMessage = "";

 $conn = require __DIR__ . "/database.php";

 $sql = "SELECT * FROM client";

 $result = $conn->query($sql);
    
 if(isset($_POST["submit"])){

    // Define the list of fields to check
$fieldsToCheck = [
    'name' => 'שם הפרויקט',
    'type' => 'סוג הפרויקט', 
    'startDate' => 'תאריך התחלה',
    'address' => 'כתובת',
    'finishDate' => 'תאריך סיום',
    'price' => 'מחיר',
    'clientName' => 'שם הלקוח'
];

    // Check if any of the fields is empty
foreach ($fieldsToCheck as $fieldName => $fieldLabel) {
    if (empty($_POST[$fieldName])) {
        $errorMessage = "שדה חובה ריק";
        break; // Stop checking after the first missing field
    }
}


    //check if the price is not a number
    if ($errorMessage=="") {
    if (!is_numeric($_POST['price'])) {
        $errorMessage = 'השדה של המחיר חייב להיות מספר.<br>';
        }
    }

    //check if the types correct inserted
    if ($errorMessage=="") {
        // Define an array of field names you want to check
        $fieldsToCheck = [
            'floorsNum',
            'floorsNum2',
            'roomsNum',
            'space',
            'cup',
        ];

        // Loop through the fields and check if they are not empty and not numeric
        foreach ($fieldsToCheck as $fieldName) {
            $fieldValue = $_POST[$fieldName];
            
           
            if (!empty($fieldValue)) {
                if (!is_numeric($fieldValue)) {
                    $errorMessages = "השדה של $fieldName חייב להיות מספר.<br>";
                    break; // Stop checking after the first wrong field
                } 
            }
           
        }
    }


// If no empty fields, and types is right, proceed with other checks
if ($errorMessage=="") {

    $name = $_POST['name'];
    $type = $_POST['type'];
    $floorsNum = $_POST['floorsNum'];

    if(isset($_POST['pool'])){
        $pool =true;
    }  else  
        $pool =false;
    if(isset($_POST['basement'])){
        $basement =true;
    }  else  
         $basement =false;    
    if(isset($_POST['parking'])){
        $parking =true;
    }  else  
        $parking =false;
    $floorsNum2 = $_POST['floorsNum2'];
    $roomsNum = $_POST['roomsNum'];
    $space = $_POST['space'];
    $cup = $_POST['cup'];
    $startDate = $_POST['startDate'];
    $address = $_POST['address'];
    $finishDate = $_POST['finishDate'];
    $price = $_POST['price'];
    $clientName = $_POST['clientName'];
    // Construct the API request URL
    $apiKey = 'AIzaSyD4pla3F8iMPajljQ3XL2GM5Tbs6G7T5Y0';
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    


 if (empty($_POST["floorsNum2"])) { //פרטי
    $stmt = $conn->prepare("insert into project(name, address, startDate, finishDate, clientName, type, floorsNum, pool, basement, parking, totalPrice) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiiiid", $name, $address, $startDate, $finishDate, $clientName, $type, $floorsNum, $pool, $basement, $parking, $price);

} else{ //ציבורי 
    $stmt = $conn->prepare("insert into project(name, address, startDate, finishDate, clientName, type, floorsNum, roomsNum, space, cup, totalPrice) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiiiid", $name, $address, $startDate, $finishDate, $clientName, $type, $floorsNum2, $roomsNum, $space, $cup, $price);

}


//Inserting the project's location 
$stmt6 = $conn->prepare("insert into data_location(descr, lat, lon, projectName) values(?, ?, ?, ?)");
$stmt6->bind_param("sdds", $address, $latitude, $longitude, $name);


//Inserting the files 
     // Get project details from the form
     $uploadDirectory = 'projectPrograms/';
 
     if (!file_exists($uploadDirectory)) {
         mkdir($uploadDirectory, 0777, true);
     }
 
     // Handle the first uploaded file
     $uploadedFile1 = $_FILES['pdfFile1'];
     if (!empty($uploadedFile1['name'])) {
     $filename1 = $uploadDirectory . basename($uploadedFile1['name']);
     $fileExtension1 = strtolower(pathinfo($filename1, PATHINFO_EXTENSION));
 
     // Check if the file is a PDF
     if ($fileExtension1 === 'pdf') {
         // Move the uploaded file to the specified directory
         if (move_uploaded_file($uploadedFile1['tmp_name'], $filename1)) {
             $insertFile1Query = "INSERT INTO files (project_name, filename) VALUES ('$name', '$filename1')";
             mysqli_query($conn, $insertFile1Query);
            // echo 'File 1 uploaded successfully!<br>';
         } else {
            $errorMessage = 'שגיאה בהעלאת הקובץ הראשון.<br>';
         }
     } else {
         $errorMessage =  'אפשר להעלות רק קבצי pdf.<br>';
     }
    }
 
     // Handle the second uploaded file
     if ($errorMessage==""){
     $uploadedFile2 = $_FILES['pdfFile2'];
     if (!empty($uploadedFile2['name'])) {
     $filename2 = $uploadDirectory . basename($uploadedFile2['name']);
     $fileExtension2 = strtolower(pathinfo($filename2, PATHINFO_EXTENSION));
 
     // Check if the file is a PDF
     if ($fileExtension2 === 'pdf') {
         // Move the uploaded file to the specified directory
         if (move_uploaded_file($uploadedFile2['tmp_name'], $filename2)) {
             $insertFile2Query = "INSERT INTO files (project_name, filename) VALUES ('$name', '$filename2')";
             mysqli_query($conn, $insertFile2Query);
             //echo 'File 2 uploaded successfully!';
         } else {
            $errorMessage = 'שגיאה בהעלאת הקובץ השני.<br>';
       }
     } else {
        $errorMessage =  'אפשר להעלות רק קבצי pdf.<br>';
    }
    }
    }

     // Handle the third uploaded file
     if ($errorMessage==""){
     $uploadedFile3 = $_FILES['pdfFile3'];
     if (!empty($uploadedFile3['name'])) {
     $filename3 = $uploadDirectory . basename($uploadedFile3['name']);
     $fileExtension3 = strtolower(pathinfo($filename3, PATHINFO_EXTENSION));
 
     // Check if the file is a PDF
     if ($fileExtension3 === 'pdf') {
         // Move the uploaded file to the specified directory
         if (move_uploaded_file($uploadedFile3['tmp_name'], $filename3)) {
             $insertFile3Query = "INSERT INTO files (project_name, filename) VALUES ('$name', '$filename3')";
             mysqli_query($conn, $insertFile3Query);
             //echo 'File 3 uploaded successfully!';
         } else {
            $errorMessage = 'שגיאה בהעלאת הקובץ השלישי.<br>';
        }
     } else {
        $errorMessage =  'אפשר להעלות רק קבצי pdf.<br>';
    }
    }
    }

     // Handle the fourth uploaded file
     if ($errorMessage==""){
     $uploadedFile4 = $_FILES['pdfFile4'];
     if (!empty($uploadedFile4['name'])) {
     $filename4 = $uploadDirectory . basename($uploadedFile4['name']);
     $fileExtension4 = strtolower(pathinfo($filename4, PATHINFO_EXTENSION));
 
     // Check if the file is a PDF
     if ($fileExtension4 === 'pdf') {
         // Move the uploaded file to the specified directory
         if (move_uploaded_file($uploadedFile4['tmp_name'], $filename4)) {
             $insertFile4Query = "INSERT INTO files (project_name, filename) VALUES ('$name', '$filename4')";
             mysqli_query($conn, $insertFile4Query);
             
            } else {
                $errorMessage = 'שגיאה בהעלאת הקובץ הרביעי.<br>';
         }
     } else {
        $errorMessage =  'אפשר להעלות רק קבצי pdf.<br>';
    }
    }
    }

    
    try{
        $execval = $stmt->execute();
    if($execval){
        try{
            $execval6 = $stmt6->execute();
        if($execval6){
            $successMessage = "הפרויקט נקלט בהצלחה";
        }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) { // Error code for duplicate entry
                $errorMessage = "כנראה שמיקום הפרויקט כבר קיים במערכת";
            } else {
                $errorMessage = "Error: " . $e->getMessage();
            }
            $successMessage = "הפרויקט נקלט בהצלחה למרות שגיאה בשמירת המיקום";
            
        } 
    }
    }catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) { // Error code for duplicate entry
            $errorMessage = "כנראה שהפרויקט כבר קיים במערכת";
        } else {
            $errorMessage = "Error: " . $e->getMessage();
        }
    } 
    
    $stmt->close();
    $stmt6->close();
    $conn->close();
    }
    

     // Store the messages in session variables
     $_SESSION["successMessage"] = $successMessage;
     $_SESSION["errorMessage"] = $errorMessage;
 
     // Redirect to the same page to prevent re-submission
     header("Location: " . $_SERVER['REQUEST_URI']);
     exit();

 }





?> 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>DASHMIN - Bootstrap Admin Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4pla3F8iMPajljQ3XL2GM5Tbs6G7T5Y0&libraries=places"></script>
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


         <!-- Sidebar Start -->
         <div class="sidebar pe-4 pb-3" >
            <nav class="navbar bg-light navbar-light" >
                <a href="index.html" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary">אבו רפיק גבארין</h3>
                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>BUILD-TECH</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">רפיק גבארין</h6>
                        <span>מנהל ראשי</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="home.php" class="nav-item nav-link"><i class="fa fa-home me-2"></i>ראשי</a>
                    <a href="projectsTable.php" class="nav-item nav-link"><i class="fa fa-map me-2"></i>פרויקטים</a>
                    <a href="bid.html" class="nav-item nav-link"><i class="fa fa-superscript"></i>הצעת מחיר</a>
                    <a href="economic.php" class="nav-item nav-link"><i class="fa fa-university me-2"></i>כלכלי</a>
                    <a href="inventory.php" class="nav-item nav-link"><i class="fa fa-cubes me-2"></i>מחסן</a>
                    <a href="addShift.html" class="nav-item nav-link"><i class="fa fa-book me-2"></i>דיווח משמרת</a>
                    <a href="reports.php" class="nav-item nav-link"><i class="far fa-file-alt me-2 me-2"></i>דוחות</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-plus-square me-2"></i>הוספה</a>
                        <div class="dropdown-menu bg-transparent border-0">
                        <a href="addEmployee.html" class="dropdown-item">עובד</a>
                            <a href="addClient.html" class="dropdown-item">לקוח</a>
                            <a href="addMaterial.html" class="dropdown-item">חומר</a>
                            <a href="addProject.php" class="dropdown-item active">פרויקט</a>
                            <a href="addException.php" class="dropdown-item">חריגה</a>
                            <a href="addSupplier.html" class="dropdown-item">ספק</a>
                            <a href="addVehicle.php" class="dropdown-item">רכב & ציוד צמ"ה</a>
                            <a href="" class="dropdown-item">צ'יק</a>
                            <a href="addReport.php" class="dropdown-item">דו"ח תנועה</a>
                            <a href="addFuel.php" class="dropdown-item">דיווח בנזין</a>
                            <a href="carFix.php" class="dropdown-item">טיפול רכב</a>
                            
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-edit me-2"></i>עריכה & מחיקה</a>
                        <div class="dropdown-menu bg-transparent border-0">
                        <a href="editEmployee.php" class="dropdown-item">עובד</a>
                            <a href="editClient.php" class="dropdown-item">לקוח</a>
                            <a href="editMaterial.php" class="dropdown-item">חומר</a>
                            <a href="" class="dropdown-item">פרויקט</a>
                            <a href="" class="dropdown-item">משמרת</a>
                            <a href="editException.php" class="dropdown-item">חריגה</a>
                            <a href="editSupplier.php" class="dropdown-item">ספק</a>
                            <a href="editCar.php" class="dropdown-item">רכב & ציוד צמ"ה</a>
                            <a href="" class="dropdown-item">צ'יק</a>
                            <a href="editReport.php" class="dropdown-item">דו"ח תנועה</a>
                            <a href="editFuel.php" class="dropdown-item">דיווח בנזין</a>
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
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <form class="d-none d-md-flex ms-4">
                    <input class="form-control border-0" type="search" placeholder="Search">
                </form>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">התראות</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">נוסף עובד חדש</h6>
                                <small>לפני 15 דקות</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">משמרת עובד נקלטה</h6>
                                <small>לפני 20 דקות</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">הסיסמה שונתה</h6>
                                <small>לפני 22 דקות</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="notifications.html" class="dropdown-item text-center">הצגת כל ההתראות</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">רפיק גבארין</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="profile.html" class="dropdown-item">הפרופיל שלי</a>
                            <a href="index.html" class="dropdown-item">יציאה</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->


            <div class="container-fluid pt-4 px-4" dir="rtl">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row g-4">

                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4" dir="rtl">
                            <h5 class="mb-4">הוספת פרויקט</h5>
                            <form method="post">
                            <input type="hidden" class="form-control" id="latitude" name="latitude" type="hidden">
                            <input type="hidden" class="form-control" id="longitude" name="longitude" type="hidden">
                            <div class="form-floating mb-3 position-relative">
                                <input type="text" class="form-control" id="name" name="name" placeholder="name@example.com">
                                <label for="name" class="position-absolute top-0 end-0">שם פרויקט</label>
                            </div>
                            <div class="form-floating mb-3 position-relative">
                                <input type="text" class="form-control" id="locationInput" name="address" placeholder="כתובת">
                                <label for="locationInput" class="position-absolute top-0 end-0">כתובת</label>
                            </div>
                            <div class="form-floating mb-3 position-relative">
                                <input type="date" class="form-control" id="startDate" name="startDate" placeholder="">
                                <label for="startDate" class="position-absolute top-0 end-0">תאריך התחלה</label>
                            </div>
                            <div class="form-floating mb-3 position-relative">
                                <input type="date" class="form-control" id="finishDate" name="finishDate" placeholder="">
                                <label for="finishDate" class="position-absolute top-0 end-0">תאריך סיום משוערך</label>
                            </div>
                            <div class="form-floating mb-3 position-relative">
                            <div class="input-group">
                                <span class="input-group-text">₪</span>
                                <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" id="price" name="price" placeholder="תמורה">
                                <span class="input-group-text">.00</span>
                            </div>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" id="clientName" name="clientName" aria-label="Floating label select example">
                                    <option selected>בחר/י</option>
                                    <?php 
                                        foreach($result as $row)
                                        {
                                            echo '<option value="'.$row["fullName"].'">'.$row["fullName"].'</option>';
                                        }
                                    ?>
                                </select>
                                <label for="clientName">לקוח</label>
                            </div>

                             
                                <div id="app" dir="rtl">
                                <h6 class="required mb-3" for="id_type">סוג פרויקט</h6>
                                </div>
                                <select name="type" class="form-select mb-3" id="id_type" onchange="myFunction(event)"
                                    aria-label="Floating label select example" id="type">
                                    <option selected value="פרטי">פרטי</option>
                                    <option value="ציבורי">ציבורי</option>
                                </select>
                                <fieldset>
                                    <div id="id_choice_1_container">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floorsNum" name="floorsNum"
                                                placeholder="name@example.com" onchange="myFunction(event)">
                                            <label for="floorsNum" class="position-absolute top-0 end-0">מס' קומות</label>
                                        </div>
                                        <div class="form-check form-switch d-flex">
                                            <label class="form-check-label me-2" for="pool">עם בריכה</label>
                                            <input class="form-check-input" type="checkbox" role="switch" id="pool" name="pool">
                                        </div>
                                        <div class="form-check form-switch d-flex">
                                            <label class="form-check-label me-2" for="basement">עם מרתף</label>
                                            <input class="form-check-input" type="checkbox" role="switch" id="basement" name="basement">
                                        </div>
                                        <div class="form-check form-switch d-flex">
                                            <label class="form-check-label me-2" for="parking">עם חניה</label>
                                            <input class="form-check-input" type="checkbox" role="switch" id="parking" name="parking">
                                        </div>

                                    </div>

                                    <div id="id_choice_2_container" style="display: none">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floorsNum2" name="floorsNum2"
                                                placeholder="name@example.com" onchange="myFunction(event)">
                                            <label for="floorsNum2" class="position-absolute top-0 end-0">מס' קומות</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="roomsNum" name="roomsNum"
                                                placeholder="name@example.com" onchange="myFunction(event)">
                                            <label for="roomsNum" class="position-absolute top-0 end-0">מס' חדרים</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="space" name="space" placeholder="name@example.com"
                                                onchange="myFunction(event)">
                                            <label for="space" class="position-absolute top-0 end-0">שטחים</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="cup" name="cup" placeholder="name@example.com"
                                                onchange="myFunction(event)">
                                            <label for="cup" class="position-absolute top-0 end-0">קופים פיתוח</label>
                                        </div>
                                    </div>
                                </fieldset>  
                        </div>
                    </div>
                     <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">העלאת קבצים</h6>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">הצעת מחיר</label>
                                <input class="form-control" type="file" name="pdfFile1" accept="application/pdf">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">תמורה </label>
                                <input class="form-control" type="file" name="pdfFile2"  accept="application/pdf">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">הסכם </label>
                                <input class="form-control" type="file" name="pdfFile3" accept="application/pdf">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">לוח זמנים </label>
                                <input class="form-control" type="file" name="pdfFile4" accept="application/pdf">
                            </div>
                            <!-- Display error message -->
                     <?php if (isset($_SESSION["errorMessage"]) && !empty($_SESSION["errorMessage"])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_SESSION["errorMessage"]; ?>
                            </div>
                        <?php } ?>

                        <!-- Display success message -->
                        <?php if (isset($_SESSION["successMessage"]) && !empty($_SESSION["successMessage"])) { ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $_SESSION["successMessage"]; ?>
                            </div>
                        <?php } ?>

                          <!-- Clear session variables after displaying messages -->
                          <?php
                        unset($_SESSION["errorMessage"]);
                        unset($_SESSION["successMessage"]);
                        ?>
                        </div>
                    </div>
                </div>
                <br>
                <button type="submit" name="submit" id="submit" class="btn btn-primary float-start">הוסף</button>
                </form>
            </div>


            <!-- Footer Start -->
            <br>
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">Your Site Name</a>, All Right Reserved. 
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                            Designed By <a href="https://htmlcodex.com">HTML Codex</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script>
    function myFunction(e) {
        switch (e.target.value) {
          case 'פרטי':
            document.getElementById('id_choice_1_container').style.display = "block";
            document.getElementById('id_choice_2_container').style.display = "none";
            break;
          case 'ציבורי':
            document.getElementById('id_choice_1_container').style.display = "none";
            document.getElementById('id_choice_2_container').style.display = "block";
            break;
          default:
            break;
        }
      }
    </script>
    <script>
    function initializeAutocomplete() {
        var locationInput = document.getElementById('locationInput');
        var autocomplete = new google.maps.places.Autocomplete(locationInput);
        /*autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                // Handle invalid place selection
                return;
            }
            // Retrieve the latitude and longitude of the selected place
            var latitude = place.geometry.address.lat();
            var longitude = place.geometry.address.lng();
            // Assign the latitude and longitude values to hidden input fields or process them as needed
            document.getElementById('latitudeInput').value = latitude;
            document.getElementById('longitudeInput').value = longitude;
        });*/
    }
    function geocode() {
            var place = document.getElementById('locationInput').value;
            var geocoder = new google.maps.Geocoder();

            geocoder.geocode({ address: place }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();
                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;
                } else {
                    alert('Geocoding failed. Please try again.');
                }
            });
        }
    // Call the initializeAutocomplete function when the page loads
    google.maps.event.addDomListener(window, 'load', initializeAutocomplete);
    </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



</body>

</html>