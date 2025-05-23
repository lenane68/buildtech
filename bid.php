<?php
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



?>

<!DOCTYPE html>
<html>

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
                <div class="navbar-nav w-100">
                    <a href="home.php" class="nav-item nav-link "><i class="fa fa-home me-2"></i>ראשי</a>
                    <a href="projectsTable.php" class="nav-item nav-link"><i class="fa fa-map me-2"></i>פרויקטים</a>
                    <a href="bid1.php" class="nav-item nav-link active"><i class="fa fa-superscript"></i>הצעת מחיר</a>
                    <a href="economic.php" class="nav-item nav-link"><i class="fa fa-university me-2"></i>כלכלי</a>
                    <a href="inventory.php" class="nav-item nav-link"><i class="fa fa-cubes me-2"></i>מחסן</a>
                    <a href="addshift.php" class="nav-item nav-link"><i class="fa fa-book me-2"></i>דיווח משמרת</a>
                    <a href="reports.php" class="nav-item nav-link"><i class="far fa-file-alt me-2 me-2"></i>דוחות</a>
                    <a href="notifications.php" class="nav-item nav-link"><i class="far fa-bell me-2 me-2"></i>התראות</a>
                    <a href="profile.php" class="nav-item nav-link"><i class="far fa-user me-2 me-2"></i>עדכון פרופיל</a>
            
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-plus-square me-2"></i>הוספה</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="addEmployee.html" class="dropdown-item">עובד</a>
                            <a href="addClient.html" class="dropdown-item">לקוח</a>
                            <a href="addmaterial.php" class="dropdown-item">חומר</a>
                            <a href="addProject.php" class="dropdown-item">פרויקט</a>
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
            <div class="container-fluid pt-4 px-4" dir="rtl">
                <div class="row g-4">
                    <div class="bg-light rounded h-100 p-4">
                        <h4 class="mb-4" dir="rtl">חישוב שלד</h4>
                        <div class="bg-light rounded h-100 p-4">
                            <form id="bid_batoon">
                                <label for="batonclass">1. חישוב בטון: </label>
                                <div class="form-floating mb-3" id="batonclass">
                                    <select class="form-select" id="batonSelect" aria-label="Floating label select example" onchange="calculateTotalPrice('bid_batoon')">
                                        <option selected>בחר/י</option>
                                        <?php
                                        $sqli = "SELECT * FROM materials_bid WHERE name = 'batoon'";
                                        $result = $conn->query($sqli);

                                        if ($result->num_rows > 0) {
                                            // output data of each row
                                            while ($row = $result->fetch_assoc()) {

                                                echo "<option value='" . $row["price"] . "'>" . $row["type"] . "</option>";
                                            }
                                        } else {
                                            echo "";
                                        }
                                        ?>
                                    </select>
                                    <label for=" batonSelect">סוג בטון</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <!--   <input type="hidden" class="form-control" id="price_batoon" placeholder=""> -->
                                    <input type="number" class="form-control" id="floatingInput" placeholder="" onchange="calculateTotalPrice('bid_batoon')">
                                    <label for="floatingInput">כמות קובים</label>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">₪</span>
                                    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="המחיר :">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </form>

                            <form id="bid_barzel">
                                <label for="barzelclass">2. חישוב ברזל:</label>
                                <div class="form-floating mb-3" id="barzelclass">
                                    <select class="form-select" id="barzelSelect" aria-label="Floating label select example" onchange="calculateTotalPrice('bid_barzel')">
                                        <option selected>בחר/י</option>
                                        <?php
                                        $sqli2 = "SELECT * FROM materials_bid WHERE name = 'ברזל'";
                                        $result2 = $conn->query($sqli2);

                                        if ($result2->num_rows > 0) {
                                            // output data of each row
                                            while ($row = $result2->fetch_assoc()) {

                                                echo "<option value='" . $row["price"] . "'>" . $row["type"] . "</option>";
                                            }
                                        } else {
                                            echo "";
                                        }
                                        ?>
                                    </select>
                                    <label for=" barzelSelect">סוג ברזל</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <!-- <input type="hidden" class="form-control" id="price_barzel" placeholder=""> -->
                                    <input type="number" class="form-control" id="floatingInput" placeholder="" onchange="calculateTotalPrice('bid_barzel')">
                                    <label for="floatingInput">כמות יחידות</label>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">₪</span>
                                    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="המחיר :">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </form>

                            <form id="bid_block">
                                <label for="blockclass">3. חישוב בלוק :</label>
                                <div class="form-floating mb-3" id="blockclass">
                                    <select class="form-select" id="blockSelect" aria-label="Floating label select example" onchange="calculateTotalPrice('bid_block')">
                                        <option selected>בחר/י</option>
                                        <?php
                                        $sqli3 = "SELECT * FROM materials_bid WHERE name = 'בלוק'";
                                        $result3 = $conn->query($sqli3);

                                        if ($result3->num_rows > 0) {
                                            // output data of each row
                                            while ($row = $result3->fetch_assoc()) {

                                                echo "<option value='" . $row["price"] . "'>" . $row["type"] . "</option>";
                                            }
                                        } else {
                                            echo "";
                                        }
                                        ?>
                                    </select>
                                    <label for=" blockSelect">סוג בלוק</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <!--   <input type="hidden" class="form-control" id="price_block" placeholder=""> -->
                                    <input type="number" class="form-control" id="floatingInput" placeholder="" onchange="calculateTotalPrice('bid_block')">
                                    <label for="floatingInput">כמות יחידות</label>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">₪</span>
                                    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="המחיר :">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </form>
                        </div>

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
                    function calculateTotalPrice(formId) {
                        var price = $("#" + formId + " select").val(); // Get selected price from dropdown
                        var quantity = $("#" + formId + " input[type='number']").val(); // Get quantity

                        var totalPrice = quantity * price;
                        $("#" + formId + " .input-group input[type='text']").val(totalPrice); // Set total price in the input field
                    }
                </script>
</body>

</html>