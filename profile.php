<?php

session_start();

$conn = require __DIR__ . "/database.php";

if (!isset($_SESSION["email"])) {
    // Redirect to the login page if the user is not logged in
    header('Location: index.php');
    exit();
}
include_once 'notify.php';

$email1 = mysqli_real_escape_string($conn, $_SESSION['email']);
$query1 = "SELECT * FROM account WHERE email='$email1'";
$result1 = mysqli_query($conn, $query1);

if ($row1 = mysqli_fetch_assoc($result1)) {
    $name1 = $row1['userName'];
    $password1 = $row1['password'];
    $phone1 = $row1['phoneNum'];
    $role1 = $row1['role'];
} else {
    // Handle case where email is not found in the database
    $name1 = '';
    $password1 = '';
    $phone1 = '';
    $role1 = '';
}

if (isset($_POST['userEmail'])) {

    $name = mysqli_real_escape_string($conn, $_POST['userName']);
    $email = mysqli_real_escape_string($conn, $_POST['userEmail']);
    $password = mysqli_real_escape_string($conn, $_POST['floatingPassword']);
    $phone = mysqli_real_escape_string($conn, $_POST['userPhone']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $check_query = "SELECT * FROM account WHERE email='$email'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) == 0) {
        $res = [
            'status' => 404,
            'message' => 'לא נמצא אימייל מתאים'
        ];
    } else if (empty($name) || empty($phone) || empty($password) || empty($role)) {
        $res = [
            'status' => 422,
            'message' => 'שדה חובה ריק'
        ];
    } else {
        if ($row = mysqli_fetch_assoc($check_result)) {
            if ($name == $row['userName'] && $password == $row['password'] && $role == $row['role'] && $phone == $row['phoneNum']) {
                $res = [
                    'status' => 500,
                    'message' => 'הנתונים לא השתנו'
                ];
            } else {



                $query = "UPDATE account SET userName='$name', phoneNum='$phone', password='$password', role='$role' WHERE email='$email'";


                $query_run = mysqli_query($conn, $query);

                if ($query_run) {
                    $res = [
                        'status' => 200,
                        'message' => 'הפרופיל עודכן בהצלחה'
                    ];

                    //to notify that the profile updated
                    $query_10 = "SELECT * FROM notification ORDER BY id DESC limit 1";
                    $query_10_r = mysqli_query($conn, $query_10);
                    $num = 0;
                    if (mysqli_num_rows($query_10_r) == 0) {
                        $num = 1;
                    } else {
                        $row = mysqli_fetch_assoc($query_10_r);
                        $num = $row['id'] + 1;
                    }

                    $stmt = $conn->prepare("INSERT INTO notification (title, full_message,relative_num,seen) VALUES (?, ?, ?,?)");
                    $relative_num = (string)$num;
                    $title = "הפרופיל עודכן";
                    $full_message = "הפרופיל שלך התעדכן!";
                    $seen = 0;

                    $stmt->bind_param("sssi", $title, $full_message, $relative_num, $seen);

                    $stmt->execute();

                    //*************** */
                } else {
                    $res = [
                        'status' => 500,
                        'message' => 'לא עודכן פרופיל'
                    ];
                }
            }
        }
    }

    echo json_encode($res);
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
                    <h3 class="text-primary">אבו <?php echo $name1 ?></h3>
                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>BUILD-TECH</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo $name1 ?></h6>
                        <span><?php echo $role1 ?></span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="home.php" class="nav-item nav-link"><i class="fa fa-home me-2"></i>ראשי</a>
                    <a href="projectsTable.php" class="nav-item nav-link"><i class="fa fa-map me-2"></i>פרויקטים</a>
                    <a href="bid1.php" class="nav-item nav-link"><i class="fa fa-superscript"></i>הצעת מחיר</a>
                    <a href="economic.php" class="nav-item nav-link"><i class="fa fa-university me-2"></i>כלכלי</a>
                    <a href="inventory.php" class="nav-item nav-link"><i class="fa fa-cubes me-2"></i>מחסן</a>
                    <a href="addshift.php" class="nav-item nav-link"><i class="fa fa-book me-2"></i>דיווח משמרת</a>
                    <a href="reports.php" class="nav-item nav-link"><i class="far fa-file-alt me-2 me-2"></i>דוחות</a>
                    <a href="notifications.php" class="nav-item nav-link"><i class="far fa-bell me-2 me-2"></i>התראות</a>
                    <a href="profile.php" class="nav-item nav-link active"><i class="far fa-user me-2 me-2"></i>עדכון פרופיל</a>
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
                            <span class="d-none d-lg-inline-flex"><?php echo $name1 ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="profile.php" class="dropdown-item">הפרופיל שלי</a>
                            <a href="logOut.php" class="dropdown-item">יציאה</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i><span class="position-absolute top-45 start-50 translate-middle badge rounded-pill bg-danger"><?php echo $unread_notification_count; ?></span>
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
                    <form class="d-none d-md-flex" style="justify-content: flex-end;">
                        <input class="form-control border-0" type="search" placeholder="Search">
                    </form>
                    <a href="#" class="sidebar-toggler flex-shrink-0 ms-2">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
            </nav>
            <!-- Navbar End -->

            <div class="page-wrapper">
                <!-- ============================================================== -->
                <!-- Container fluid  -->
                <!-- ============================================================== -->
                <div class="container-fluid pt-4 px-4 ">
                    <!-- ============================================================== -->
                    <!-- Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <div class="row page-titles mb-3 g-4">
                        <div class="col-md-5 align-self-center">
                            <h3 style="line-height: 3opx; font-size: 21px; color:#4692c1; font-family:Montserrat, sans-serif; font-weight: 400;">
                                Profile</h3>
                        </div>


                    </div>
                    <!-- ============================================================== -->
                    <!-- End Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Start Page Content -->
                    <!-- ============================================================== -->
                    <!-- Row -->
                    <div class="row g-4">
                        <!-- Column -->
                        <div class="col-lg-4 col-xlg-3 col-md-5">
                            <div class="bg-light rounded h-100 p-4">
                                <div class="mb-4">
                                    <div class="m-t-30 mb-3" style="text-align: center;"> <img src="img/3.jpeg" style="border-radius: 50%;" width="150" class="mb-3" />
                                        <h4 class=" m-t-10 mb-3 " style="color: #3e3f40; position: relative; font-weight: 400;"> <?php echo $name1 ?>
                                        </h4>
                                        <h6 class="card-subtitle mb-3" style="color: #6b6d6e; position: relative; font-weight: 400;"><?php echo $role1 ?></h6>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                        <!-- Column -->
                        <div class="col-lg-8 col-xlg-9 col-md-7">
                            <div class="bg-light rounded h-100 p-4">
                                <!-- Tab panes -->
                                <div class="">
                                    <form id="updateProfile" method="post" action="profile.php">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="userName" name="userName" placeholder="" value="<?php echo $name1; ?>">
                                            <label for="floatingPassword"> שם משתמש</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="" value="<?php echo $email1; ?>">
                                            <label for="floatingPassword"> אימייל</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" id="floatingPassword" name="floatingPassword" placeholder="" value="<?php echo $password1; ?>">
                                            <label for="floatingPassword"> סיסמה</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="userPhone" name="userPhone" placeholder="" value="<?php echo $phone1; ?>">
                                            <label for="floatingPassword"> מס' טלפון</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="role" name="role" placeholder="" value="<?php echo $role1; ?>">
                                            <label for="floatingPassword"> תפקיד</label>
                                        </div>
                                        <button type="submit" class="btn btn-primary">עדכון פרופיל</button>


                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                    </div>
                    <!-- Row -->
                    <!-- ============================================================== -->
                    <!-- End PAge Content -->
                    <!-- ============================================================== -->
                </div>
                <!-- ============================================================== -->
                <!-- End Container fluid  -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- footer -->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- End footer -->
                <!-- ============================================================== -->
            </div>




        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
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
        $(document).on('submit', '#updateProfile', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            fetch(this.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status == 422 || data.status == 404) {
                        // Handle validation error
                        // document.getElementById('errorMessageUpdate').classList.remove('d-none');
                        // document.getElementById('errorMessageUpdate').textContent = data.message;
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.error(data.message);
                    } else if (data.status == 200) {
                        // Handle success
                        // document.getElementById('errorMessageUpdate').classList.add('d-none');
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success(data.message);
                        location.reload();
                        this.reset(); // Reset the form
                        // Optionally, update any other parts of the page as needed
                    } else if (data.status == 500) {
                        // Handle server error
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.error(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));

        });
    </script>
</body>

</html>