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

$sqli_notify = "SELECT * FROM notification WHERE DATE(date) >= (DATE(NOW()) - INTERVAL 90 DAY) ORDER BY date DESC LIMIT 5";

$result_notify = $conn->query($sqli_notify);

$query_notify1 = "SELECT * FROM car where testDate <= (DATE(NOW()) + INTERVAL 30 DAY) and testDate >= DATE(NOW())";
$query_notify2 = "SELECT * FROM checks where checkDate <= (DATE(NOW()) + INTERVAL 30 DAY) and checkDate >= DATE(NOW())";

$result_car = $conn->query($query_notify1);
$result_checks = $conn->query($query_notify2);

if ($result_car->num_rows > 0) {
    while ($row_car = $result_car->fetch_assoc()) {
        $stmt = $conn->prepare("INSERT INTO notification (id, title, full_message) VALUES (?, ?, ?)");

        $id = (int) $row_car["number"];
        $title = "טסט רכב";
        $full_message = "תאריך סיום הטסט ברכב שמספרו : " . $row_car["number"] . " הוא : " . $row_car["testDate"];

        $stmt->bind_param("iss", $id, $title, $full_message);

        $stmt->execute();
    }
}

if ($result_checks->num_rows > 0) {
    while ($row_checks = $result_checks->fetch_assoc()) {
        $stmt = $conn->prepare("INSERT INTO notification (id, title, full_message) VALUES (?, ?, ?)");

        $id = (int) $row_checks["id"];
        $title = "פרעון צק";
        $full_message = "התאריך לפירעון הצק שמספרו : " . $row_checks["id"] . " הוא : " . $row_car["checkDate"];

        $stmt->bind_param("iss", $id, $title, $full_message);

        $stmt->execute();
    }
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
    <!-- View Shift Modal -->
    <div class="modal fade" id="shiftViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" dir="rtl">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">הצגת משמרת</h5>
                    <button type="button" class="btn-close btn-close-left" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="">תאריך המשמרת</label>
                        <p id="view_workDate" class="form-control"></p>
                    </div>

                    <div class="mb-3">
                        <label for="">שם עובד </label>
                        <p id="view_employeeName" class="form-control"></p>
                    </div>

                    <div class="mb-3">
                        <label for="">סוג יום עבודה</label>
                        <p id="view_dayType" class="form-control"></p>
                    </div>
                    <div class="mb-3">
                        <label for="">שעות נוספות</label>
                        <p id="view_hours" class="form-control"></p>
                    </div>
                    <div class="mb-3">
                        <label for=""> על החשבון</label>
                        <p id="view_onAccount" class="form-control"></p>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Shift Modal -->
    <div class="modal fade" id="shiftEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" dir="rtl">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">עריכת משמרת</h5>
                    <button type="button" class="btn-close btn-close-left" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateShift">
                    <div class="modal-body">

                        <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                        <div class="mb-3">

                            <input type="hidden" name="id" id="id" class="form-control" />
                        </div>

                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="workDate" name="workDate" placeholder="">
                            <label for="floatingPassword" class="position-absolute top-0 end-0">תאריך משמרת</label>

                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select" id="employeeName" name="employeeName" aria-label="Floating label select example">

                                <?php
                                $employeeQuery = "SELECT fullName FROM employee";
                                $employeeResult = mysqli_query($conn, $employeeQuery);

                                if ($employeeResult && mysqli_num_rows($employeeResult) > 0) {
                                    while ($row = mysqli_fetch_assoc($employeeResult)) {
                                        echo '<option>' . $row['fullName'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <label for="floatingSelect" class="position-absolute top-0 end-0">עובד</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select" id="dayType" name="dayType" aria-label="Floating label select example">

                                <option value="יום שלם">יום שלם </option>
                                <option value="רק שעות"> רק שעות</option>
                                <option value="לא עבד"> לא עבד </option>
                            </select>
                            <label for="floatingSelect" class="position-absolute top-0 end-0">סוג יום עבודה</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="hours" name="hours" placeholder="">
                            <label for="floatingInput" class="position-absolute top-0 end-0">שעות עבודה נוספות </label>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">00.</span>
                            <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="על החשבון" id="onAccount" name="onAccount">
                            <span class="input-group-text">₪</span>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                        <button type="submit" class="btn btn-primary">עדכן המשמרת</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


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
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown"><i class="far fa-edit me-2"></i>עריכה & מחיקה</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="editEmployee.php" class="dropdown-item">עובד</a>
                            <a href="editClient.php" class="dropdown-item">לקוח</a>
                            <a href="editMaterial.php" class="dropdown-item">חומר</a>
                            <a href="editShift.php" class="dropdown-item active">משמרת</a>
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
                            <i class="fa fa-bell me-lg-2"></i>
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


            <div class="container-fluid pt-4 px-4" dir="rtl">
                <div class="row g-4">
                    <div class="bg-light rounded h-100 p-4">
                        <h5 class="mb-4">טבלת משמרות עובדים</h5>


                        <table class="table" id="myTable" dir="rtl">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">תאריך</th>
                                    <th scope="col">שם עובד</th>
                                    <th scope="col">סוג יום עבודה</th>
                                    <th scope="col">פעולה</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php

                                $query = "SELECT * FROM shift order by workDate desc";

                                $query_run = mysqli_query($conn, $query);


                                if (mysqli_num_rows($query_run) > 0) {
                                    foreach ($query_run as $shift) {

                                ?>
                                        <tr>
                                            <th scope="row"></th>
                                            <td> <?= $shift["workDate"] ?> </td>
                                            <td> <?= $shift["employeeName"] ?> </td>
                                            <td> <?= $shift["dayType"] ?> </td>
                                            <td>
                                                <button type="button" value="<?= $shift['id']; ?>" class="viewShiftBtn btn btn-info btn-sm">הצג</button>
                                                <button type="button" value="<?= $shift['id']; ?>" class="editShiftBtn btn btn-success btn-sm">עדכון</button>
                                                <button type="button" value="<?= $shift['id']; ?>" class="deleteShiftBtn btn btn-danger btn-sm">מחיקה</button>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>


    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
        $(document).on('click', '.editShiftBtn', function() {

            var id = $(this).val();


            $.ajax({
                type: "GET",
                url: "shiftcode.php?id=" + id,
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status == 404) {

                        alert(res.message);
                    } else if (res.status == 200) {


                        $('#id').val(res.data.id);
                        $('#workDate').val(res.data.workDate);
                        $('#employeeName').val(res.data.employeeName);
                        $('#dayType').val(res.data.dayType);
                        $('#hours').val(res.data.hours);
                        $('#onAccount').val(res.data.onAccount);


                        $('#shiftEditModal').modal('show');

                    }
                }
            });

        });

        $(document).on('submit', '#updateShift', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("update_shift", true);


            $.ajax({
                type: "POST",
                url: "shiftcode.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {

                    var res = jQuery.parseJSON(response);
                    if (res.status == 422) {
                        $('#errorMessageUpdate').removeClass('d-none');
                        $('#errorMessageUpdate').text(res.message);

                    } else if (res.status == 200) {

                        $('#errorMessageUpdate').addClass('d-none');

                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success(res.message);

                        $('#shiftEditModal').modal('hide');
                        $('#updateShift')[0].reset();

                        $('#myTable').load(location.href + " #myTable");

                    } else if (res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });

        $(document).on('click', '.viewShiftBtn', function() {
            var id = $(this).val();
            $.ajax({
                type: "GET",
                url: "shiftcode.php?id=" + id,
                success: function(response) {

                    var res = jQuery.parseJSON(response);
                    if (res.status == 404) {

                        alert(res.message);
                    } else if (res.status == 200) {

                        $('#view_workDate').text(res.data.workDate);
                        $('#view_employeeName').text(res.data.employeeName);
                        $('#view_dayType').text(res.data.dayType);
                        $('#view_hours').text(res.data.hours);
                        $('#view_onAccount').text(res.data.onAccount);



                        $('#shiftViewModal').modal('show');
                    }
                }
            });
        });

        $(document).on('click', '.deleteShiftBtn', function(e) {
            e.preventDefault();

            if (confirm('האם אתה בטוח שברצונך למחוק את הנתונים האלה?')) {
                var id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "shiftcode.php",
                    data: {
                        'delete_shift': true,
                        'id': id
                    },
                    success: function(response) {

                        var res = jQuery.parseJSON(response);
                        if (res.status == 500) {

                            alert(res.message);
                        } else {
                            alertify.set('notifier', 'position', 'top-right');
                            alertify.success(res.message);

                            $('#myTable').load(location.href + " #myTable");
                        }
                    }
                });
            }
        });
    </script>




</body>

</html>