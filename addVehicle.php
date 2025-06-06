<?php

session_start();

$conn = require __DIR__ . "/database.php";

session_start();
if (!isset($_SESSION["email"])) {
    // Redirect to the login page if the user is not logged in
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
    // Handle case where email is not found in the database
    $name = '';
    $password = '';
    $phone = '';
    $role = '';
}



$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        empty($_POST["number"]) || empty($_POST["type"]) || empty($_POST["year"]) || empty($_POST["color"]) ||
        empty($_POST["testFinishDate"]) || empty($_POST["insuranceFinishDate"]) || empty($_POST["careDate"])
    ) {
        $errorMessage = "שדה חובה ריק";
    } else if (!is_numeric($_POST["number"])) {
        $errorMessage = "מספר הרכב/ הכלי חייב להיות מספר";
    } else if ($_POST['color'] === "בחר/י") {
        $errorMessage = 'צריך לבחור צבע מהרשימה<br>';
    } else if ($_POST['fuelType'] === "בחר/י") {
        $errorMessage = 'צריך לבחור סוג בנזין מהרשימה<br>';
    } else if (!empty($_POST["year"]) && (!is_numeric($_POST["year"]) || $_POST["year"] < 1900 || $_POST["year"] > date("Y"))) {
        $errorMessage = 'שנת ייצור לא חוקית<br>';
    } else {

        $number = $_POST['number'];
        $type = $_POST['type'];
        $year = $_POST['year'];
        $color = $_POST['color'];
        $testFinishDate = $_POST['testFinishDate'];
        $insuranceFinishDate = $_POST['insuranceFinishDate'];
        $careDate = $_POST['careDate'];
        $fuelType = $_POST['fuelType'];

        $stmt = $conn->prepare("insert into car(number, type, year, color, testDate, insuranceDate, careDate, fuelType) values(?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $number, $type, $year, $color, $testFinishDate, $insuranceFinishDate, $careDate, $fuelType);
        try {
            $execval = $stmt->execute();
            if ($execval) {
                $successMessage = "הרכב/ הכלי נקלט בהצלחה";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) { // Error code for duplicate entry
                $errorMessage = "כנראה שהכלי כבר קיים במערכת";
            } else {
                $errorMessage = "Error: " . $e->getMessage();
            }
        }

        $stmt->close();
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
    <style>
        .custom-form {
            display: flex;
            justify-content: center;
        }

        .custom-form-container {
            max-width: 500px;
            width: 100%;
            direction: rtl;
            text-align: right;
        }

        .custom-form .form-floating {
            text-align: right;
        }
    </style>
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
                    <a href="projectsTable.php" class="nav-item nav-link"><i class="fa fa-map me-2"></i>פרויקטים</a>
                    <a href="bid1.php" class="nav-item nav-link"><i class="fa fa-superscript"></i>הצעת מחיר</a>
                    <a href="economic.php" class="nav-item nav-link"><i class="fa fa-university me-2"></i>כלכלי</a>
                    <a href="inventory.php" class="nav-item nav-link"><i class="fa fa-cubes me-2"></i>מחסן</a>
                    <a href="addShift.php" class="nav-item nav-link"><i class="fa fa-book me-2"></i>דיווח משמרת</a>
                    <a href="reports.php" class="nav-item nav-link"><i class="far fa-file-alt me-2 me-2"></i>דוחות</a>
                    <a href="notifications.php" class="nav-item nav-link"><i class="far fa-bell me-2 me-2"></i>התראות</a>
                    <a href="profile.php" class="nav-item nav-link"><i class="far fa-user me-2 me-2"></i>עדכון פרופיל</a>
            
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown"><i class="fa fa-plus-square me-2"></i>הוספה</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="addEmployee.php" class="dropdown-item">עובד</a>
                            <a href="addClient.php" class="dropdown-item">לקוח</a>
                            <a href="addmaterial.php" class="dropdown-item">חומר</a>
                            <a href="addProject.php" class="dropdown-item">פרויקט</a>
                            <a href="addException.php" class="dropdown-item">חריגה</a>
                            <a href="addSupplier.php" class="dropdown-item">ספק</a>
                            <a href="addVehicle.php" class="dropdown-item active">רכב & ציוד צמ"ה</a>
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
                            <i class="fa fa-bell me-lg-2"></i><span class="position-absolute top-45 start-50 translate-middle badge rounded-pill bg-danger"></span>
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
            <!-- Navbar End -->


            <div class="col-sm-12 custom-form">
                <div class="bg-light rounded p-4 custom-form-container" dir="rtl">
                    <h5 class="mb-4">הוספת רכב/ כלי צמ"ה</h5>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="number" name="number" placeholder="">
                            <label for="number" class="position-absolute top-0 end-0">מספר</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="type" name="type" placeholder="">
                            <label for="type" class="position-absolute top-0 end-0">סוג</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="year" name="year" placeholder="">
                            <label for="year" class="position-absolute top-0 end-0">שנה</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="color" name="color" aria-label="Floating label select example">
                                <option selected>בחר/י</option>
                                <option>אדום</option>
                                <option>ברונז</option>
                                <option>כחול</option>
                                <option>ירוק</option>
                                <option>כסף</option>
                                <option>כתום</option>
                                <option>לבן</option>
                                <option>צהוב</option>
                                <option>שחור</option>

                            </select>
                            <label for="color" class="position-absolute top-0 end-0">צבע</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="fuelType" name="fuelType" aria-label="Floating label select example">
                                <option selected>בחר/י</option>
                                <option>בנזין 95</option>
                                <option>סולר</option>
                            </select>
                            <label for="fuelType" class="position-absolute top-0 end-0">סוג דלק</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="testFinishDate" name="testFinishDate" placeholder="">
                            <label for="testFinishDate" class="position-absolute top-0 end-0">תאריך סיום טסט</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="insuranceFinishDate" name="insuranceFinishDate" placeholder="">
                            <label for="insuranceFinishDate" class="position-absolute top-0 end-0">תאריך סיום ביטוח</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="careDate" name="careDate" placeholder="">
                            <label for="careDate" class="position-absolute top-0 end-0">תאריך הטיפול הבא</label>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">הוסף</button>
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
                    </form>
                </div>
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
</body>

</html>