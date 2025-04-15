<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$conn = require __DIR__ . "/../database.php";

session_start();

if (!isset($_SESSION["email"])) {
    // Redirect to the login page if the user is not logged in
    //header('Location: ../../index.php');
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
// Fetch lead status counts from the database 
$leads_status_query = "SELECT status, COUNT(*) as count FROM leads GROUP BY status"; 
$leads_status_result = mysqli_query($conn, $leads_status_query); 
$statuses = []; 
$counts = []; 
while ($row = mysqli_fetch_assoc($leads_status_result)) { 
    $statuses[] = $row['status']; 
    $counts[] = $row['count']; }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="utf-8">
    <title>BuildTech</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon >
    <link href="img/favicon.ico" rel="icon" >

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">


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
        <a href="../index.php" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary">אבו <?php echo $name ?></h3>
            <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>BUILD-TECH</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="../img/user.jpg" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0"><?php echo $name ?></h6>
                <span><?php echo $role ?></span>
            </div>
        </div>
        <div class="navbar-nav w-100" style="float:right;">
            <a href="#" class="nav-item nav-link active"><i class="fa fa-home me-2"></i>לוח בקרה</a>
            <a href="leads_m.php" class="nav-item nav-link"><i class="fa fa-home me-2"></i>ניהול לידים</a>
            <a href="#" class="nav-item nav-link"><i class="fa fa-home me-2"></i>ניהול לקוחות</a>
            <a href="#" class="nav-item nav-link"><i class="fa fa-home me-2"></i>ניהול משתמשים</a>
            <a href="#" class="nav-item nav-link"><i class="fa fa-home me-2"></i>תמיכה ועזרה</a>
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
                            <img class="rounded-circle me-lg-2" src="../img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex"><?php echo $name ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="../profile.php" class="dropdown-item">הפרופיל שלי</a>
                            <a href="../logOut.php" class="dropdown-item">יציאה</a>
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
            <div class="container" dir="rtl"> 
            <h2>לידים לפי סטטוס</h2> 
            <canvas id="leadsStatusChart"></canvas> 
        </div> 
        <script> 
        var ctx = document.getElementById('leadsStatusChart').getContext('2d'); 
        var leadsStatusChart = new Chart(ctx, { type: 'bar', data: { 
            labels: <?php echo json_encode($statuses); ?>, datasets: [{ label: 'מספר לידים', data: <?php echo json_encode($counts); ?>, backgroundColor: 'rgba(75, 192, 192, 0.2)', borderColor: 'rgba(75, 192, 192, 1)', borderWidth: 1 }] }, options: { scales: { y: { beginAtZero: true } } } }); 
            </script>
            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">Bulid-Tech</a>, All Right Reserved.
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                            Designed By <a href="https://htmlcodex.com">HTML Codex</a>
                            </br>
                            Distributed By <a class="border-bottom" href="https://themewagon.com" target="_blank">ThemeWagon</a>
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
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../lib/tempusdominus/js/moment.min.js"></script>
    <script src="../lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="../lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="../js/main.js"></script>
    <script>
    // Wait until the window is fully loaded
    window.onload = function() {
        // Hide the spinner
        document.getElementById("spinner").classList.remove("show");
    };

    function toggleTaskLine(event, taskId) {
            var checkbox = event.target;
            var taskDescription = document.getElementById("taskDescription" + taskId);
            if (checkbox.checked) {
                taskDescription.style.textDecoration = "line-through";
            } else {
                taskDescription.style.textDecoration = "none";
            }
        }
</script>
</body>

</html>