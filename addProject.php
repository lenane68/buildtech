<?php 
 $conn = require __DIR__ . "/database.php";

 $sql = "SELECT * FROM client";

 $result = $conn->query($sql);
    



 if(isset($_POST["submit"])){
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
    $clientName = $_POST['clientName'];

   

 if (empty($_POST["floorsNum2"])) { //פרטי
    $stmt = $conn->prepare("insert into project(name, address, startDate, finishDate, clientName, type, floorsNum, pool, basement, parking) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiiii", $name, $address, $startDate, $finishDate, $clientName, $type, $floorsNum, $pool, $basement, $parking);

} else{ //ציבורי 
    $stmt = $conn->prepare("insert into project(name, address, startDate, finishDate, clientName, type, floorsNum, roomsNum, space, cup) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiiii", $name, $address, $startDate, $finishDate, $clientName, $type, $floorsNum2, $roomsNum, $space, $cup);

}

$execval = $stmt->execute();

$file =$_FILES['file1'];
$file2 =$_FILES['file2'];
$file3 =$_FILES['file3'];
$file4 =$_FILES['file4'];

if(isset($_FILES['files'])){
    $folder = "projectPrograms/";

    $names = $_FILES['files']['name'];
    $tmp_names = $_FILES['files']['tmp_name'];

    $upload_data = array_combine($tmp_names, $names);

    foreach($upload_data as $temp_folder => $file_name){
        move_uploaded_file($temp_folder, $folder.$file_name);
        
    }

}

$fileName = $_FILES['file1']['name'];
$fileTmpName = $_FILES['file1']['tmp_name'];
$fileSize = $_FILES['file1']['size'];
$fileError = $_FILES['file1']['error'];
$fileType = $_FILES['file1']['type'];

$fileName2 = $_FILES['file2']['name'];
$fileTmpName2 = $_FILES['file2']['tmp_name'];
$fileSize2 = $_FILES['file2']['size'];
$fileError2 = $_FILES['file2']['error'];
$fileType2 = $_FILES['file2']['type'];

$fileName3 = $_FILES['file3']['name'];
$fileTmpName3 = $_FILES['file3']['tmp_name'];
$fileSize3 = $_FILES['file3']['size'];
$fileError3 = $_FILES['file3']['error'];
$fileType3 = $_FILES['file3']['type'];

$fileName4 = $_FILES['file4']['name'];
$fileTmpName4 = $_FILES['file4']['tmp_name'];
$fileSize4 = $_FILES['file4']['size'];
$fileError4 = $_FILES['file4']['error'];
$fileType4 = $_FILES['file4']['type'];

$fileExt = explode('.', $fileName);
$fileActualExt = strtolower(end($fileExt));

$fileExt2 = explode('.', $fileName2);
$fileActualExt2 = strtolower(end($fileExt2));

$fileExt3 = explode('.', $fileName3);
$fileActualExt3 = strtolower(end($fileExt3));

$fileExt4 = explode('.', $fileName4);
$fileActualExt4 = strtolower(end($fileExt4));

$allowed = array('pdf');


if((in_array($fileActualExt, $allowed)) && (in_array($fileActualExt2, $allowed)) &&
 (in_array($fileActualExt3, $allowed)) && (in_array($fileActualExt4, $allowed))) {
    if(($fileError === 0) && ($fileError2 === 0) && ($fileError3 === 0) && ($fileError4 === 0)){
        if(($fileSize < 1000000) && ($execval) && ($fileSize2 < 1000000) && ($fileSize3 < 1000000) && ($fileSize4 < 1000000)){
            $fileNameNew = uniqid('', true).".".$fileActualExt;
            $fileDestination = 'projectFiles/'.$fileNameNew;
            move_uploaded_file($fileTmpName, $fileDestination);

            $fileNameNew2 = uniqid('', true).".".$fileActualExt2;
            $fileDestination2 = 'projectFiles/'.$fileNameNew2;
            move_uploaded_file($fileTmpName2, $fileDestination2);

            $fileNameNew3 = uniqid('', true).".".$fileActualExt3;
            $fileDestination3 = 'projectFiles/'.$fileNameNew3;
            move_uploaded_file($fileTmpName3, $fileDestination3);

            $fileNameNew4 = uniqid('', true).".".$fileActualExt4;
            $fileDestination4 = 'projectFiles/'.$fileNameNew4;
            move_uploaded_file($fileTmpName4, $fileDestination4);

            $category1 = "הצעת מחיר";
            $category2 = "תמורה";
            $category3 = "הסכם";
            $category4 = "לוח זמנים";
            

            
            $stmt2 = $conn->prepare("insert into projectfile(fileName, type, size, projectName, category) values(?, ?, ?, ?, ?)");
            $stmt2->bind_param("sssss", $fileNameNew, $fileType, $fileSize, $name, $category1);
            $execval2 = $stmt2->execute();

            $stmt3 = $conn->prepare("insert into projectfile(fileName, type, size, projectName, category) values(?, ?, ?, ?, ?)");
            $stmt3->bind_param("sssss", $fileNameNew2, $fileType2, $fileSize2, $name, $category2);
            $execval3 = $stmt3->execute();

            $stmt3 = $conn->prepare("insert into projectfile(fileName, type, size, projectName, category) values(?, ?, ?, ?, ?)");
            $stmt3->bind_param("sssss", $fileNameNew3, $fileType3, $fileSize3, $name, $category3);
            $execval4 = $stmt3->execute();

            $stmt3 = $conn->prepare("insert into projectfile(fileName, type, size, projectName, category) values(?, ?, ?, ?, ?)");
            $stmt3->bind_param("sssss", $fileNameNew4, $fileType4, $fileSize4, $name, $category4);
            $execval5 = $stmt3->execute();
            
            if(($execval2) && ($execval3) && ($execval4) && ($execval5)){
                echo "Added successfully and Upload file success";
            } else {
                die($conn->error . " " . $conn->errno);   
            }
           
        } else {
            if(!($execval)){
                die($conn->error . " " . $conn->errno);
            } else 
            echo "Your file is too big!";  
        }
    } else {
        echo "There was an error uploading your file!";  
    }
} else {
    echo "You can't upload files of this type!";
}

/*
 $execval = $stmt->execute();
 if($execval){
     echo "Adding successfully...";
 } else {
     die($conn->error . " " . $conn->errno);
 }*/

 echo $execval;
 $stmt->close();
 $conn->close();

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
         <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
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
                    <a href="home.html" class="nav-item nav-link "><i class="fa fa-home me-2"></i>ראשי</a>
                    <a href="projectsTable.html" class="nav-item nav-link"><i class="fa fa-map me-2"></i>פרויקטים</a>
                    <a href="bid.html" class="nav-item nav-link"><i class="fa fa-superscript"></i>הצעת מחיר</a>
                    <a href="economic.html" class="nav-item nav-link"><i class="fa fa-university me-2"></i>כלכלי</a>
                    <a href="inventory.html" class="nav-item nav-link"><i class="fa fa-cubes me-2"></i>מחסן</a>
                    <a href="addShift.html" class="nav-item nav-link"><i class="fa fa-book me-2"></i>דיווח משמרת</a>
                    <a href="reports.html" class="nav-item nav-link"><i class="far fa-file-alt me-2 me-2"></i>דוחות</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-plus-square me-2"></i>הוספה</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="addEmployee.html" class="dropdown-item">עובד</a>
                            <a href="addClient.html" class="dropdown-item">לקוח</a>
                            <a href="addMaterial.html" class="dropdown-item">חומר</a>
                            <a href="addProject.html" class="dropdown-item active">פרויקט</a>
                            <a href="addException.html" class="dropdown-item">חריגה</a>
                            
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-edit me-2"></i>עריכה & מחיקה</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="editEmployee.html" class="dropdown-item">עובד</a>
                            <a href="editClient.html" class="dropdown-item">לקוח</a>
                            <a href="editMaterial.html" class="dropdown-item">חומר</a>
                            <a href="editProject.html" class="dropdown-item">פרויקט</a>
                            <a href="editShift.html" class="dropdown-item">משמרת</a>
                            <a href="editException.html" class="dropdown-item">חריגה</a>
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


            <div class="container-fluid pt-4 px-4">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <div id="app">
                                <h6 class="required mb-3" for="id_type">סוג פרויקט</h6>
                              </div>
                              <select name="type" class="form-select mb-3" id="id_type" onchange="myFunction(event)"
                                        aria-label="Floating label select example" id="type">
                                        <option selected value="פרטי">פרטי</option>
                                        <option value="ציבורי"> ציבורי</option>                                      
                              </select>
                              <fieldset>
                                <div id="id_choice_1_container">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floorsNum" name="floorsNum"
                                            placeholder="name@example.com" onchange="myFunction(event)">
                                        <label for="floorsNum">מס' קומות </label>
                                    </div>
                                  <div class="form-check form-switch">
                                    <label class="form-check-label" for="pool">עם בריכה</label>
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="pool" name="pool">
                                    
                                    </div>
                                    <div class="form-check form-switch">
                                        <label class="form-check-label" for="basement">עם מרתף</label>
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="basement" name="basement">
                                    </div>
                                    <div class="form-check form-switch">
                                        <label class="form-check-label" for="parking">עם חניה</label>
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="parking" name="parking">
                                        
                                        </div>
                                </div>
                              
                                <div id="id_choice_2_container" style="display: none">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floorsNum2" name="floorsNum2"
                                            placeholder="name@example.com"  onchange="myFunction(event)">
                                        <label for="floorsNum2">מס' קומות </label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="roomsNum" name="roomsNum"
                                            placeholder="name@example.com"  onchange="myFunction(event)">
                                        <label for="roomsNum">מס' חדרים </label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="space" name="space"
                                            placeholder="name@example.com"  onchange="myFunction(event)">
                                        <label for="space">שטחים </label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="cup" name="cup"
                                            placeholder="name@example.com"  onchange="myFunction(event)">
                                        <label for="cup">קופים פיתוח</label>
                                    </div>
                                </div>
                              </fieldset>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">הוספת פרויקט</h6>
                            <form>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="name@example.com">
                                    <label for="name">שם פרויקט</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="name@example.com">
                                    <label for="address">כתובת </label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="startDate" name="startDate"
                                        placeholder="">
                                    <label for="startDate">תאריך התחלה</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="finishDate" name="finishDate"
                                        placeholder="">
                                    <label for="finishDate">תאריך סיום משוערך</label>
                                </div>
                                <div class="form-floating mb-3" >
                                    <select class="form-select" id="clientName" name="clientName"
                                        aria-label="Floating label select example">
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
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">הוספת שלבים</h6>
                            <select class="form-select mb-3" multiple aria-label="multiple select example">
                                <option selected>שלבי עבודה</option>
                                <option value="1">יציקת קורות קשר</option>
                                <option value="2">יציקת ריצפה</option>
                                <option value="3">גמר מעטפת קומת קרקע</option>
                            </select>
                            <button type="submit" class="btn btn-primary">אישור</button>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">העלאת קבצים</h6>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">הצעת מחיר</label>
                                <input class="form-control" type="file" name="file1">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">תמורה </label>
                                <input class="form-control" type="file" name="file2">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">הסכם </label>
                                <input class="form-control" type="file" name="file3">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">לוח זמנים </label>
                                <input class="form-control" type="file" name="file4">
                            </div>
                            <div class="mb-3">
                                <label for="formFileMultiple" class="form-label">תוכניות עבודה</label>
                                <input class="form-control" type="file" name="files[]" multiple>
                            </div>
                                          
                        </div>
                    </div>
          
                </div>
                <button type="submit" name="submit" class="btn btn-primary">הוסף</button>
                </form>
            </div>


            <!-- Footer Start -->
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

</body>

</html>