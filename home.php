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

/* by lena*/

/*$query_notify1 = "SELECT * FROM car where testDate >= (DATE(NOW()) - INTERVAL 30 DAY)";
$query_notify2 = "SELECT * FROM checks where checkDate >= (DATE(NOW()) - INTERVAL 30 DAY)";

$result_car = $conn->query($query_notify1);
$result_checks = $conn->query($query_notify2);

if ($result_car->num_rows > 0) {
    while ($row_car = $result_car->fetch_assoc()) {
        $stmt = $conn->prepare("insert into notification(title, full_message) values(?, ?)");
        $full_message = "תאריך הטסט ברכב שמספרו : " . $row_car["number"] . "הוא : " . $row_car["testDate"] . "";
        $stmt->bind_param("isis", "טסט רכב", $full_message);
    }
}
//******************** */

$query = "SELECT * FROM project";
$query_run = mysqli_query($conn, $query);

$total = 0;

if (mysqli_num_rows($query_run) > 0) {
    foreach ($query_run as $project) {
        $total++;
    }
}

$query2 = "SELECT * FROM employee WHERE Active = 1";
$query_run2 = mysqli_query($conn, $query2);

$totalEmployee = 0;

if (mysqli_num_rows($query_run2) > 0) {
    foreach ($query_run2 as $employee) {
        $totalEmployee++;
    }
}

$query3 = "SELECT * FROM expense WHERE MONTH(date) = MONTH(now())
    and YEAR(date) = YEAR(now())";
$query_run3 = mysqli_query($conn, $query3);

$totalexpenses = 0;

if (mysqli_num_rows($query_run3) > 0) {
    foreach ($query_run3 as $expense) {
        $totalexpenses += $expense["price"];
    }
}

$query4 = "SELECT * FROM income WHERE MONTH(date) = MONTH(now())
    and YEAR(date) = YEAR(now())";
$query_run4 = mysqli_query($conn, $query4);

$totalincomes = 0;

if (mysqli_num_rows($query_run4) > 0) {
    foreach ($query_run4 as $income) {
        $totalincomes += $income["price"];
    }
}

$addresses = array();

$sql = "SELECT address FROM project WHERE finishDate > now()"; // Replace with your table name
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $addresses[] = $row["address"];
    }
}

if (isset($_POST["submit"])) {

    $description = $_POST['description'];

    $stmt = $conn->prepare("insert into tasks(description) values(?)");
    $stmt->bind_param("s", $description);

    $execval = $stmt->execute();
    if ($execval) {
        echo "Adding successfully...";
    } else {
        die($conn->error . " " . $conn->errno);
    }
    echo $execval;
    $stmt->close();

    // Redirect to the same page to prevent re-submission
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

if (isset($_POST["delete"])) {

    $id = $_POST['id'];

    $query = "UPDATE tasks SET done='1' WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        echo "Delete successfully...";
    } else {
        die($conn->error . " " . $conn->errno);
    }

    // Redirect to the same page to prevent re-submission
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}
// Query to retrieve incomes and order by date
$incomeQuery = "SELECT SUM(price) AS total_price, DATE_FORMAT(date, '%Y-%m') AS month FROM income GROUP BY month ORDER BY date ASC";
$incomeResult = $conn->query($incomeQuery);

// Check if the query was successful
if ($incomeResult) {
    // Fetch rows from the result set
    while ($incomeRow = $incomeResult->fetch_assoc()) {
        $incomes[] = [
            'date' => $incomeRow['month'],
            'price' => $incomeRow['total_price']
        ];
    }

    // Free the result set
    $incomeResult->free();
} else {
    // Handle error
    echo 'Error executing query: ' . $conn->error;
}

// Query to retrieve expenses and order by date
$expenseQuery = "SELECT SUM(price) AS total_price, DATE_FORMAT(date, '%Y-%m') AS month FROM expense GROUP BY month ORDER BY date ASC";
$expenseResult = $conn->query($expenseQuery);

// Check if the query was successful
if ($expenseResult) {
    // Fetch rows from the result set
    while ($expenseRow = $expenseResult->fetch_assoc()) {
        $expenses[] = [
            'date' => $expenseRow['month'],
            'price' => $expenseRow['total_price']
        ];
    }

    // Free the result set
    $expenseResult->free();
} else {
    // Handle error
    echo 'Error executing query: ' . $conn->error;
}


// Prepare the data for the chart
$chartData = [];

// Iterate over the incomes array and calculate the total income for each month
foreach ($incomes as $income) {
    $date = date('Y-m', strtotime($income['date']));
    $price = $income['price'];

    if (!isset($chartData[$date])) {
        $chartData[$date] = ['income' => $price, 'expense' => 0];
    } else {
        $chartData[$date]['income'] += $price;
    }
}

// Iterate over the expenses array and calculate the total expense for each month
foreach ($expenses as $expense) {
    $date = date('Y-m', strtotime($expense['date']));
    $price = $expense['price'];

    if (!isset($chartData[$date])) {
        $chartData[$date] = ['income' => 0, 'expense' => $price];
    } else {
        $chartData[$date]['expense'] += $price;
    }
}

// Calculate the revenue for each month
foreach ($chartData as &$data) {
    $data['revenue'] = $data['income'] - $data['expense'];
}

// Prepare the labels and data for the chart
$labels = [];
$incomeData = [];
$expenseData = [];
$revenueData = [];

foreach ($chartData as $date => $data) {
    $labels[] = date('F Y', strtotime($date));
    $incomeData[] = $data['income'];
    $expenseData[] = $data['expense'];
    $revenueData[] = $data['revenue'];
}

// Convert the data arrays to JSON format
$labelsJSON = json_encode($labels);
$incomeDataJSON = json_encode($incomeData);
$expenseDataJSON = json_encode($expenseData);
$revenueDataJSON = json_encode($revenueData);


// ...pie chart
// Prepare and execute the query to fetch income data from the "income" table
// Prepare and execute the query to fetch expense data from the "expense" table
$query = "SELECT category, SUM(price) AS total_price FROM expense GROUP BY category";
$result = $conn->query($query);

// Fetch the expense data from the result set
$expenseData = [];
while ($row = $result->fetch_assoc()) {
    $expenseData[] = $row;
}

// Prepare the data for the pie chart
$categories = [];
$prices = [];
$colors = [


    'rgba(255, 206, 86, 0.6)',
    'rgba(75, 192, 192, 0.6)',
    'rgba(255, 99, 132, 0.6)',
    'rgba(153, 102, 255, 0.6)',
    'rgba(255, 159, 64, 0.6)',
    'rgba(54, 162, 235, 0.6)',
    // Add more colors as needed
];

foreach ($expenseData as $index => $row) {
    $categories[] = $row['category'];
    $prices[] = $row['total_price'];
    $colors[] = $colors[$index % count($colors)]; // Assign unique color to each category
}

// Generate the chart data in JSON format
$chartData = [
    'labels' => $categories,
    'datasets' => [
        [
            'data' => $prices,
            'backgroundColor' => $colors,
        ],
    ],
];

// Encode the chart data as JSON
$jsonChartData = json_encode($chartData);



?>

<?php $conn = require __DIR__ . "/database.php";

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
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="utf-8">
    <title>BuildTech</title>
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

    <script src="https://maps.googleapis.com/maps/api/js?callback=initMap&key=AIzaSyD4pla3F8iMPajljQ3XL2GM5Tbs6G7T5Y0" defer></script>

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
                    <a href="home.php" class="nav-item nav-link active"><i class="fa fa-home me-2"></i>ראשי</a>
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


            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-building fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">מס' פרויקטים </p>
                                <h6 id="projectsNumber" class="mb-0"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-briefcase fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">עובדים פעילים</p>
                                <h6 id="totalEmployee" class="mb-0"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-pie fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">הוצאות החודש</p>
                                <h6 id="totalexpenses" class="mb-0"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-area fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">הכנסות החודש</p>
                                <h6 id="totalincomes" class="mb-0"></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sale & Revenue End -->


            <!-- Sales Chart Start -->
            <div class="container-fluid pt-4 px-4 text-end">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light text-center rounded p-4" style="height: 400px;">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <a href=""></a>
                                <h6 class="mb-0" style="font-weight: bold; font-size: 18px;">הכנסות, הוצאות ורווח</h6>
                            </div>
                            <canvas id="barChart" style="height: 250px; width: 100%;"></canvas>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light text-center rounded p-4" style="height: 400px;">
                            <div class="d-flex align-items-center justify-content-between mb-4" style="height: 5px;">
                                <a href=""></a>
                                <h6 class="mb-0" style="font-weight: bold; font-size: 17px;">התפלגות הוצאה לפי קטגוריות</h6>
                            </div>
                            <canvas id="incomeChart" style="height: 100px; width: 50%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>





            <!-- Sales Chart End -->


            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4" dir="rtl">
                        <h6 class="mb-0" style="font-weight: bold; font-size: 18px;">פרויקטים אחרונים</h6>
                        <a href="projectsTable.php">הצג הכל</a>
                    </div>
                    <div class="table-responsive">
                        <table dir="rtl" class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-white text-center" style="background-color: #2FA6D6;">
                                    <!--<th scope="col"><input class="form-check-input" type="checkbox"></th>-->
                                    <th scope="col">פרויקט</th>
                                    <th scope="col">תאריך התחלה</th>
                                    <th scope="col">לקוח</th>
                                    <th scope="col">כתובת</th>
                                    <th scope="col">אחוז סיום</th>
                                    <th scope="col">נשאר לתשלום</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
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
                                $query = "SELECT * FROM project ORDER BY startDate DESC";

                                $query_run = mysqli_query($conn, $query);

                                $i = -1;

                                if (mysqli_num_rows($query_run) > 0) {
                                    foreach ($query_run as $project) {
                                        $query2 = "SELECT * FROM projectstep WHERE projectId = '" . $project["id"] . "' AND finish = 'נגמר'";
                                        $query3 = "SELECT * FROM projectstep WHERE projectId = '" . $project["id"] . "' ";

                                        $query_run2 = mysqli_query($conn, $query2);
                                        $query_run3 = mysqli_query($conn, $query3);

                                        $totalPayment = 0;

                                        $totalpercent = 0;

                                        if (mysqli_num_rows($query_run2) > 0) {
                                            foreach ($query_run2 as $projectstep) {
                                                $totalpercent += $projectstep["projectsPercent"];
                                            }
                                        }

                                        if (mysqli_num_rows($query_run3) > 0) {
                                            foreach ($query_run3 as $projectstep) {
                                                $totalPayment += ($projectstep["paymentPercent"] / 100) * ($projectstep["projectsPercent"] / 100) * $project["totalPrice"];
                                            }
                                        }
                                        $still = $project["totalPrice"] - $totalPayment;
                                        $i++;
                                        if ($i == 5) {
                                            break;
                                        }
                                ?>
                                        <tr class="text-center" style="color: black">
                                            <!--<td><input class="form-check-input" type="checkbox"></td>-->
                                            <td><?= $project["name"] ?></td>
                                            <td><?= date('d.m.Y', strtotime($project["startDate"])) ?> </td>
                                            <td><?= $project["clientName"] ?></td>
                                            <td><?= $project["address"] ?></td>
                                            <td><?= $totalpercent ?>%</td>
                                            <td><?= number_format($still) ?>₪</td>

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
            <!-- Recent Sales End -->


            <!-- Widgets Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-md-6 col-xl-4">
                        <div class="bg-light rounded h-100 p-4">
                            <div id="map" style="height: 400px; width: 100%;"></div>
                        </div>
                    </div>




                    <div class="col-sm-12 col-md-6 col-xl-4">
                        <div class="h-100 bg-light rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <a href=""> </a>
                                <h6 class="mb-0">לוח שנה</h6>

                            </div>
                            <div id="calender"></div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-4">
                        <div class="h-100 bg-light rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <a href=""></a>
                                <h6 class="mb-0">רשימת מטלות</h6>
                            </div>

                            <form action="" method="post">
                                <div class="d-flex mb-2">
                                    <input class="form-control bg-transparent" type="text" placeholder="הזן משימה" name="description" id="description">
                                    <button type="submit" name="submit" class="btn btn-primary ms-2" onclick="submitForm(event)">הוספה</button>
                                </div>
                            </form>

                            <?php
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
                            $query = "SELECT * FROM tasks WHERE done='0'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $task) {
                            ?>
                                    <form method="post">
                                        <div class="d-flex align-items-center border-bottom py-2">
                                            <input class="form-check-input m-0" type="checkbox" onchange="toggleTaskLine(event, <?php echo $task["id"]; ?>)">
                                            <div class="w-100 ms-3">
                                                <div class="d-flex w-100 align-items-center justify-content-between">
                                                    <input type="hidden" name="id" id="id" value="<?= $task["id"]; ?>">
                                                    <span id="taskDescription<?= $task["id"]; ?>"><?= $task["description"]; ?></span>
                                                    <button type="submit" name="delete" class="btn btn-sm"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Widgets End -->


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
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
        //projects number
        var projectsNumber = "<?php echo $total; ?>";
        $('#projectsNumber').html(projectsNumber);
        //employees number
        var totalEmployee = "<?php echo $totalEmployee; ?>";
        $('#totalEmployee').html(totalEmployee);
        //expenses month
        var totalexpenses = "<?php echo $totalexpenses; ?>";
        $('#totalexpenses').html(totalexpenses);
        var paragraph = document.getElementById("totalexpenses");
        var text = document.createTextNode(" ₪");
        paragraph.appendChild(text);
        //totalincomes
        var totalincomes = "<?php echo $totalincomes; ?>";
        $('#totalincomes').html(totalincomes);
        var paragraph = document.getElementById("totalincomes");
        var text = document.createTextNode(" ₪");
        paragraph.appendChild(text);
    </script>

    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 6, // Adjust the initial zoom level as needed
                center: new google.maps.LatLng(31.5, 34.8), // Set the initial map center
            });

            // Use PHP to generate a JavaScript array from PHP array
            var addresses = <?php echo json_encode($addresses); ?>;

            geocodeAddresses(addresses, map);
        }

        function geocodeAddresses(addresses, map) {
            var geocoder = new google.maps.Geocoder();

            for (var i = 0; i < addresses.length; i++) {
                geocoder.geocode({
                    'address': addresses[i]
                }, function(results, status) {
                    if (status === 'OK') {
                        var marker = new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location,
                        });
                    } else {
                        console.error('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }
        }
    </script>

    <script>
        // Retrieve the data from PHP variables
        var labels = <?php echo $labelsJSON; ?>;
        var incomeData = <?php echo $incomeDataJSON; ?>;
        var expenseData = <?php echo $expenseDataJSON; ?>;
        var revenueData = <?php echo $revenueDataJSON; ?>;

        // Create the bar chart using Chart.js
        var ctx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'הכנסות',
                    data: incomeData,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'הוצאות',
                    data: expenseData,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'רווח',
                    data: revenueData,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
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
    </script>

    <script>
        // Retrieve the projects number from your data source
        var totalexpenses = <?php echo $totalexpenses; ?>; // Replace with your actual projects number

        // Format the number with commas
        var formattedNumber = totalexpenses.toLocaleString();

        // Set the formatted number as the content of the <h6> element
        document.getElementById("totalexpenses").textContent = formattedNumber;
    </script>

    <script>
        // Retrieve the projects number from your data source
        var totalincomes = <?php echo $totalincomes; ?>; // Replace with your actual projects number

        // Format the number with commas
        var formattedNumber = totalincomes.toLocaleString();

        // Set the formatted number as the content of the <h6> element
        document.getElementById("totalincomes").textContent = formattedNumber;
    </script>
    <script>
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