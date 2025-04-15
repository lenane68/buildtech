<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = require __DIR__ . "/../database.php";

session_start();

if (!isset($_SESSION["email"])) {
    exit();
}

$email = mysqli_real_escape_string($conn, $_SESSION['email']);
$query = "SELECT * FROM account WHERE email='$email'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $name = $row['userName'];
    $role = $row['role'];
} else {
    $name = '';
    $role = '';
}

$leads_query = "SELECT * FROM leads";
$leads_result = mysqli_query($conn, $leads_query);

?>
<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="utf-8">
    <title>ניהול לידים</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
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
                    <a href="homecrm.php" class="nav-item nav-link"><i class="fa fa-home me-2"></i>לוח בקרה</a>
                    <a href="leads.php" class="nav-item nav-link active"><i class="fa fa-user me-2"></i>ניהול לידים</a>
                    <a href="clients.php" class="nav-item nav-link"><i class="fa fa-users me-2"></i>ניהול לקוחות</a>
                    <a href="users.php" class="nav-item nav-link"><i class="fa fa-user-friends me-2"></i>ניהול משתמשים</a>
                    <a href="support.php" class="nav-item nav-link"><i class="fa fa-life-ring me-2"></i>תמיכה ועזרה</a>
                </div>
            </nav>
        </div>

        <div class="content">
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

            <div class="container-fluid pt-4 px-4" dir="rtl">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="bg-light rounded h-100 p-4">
                            <h3>ניהול לידים</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>שם</th>
                                            <th>אימייל</th>
                                            <th>טלפון</th>
                                            <th>סטטוס</th>
                                            <th>פעולות</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($lead = mysqli_fetch_assoc($leads_result)) { ?>
                                            <tr>
                                                <td><?php echo $lead['name']; ?></td>
                                                <td><?php echo $lead['email']; ?></td>
                                                <td><?php echo $lead['phone']; ?></td>
                                                <td><?php echo $lead['status']; ?></td>
                                                <td>
                                                    <button>ערוך</button>
                                                    <button>מחק</button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">Build-Tech</a>, All Right Reserved.
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            Designed By <a href="https://htmlcodex.com">HTML Codex</a><br>
                            Distributed By <a class="border-bottom" href="https://themewagon.com" target="_blank">ThemeWagon</a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
        </div>
    </div>

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
        window.onload = function() {
            document.getElementById("spinner").classList.remove("show");
        };
    </script>
</body>
</html>
