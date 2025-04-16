<?php

// @format
$conn = require __DIR__ . "/database.php";

session_start();

if (!isset($_SESSION["email"])) {
    // Redirect to the login page if the user is not logged in
    header('Location: index.php');
    exit();
}
include_once 'notify.php';
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


$id = $_POST['id'];

$select = "SELECT * FROM project WHERE id = '$id'";
$query = mysqli_query($conn, $select);

$_SESSION['project_id'] = $id;


while ($row = mysqli_fetch_array($query)) {
    $name = $row['name'];
    $address = $row['address'];
    $startDate = $row['startDate'];
    $endDate = $row['finishDate'];
    $finishDate = $row['finishDate'];
    $clientName = $row['clientName'];
}


$query2 = "SELECT * FROM income WHERE projectId='$id'";
$query_run = mysqli_query($conn, $query2);

$total = 0;

if (mysqli_num_rows($query_run) > 0) {
    foreach ($query_run as $payment) {
        $total += $payment["price"];
    }
}

$query2 = "SELECT * FROM expense WHERE projectId='$id'";
$query_run = mysqli_query($conn, $query2);

$totalexpenses = 0;

if (mysqli_num_rows($query_run) > 0) {
    foreach ($query_run as $payment) {
        $totalexpenses += $payment["price"];
    }
}

// Retrieve associated files for the project from the database
$fileQuery = "SELECT * FROM files WHERE project_name = '$name'";
$fileResult = mysqli_query($conn, $fileQuery);


// Query to retrieve project steps data
// ...pie chart
// Prepare and execute the query to fetch income data from the "income" table
$query = "SELECT projectsPercent, finish FROM projectstep WHERE projectId = '$id'";
$result = mysqli_query($conn, $query);

// Fetch the income data from the result set
$incomeData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $incomeData[] = $row;
}

// Prepare the data for the pie chart
$finishCount = 0;
$notFinishCount = 0;

foreach ($incomeData as $row) {
    if ($row['finish'] === "נגמר") {
        $finishCount += $row['projectsPercent'];
    } else {
        $notFinishCount += $row['projectsPercent'];
    }
}

// Generate the chart data in JSON format
$chartData = [
    'labels' => ['נגמר', 'לא נגמר'],
    'datasets' => [
        [
            'data' => [$finishCount, $notFinishCount],
            'backgroundColor' => [
                'rgba(54, 162, 235, 0.25)',
                'rgba(54, 162, 235, 1)',
                // Add more colors as needed
            ],
        ],
    ],
];

// Encode the chart data as JSON
$jsonChartData = json_encode($chartData);


?>


<!DOCTYPE html>
<html lang="en">

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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4pla3F8iMPajljQ3XL2GM5Tbs6G7T5Y0&libraries=places"></script>




    <style>
        .file-panel {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .file-details {
            flex-grow: 1;
            margin-right: 10px;
            text-align: right;
        }

        .file-details a {
            text-decoration: none;
            color: #333;
        }

        .file-details a:hover {
            text-decoration: underline;
            color: #1EB6C1;
        }

        .file-icon {
            flex-shrink: 0;
            text-align: right;
        }

        .table-container {
            overflow-y: auto;
            max-height: 350px;
            /* Set the desired maximum height for the table */
            direction: ltr;
            /* Set the text direction to right-to-left */
        }

        .table-container table {
            direction: rtl;
            /* Set the table direction back to left-to-right */
        }

        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }

        .my-div {
            border-bottom: 8px solid #55BF96;
            border-radius: 0.25rem;
        }

        .my-div2 {
            border-bottom: 8px solid #E04050;
            border-radius: 0.25rem;
        }

        .button-container {
            display: flex;
            gap: 10px;
            /* Adjust the gap as needed */
        }
    </style>

</head>

<body>

    <!-- Update Step Modal -->
    <div class="modal fade" id="updateStepModal" tabindex="-1" aria-labelledby="updateStepModalLabel" aria-hidden="true">
        <div class="modal-dialog" dir="rtl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStepModalLabel">עדכון</h5>
                    <button type="button" class="btn-close btn-close-left" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateStepForm">
                    <div class="modal-body">
                        <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>
                        <input type="hidden" name="updateStepId" id="updateStepId">
                        <div class="mb-3">
                            <label for="newFinish">עדכן סטטוס התקדמות שלב:</label>
                            <select class="form-control" id="newFinish" name="newFinish">
                                <option value="נגמר">נגמר</option>
                                <option value="בעבודה">בעבודה</option>
                                <option value="לא בוצע">לא בוצע</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                        <button type="submit" class="btn btn-primary">עדכן</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Add Step Modal -->
    <div class="modal fade" id="stepAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" dir="rtl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">הוספת שלב חדש</h5>
                    <button type="button" class="btn-close btn-close-left" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addStep">
                    <div class="modal-body">
                        <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                        <input type="hidden" name="projectid2" id="projectid2">


                        <div class="mb-3">
                            <label for="projectsPercent">אחוז מהפרויקט</label>
                            <input type="number" name="projectsPercent" id="projectsPercent" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="description">תיאור</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                        <button type="submit" class="btn btn-primary">הוסף שלב</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" dir="rtl">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">עדכון סטטוס תשלום</h5>
                    <button type="button" class="btn-close btn-close-left" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="insertPayment">
                    <div class="modal-body">

                        <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                        <input type="hidden" name="projectid" id="projectid">
                        <input type="hidden" name="projectstepid" id="projectstepid">

                        <div class="mb-3">
                            <label for="finish"> סטטוס התשלום</label>
                            <select class="form-control" id="payment" name="payment">
                                <option>שולם</option>
                                <option>שולם חלקי</option>
                                <option>לא שולם</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="">אחוז שולם מהשלב</label>
                            <input type="number" name="paymentPercent" id="paymentPercent" class="form-control"></input>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                        <button type="submit" class="btn btn-primary">עדכון</button>
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

                    <a href="#" class="sidebar-toggler flex-shrink-0 ms-2">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
            </nav>
            <!-- Navbar End -->


            <!-- Form Start -->

            <div class="container-fluid pt-4 px-4" dir="rtl">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <form id="updateProject">
                                <button type="submit" name="submit" class="btn btn-primary border-0" style="float: left; background-color: rgba(54, 162, 235, 1);"><i class="fa fa-check me-2"></i>&nbsp עדכון </button>
                                <h6 class="mb-4" id="name" style="color: black; font-size: 20px;"></h6>

                                <input type="hidden" name="id" id="id" value=""></input>

                                <div class="col-lg-4 col-xlg-3 col-md-5">
                                    <div>
                                        <div class="mb-4">
                                            <div class="mb-3" style="text-align:center;"> <img src="img/2.jpg" width="300" height="200" class="mb-3" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">שם הפרויקט</label>
                                    <input type="text" class="form-control" id="projectName" name="projectName" style="font-weight: bold; color: black;" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">המזמין </label>
                                    <input type="text" class="form-control" id="clientName" name="clientName" style="font-weight: bold; color: black;" require>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">כתובת </label>
                                    <input type="text" class="form-control" id="address" name="address" style="font-weight: bold; color: black;" require>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">תאריך התחלת הפרויקט</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate" style="font-weight: bold; color: black;" require>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">תאריך סיום משוערך</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate" style="font-weight: bold; color: black;" require>
                                </div>

                            </form>

                        </div>
                    </div>


                    <div class="col-sm-12 col-xl-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 my-div">
                                    <i class="fa fa-chart-line fa-3x" style="color: #55BF96;"></i>
                                    <div class="ms-3">
                                        <p class="mb-2">הכנסות</p>
                                        <h6 id="penefit" class="mb-0"></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 my-div2">
                                    <i class="fa fa-chart-bar fa-3x" style="color: #E04050;"></i>
                                    <div class="ms-3">
                                        <p class="mb-2">הוצאות</p>
                                        <h6 id="expenses" class="mb-0"></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="bg-light rounded p-4">

                        
                        <h3 class="mb-4" style="color: black;  font-size: 18px;">העלאת תמונות לבדיקת בטיחות</h3>
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 my-div">
                        <form id="imageUploadForm" enctype="multipart/form-data">
                        <input type="hidden" name="project_id" value="<?php echo $id; ?>">
    <input type="file" name="images[]" multiple class="form-control mb-3" required>
    <button type="submit" class="btn btn-primary border-0" style="background-color: rgba(54, 162, 235, 1);">
        <i class="fa fa-upload me-2"></i> העלאת תמונות לבדיקה
    </button>
</form>

<div id="uploadResponse" class="mt-3"></div>

</div>


                        </div>
                        <div class="bg-light rounded h-100 p-4">
                            <h3 class="mb-4" style="color: black;  font-size: 18px;">שלבי הפרויקט</h3>
                            <div class="d-flex align-items-center justify-content-between mb-4"></div>
                            <div class="chart-container" style="width: 300px; height: 250px; display: flex; justify-content: center; align-items: center;">
                                <canvas id="incomeChart"></canvas>
                            </div>
                            <br>
                            <br>


                            <div class="table-container" id="myTable">
                                <table class="table">
                                    <thead>
                                        <tr style="background-color: #95A29F;" class="text-white text-center">
                                            <th scope="col">#</th>
                                            <th scope="col">שלב</th>
                                            <th scope="col">אחוז</th>
                                            <th scope="col">שולם</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        $i = 1;
                                        $conn = require __DIR__ . "/database.php";

                                        if (!isset($_SESSION["email"])) {
                                            // Redirect to the login page if the user is not logged in
                                            header('Location: index.php');
                                            exit();
                                        }

                                        $sqli = "SELECT * FROM notification WHERE DATE(date) >= (DATE(NOW()) - INTERVAL 90 DAY) ORDER BY date DESC";
                                        $sqli2 = "SELECT * FROM notification WHERE DATE(date) >= (DATE(NOW()) - INTERVAL 90 DAY) ORDER BY date DESC LIMIT 5";

                                        $result = $conn->query($sqli);
                                        $result2 = $conn->query($sqli2);

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
                                        $query = "SELECT * FROM projectstep WHERE projectId='$id'";
                                        $query_run = mysqli_query($conn, $query);

                                        if (mysqli_num_rows($query_run) > 0) {
                                            while ($projectstep = mysqli_fetch_assoc($query_run)) {
                                        ?>
                                                <tr>
                                                    <th scope="row"><?= $i ?></th>
                                                    <td><?= $projectstep["description"] ?></td>
                                                    <td><?= $projectstep["projectsPercent"] ?>%</td>
                                                    <td><?= $projectstep["payment"] ?></td>
                                                    <td>
                                                        <div class="button-container">
                                                            <button type="button" value="<?= $projectstep["id"] ?>" class="insertPaymentBtn btn btn-primary border-0" style="background-color: #F15156;"><i class="fas fa-piggy-bank"></i></button>
                                                            <button type="button" value="<?= $projectstep["id"] ?>" class="deleteStepBtn btn btn-danger border-0" style="background-color: red;"><i class="fas fa-trash"></i></button>
                                                            <button type="button" value="<?= $projectstep["id"] ?>" class="updateStepBtn btn btn-success border-0" style="background-color: green;"><i class="fas fa-edit"></i></button>

                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php
                                                $i++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div class="d-flex justify-content-between">
                                <button type="button" value="<?= $id ?>" class="addStepBtn btn btn-primary border-0" style="background-color:  rgba(54, 162, 235, 1);">הוספת שלב/ים </button>

                            </div>
                        </div>
                    </div>




                    <?php
                    // Fetch the address from the database
                    //$address = "רחוב שטורמן, הרצליה, ישראל"; // Replace with your code to fetch the address from MySQL

                    // Construct the embedded map URL
                    $mapUrl = "https://www.google.com/maps/embed/v1/place?key=AIzaSyD4pla3F8iMPajljQ3XL2GM5Tbs6G7T5Y0&q=" . urlencode($address);

                    // Output the HTML with the embedded map
                    echo '
                                <div class="col-sm-12 col-xl-6">
                                        <div class="bg-light rounded h-100 p-4">
                                            <h3 class="mb-1" style="color: black;  font-size: 18px;">מיקום הפרויקט</h3>
                                            <br>
                                            <iframe class="position-relative rounded w-100" style="height: 300px;"
                                                src="' . $mapUrl . '"
                                                tabindex="0"></iframe>
                                        </div>
                                    </div>
                                    ';
                    ?>

                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="mb-0" style="color: black; font-size: 18px;">קבצים מצורפים</h3>
                                <button class="btn btn-success add-file-button" onclick="showFileInput()">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <input type="file" id="fileInput" style="display: none;" onchange="uploadFile(this)" accept=".pdf">

                            </div>
                            <?php
                            if (mysqli_num_rows($fileResult) > 0) {
                                while ($file = mysqli_fetch_assoc($fileResult)) {
                                    $extension = strtolower(pathinfo($file['filename'], PATHINFO_EXTENSION));
                                    $iconClass = ($extension === 'pdf') ? 'far fa-file-pdf' : 'far fa-file';
                            ?>
                                    <div class="file-panel" style="display: flex; align-items: center; justify-content: space-between;">
                                        <div class="file-details" style="display: flex; align-items: center;">
                                            <div class="file-icon">
                                                <i class="<?= $iconClass ?> fa-3x" style="color: red;"></i>
                                            </div>
                                            <p style="margin: 0;">
                                                <a href="<?= $file['filename'] ?>" target="_blank"><?= basename($file['filename']) ?></a>
                                            </p>
                                        </div>
                                        <a href="#" class="btn btn-danger delete-button" onclick="deleteFile(<?= $file['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                            <?php
                                }
                            } else {
                                echo 'אין קבצים עבור הפרויקט הזה.';
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Form End -->


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
        var name = "<?php echo $name; ?>";
        $('#name').html(name);
        document.getElementById("projectName").value = name;
        //$('#projectName').html(name);
        var address = "<?php echo $address; ?>";
        document.getElementById("address").value = address;
        var startDate = "<?php echo $startDate; ?>";
        document.getElementById("startDate").value = startDate;
        var endDate = "<?php echo $endDate; ?>";
        document.getElementById("endDate").value = endDate;
        var clientName = "<?php echo $clientName; ?>";
        document.getElementById("clientName").value = clientName;
        var projectId = "<?php echo $id; ?>";
        document.getElementById("id").value = projectId;
        document.getElementById("projectid").value = projectId;
        document.getElementById("projectid2").value = projectId;
    </script>

    <script>
        document.getElementById('imageUploadForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    const formData = new FormData(this);

    fetch('upload_handler.php', {
        method: 'POST',
        body: formData,
        credentials: 'include' // Important for session persistence
    })
    .then(response => response.text()) // Expect HTML response now
    .then(html => {
        const uploadResponse = document.getElementById('uploadResponse');
        uploadResponse.innerHTML = html; // Directly insert the HTML response
    })
    .catch(error => {
        document.getElementById('uploadResponse').innerHTML = 
            `<div class="alert alert-danger">Error: ${error.message}</div>`;
    });
});


        $(document).on('click', '.insertPaymentBtn', function() {

            var projectid = $(this).val();

            $('#projectstepid').val(projectid);

            $.ajax({
                type: "GET",
                url: "insertPayment.php?projectid=" + projectid,
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status == 404) {

                        alert(res.message);
                    } else if (res.status == 200) {



                        $('#payment').val(res.data.payment);
                        $('#paymentPercent').val(res.data.paymentPercent);


                        $('#paymentModal').modal('show');

                    }
                }
            });
        });

        $(document).on('submit', '#insertPayment', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("insert_payment", true);

            $.ajax({
                type: "POST",
                url: "insertPayment.php",
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

                        $('#paymentModal').modal('hide');
                        $('#insertPayment')[0].reset();

                        setTimeout(function() {
                            refreshPage();
                        }, 2000); // 2000 milliseconds = 2 seconds
                        //$('#myTable').load(location.href + " #myTable");


                    } else if (res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });

        $(document).on('click', '.addStepBtn', function() {
            $('#stepAddModal').modal('show');
        });

        $(document).on('submit', '#addStep', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("insert_step", true);

            $.ajax({
                type: "POST",
                url: "insertStep.php",
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

                        $('#stepAddModal').modal('hide');
                        $('#addStep')[0].reset();

                        //$('#myTable').load(location.href + " #myTable");
                        // Wait for 2 seconds before refreshing the page
                        setTimeout(function() {
                            refreshPage();
                        }, 2000); // 2000 milliseconds = 2 seconds
                    } else if (res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });
        // Assume that you have a click event handler for the "Delete Step" button
        $('.deleteStepBtn').on('click', function() {
            var deleteStepId = $(this).val(); // Get the step ID from the button's value attribute

            $.ajax({
                type: "POST",
                url: "deleteStep.php",
                data: {
                    deleteStepId: deleteStepId
                }, // Send the step ID as data
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        alertify.success(res.message);

                        //$('#myTable').load(location.href + " #myTable");
                        setTimeout(function() {
                            refreshPage();
                        }, 2000); // 2000 milliseconds = 2 seconds
                    } else {
                        alertify.error(res.message);
                    }
                }
            });
        });

        // JavaScript code to open the update step modal
        $('.updateStepBtn').on('click', function() {
            var stepId = $(this).val(); // Get the step ID from the button's value attribute

            // Populate the modal form fields with stepId data or perform any additional actions

            $('#updateStepId').val(stepId); // Assuming you have an input field with ID "updateStepId"


            // Get the current status value
            $.ajax({
                type: "POST",
                url: "fetchCurrentFinish.php", // Replace with the actual PHP file's URL
                data: {
                    stepId: stepId
                },
                success: function(response) {
                    var currentFinish = response;
                    $('#newFinish').val(currentFinish); // Set the selected value in the select element
                    $('#updateStepModal').modal('show'); // Open the modal
                }
            });
        });

        $('#updateStepForm').submit(function(event) {
            event.preventDefault(); // Prevent form from submitting normally
            var formData = $(this).serialize(); // Serialize the form data
            $.ajax({
                type: 'POST',
                url: 'updateFinishStatus.php', // Replace with the actual PHP file's URL
                data: formData,
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        // Update the table here
                        // You can either refresh the entire table or update the specific row

                        // Close the modal
                        $('#updateStepModal').modal('hide');
                        alertify.success(res.message);

                        setTimeout(function() {
                            refreshPage();
                        }, 2000); // 2000 milliseconds = 2 seconds
                        //$('#myTable').load(location.href + " #myTable");

                    } else {
                        // Handle error case
                        console.log(res.message);
                        alertify.error(res.message);
                    }
                }
            });
        });

        function deleteFile(fileId) {
            if (confirm('האם אתה פתוח שרוצה למחוק את הקובץ הזה?')) {
                // Make an AJAX request to deleteFile.php
                $.ajax({
                    url: 'deleteFile.php',
                    type: 'POST',
                    data: {
                        file_id: fileId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Optional: Update UI to reflect the deletion
                            $('#errorMessageUpdate').addClass('d-none');

                            alertify.set('notifier', 'position', 'top-right');
                            alertify.success("הקובץ נמחק בהצלחה");

                            setTimeout(function() {
                                refreshPage();
                            }, 2000); // 2000 milliseconds = 2 seconds
                        } else {
                            alert('שגיאה במחיקת הקובץ');
                        }
                    },
                    error: function() {
                        alert('שגיאה במחיקת הקובץ .');
                    }
                });
            }
        }

        function refreshPage() {
            window.location.reload();
        }

        function showFileInput() {
            const fileInput = document.getElementById('fileInput');
            fileInput.click();
        }

        function uploadFile(input) {
            const formData = new FormData();
            formData.append('pdfFile', input.files[0]);
            formData.append('projectName', '<?php echo $name; ?>'); // Replace with your PHP code

            fetch('insertFile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the JSON response data here
                    console.log(data);
                    $('#errorMessageUpdate').addClass('d-none');

                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success("הקובץ הועלה בהצלחה");

                    setTimeout(function() {
                        refreshPage();
                    }, 2000); // 2000 milliseconds = 2 seconds
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

    </script>

    <script>
        // Get the element by its ID
        var penefitElement = document.getElementById("penefit");

        // Set the number value
        var number = "<?php echo $total; ?>"; // Replace with your actual number

        // Add commas to the number and update the element content
        penefitElement.textContent = Number(number).toLocaleString() + "₪";
    </script>

    <script>
        // Get the element by its ID
        var penefitElement = document.getElementById("expenses");

        // Set the number value
        var number = "<?php echo $totalexpenses; ?>"; // Replace with your actual number

        // Add commas to the number and update the element content
        penefitElement.textContent = Number(number).toLocaleString() + "₪";
    </script>

    <script>
        // Retrieve the chart data from PHP
        var chartData = <?php echo $jsonChartData; ?>;

        // Create the pie chart
        var ctx = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });




        $(document).on('submit', '#updateProject', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("update_project", true);

            $.ajax({
                type: "POST",
                url: "updateproject.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {

                    var res = jQuery.parseJSON(response);
                    if (res.status == 422) {
                        $('#errorMessageUpdate').removeClass('d-none');
                        $('#errorMessageUpdate').text(res.message);
                        alertify.error(res.message);
                    } else if (res.status == 200) {

                        $('#errorMessageUpdate').addClass('d-none');

                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success(res.message);

                        // $('#updateProject')[0].reset();

                        //$('#myTable').load(location.href + " #myTable");

                    } else if (res.status == 500) {
                        $('#errorMessageUpdate').removeClass('d-none');
                        $('#errorMessageUpdate').text(res.message);

                        alertify.error(res.message);
                    }
                }
            });

        });
    </script>

    <script>
        function initializeAutocomplete() {
            var locationInput = document.getElementById('address');
            var autocomplete = new google.maps.places.Autocomplete(locationInput);
        }

        // Call the initializeAutocomplete function when the page loads
        google.maps.event.addDomListener(window, 'load', initializeAutocomplete);
    </script>


</body>

</html>