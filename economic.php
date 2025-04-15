<?php
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
    <style>
        .btn-close.btn-close-left {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            z-index: 2;
        }

        .modal-header {
            position: relative;
        }


        /* Add CSS styles for the link */
        .getAll {
            cursor: pointer;
        }
    </style>
</head>


<body>
    <!-- View bank account Modal -->
    <div class="modal fade" id="bankViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" dir="rtl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">הצגת חשבון בנק</h5>
                    <button type="button" class="btn-close btn-close-left" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="dataModal">

                    <div class="mb-3">
                        <label for="">מספר חשבון</label>
                        <p id="view_accountNumber" class="form-control"></p>
                    </div>
                    <div class="mb-3">
                        <label for="">מספר סניף</label>
                        <p id="view_branch" class="form-control"></p>
                    </div>
                    <div class="mb-3">
                        <label for="">בנק</label>
                        <p id="view_bank" class="form-control"></p>
                    </div>
                    <div class="mb-3">
                        <label for="">בבעלות</label>
                        <p id="view_owner" class="form-control"></p>
                    </div>
                    <div class="mb-3">
                        <label for="">מספר זהב</label>
                        <p id="view_gold" class="form-control"></p>
                    </div>
                    <div class="mb-3">
                        <label for="">כתובת</label>
                        <p id="view_address" class="form-control"></p>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                    <button type="button" id="copyButton" class="btn btn-info" data-bs-dismiss="modal">העתק</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit bank account Modal -->
    <div class="modal fade" id="bankEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" dir="rtl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">עריכת חשבון בנק</h5>
                    <button type="button" class="btn-close btn-close-left" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateBank">
                    <div class="modal-body">

                        <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                        <div class="mb-3">
                            <label for="">מספר חשבון</label>
                            <input type="number" name="accountNumber" id="accountNumber" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="">מספר סניף</label>
                            <input type="number" name="branch" id="branch" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="">בנק</label>
                            <select class="form-select" name="bank" id="bank" aria-label="Floating label select example" required>
                                <option>בנק דיסקונט לישראל בע"מ</option>
                                <option>בנק הפועלים בע"מ</option>
                                <option>בנק "יהב" לעובדי המדינה בע"מ</option>
                                <option>בנק ירושלים בע"מ</option>
                                <option>בנק לאומי לישראל בע"מ</option>
                                <option>וואן זירו הבנק הדיגיטלי בע"מ</option>
                                <option>בנק מזרחי טפחות בע"מ</option>
                                <option>בנק מסד בע"מ</option>
                                <option>בנק מרכנתיל דיסקונט בע"מ</option>
                                <option>הבנק הבינלאומי הראשון לישראל בע"מ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="">בבעלות</label>
                            <input type="text" name="owner" id="owner" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="">מספר זהב</label>
                            <input type="text" name="gold" id="gold" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="">כתובת</label>
                            <input type="text" name="address" id="address" class="form-control" />
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                        <button type="submit" class="btn btn-primary">עדכן חשבון</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add bank account Modal -->
    <div class="modal fade" id="bankAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" dir="rtl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">הוספת חשבון בנק</h5>
                    <button type="button" class="btn-close btn-close-left" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addBank">
                    <div class="modal-body">

                        <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                        <div class="mb-3">
                            <label for="">מספר חשבון</label>
                            <input type="number" name="accountNumber" id="accountNumber" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="">מספר סניף</label>
                            <input type="number" name="branch" id="branch" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="">בנק</label>
                            <select class="form-select" name="bank" id="bank" aria-label="Floating label select example" required>
                                <option>בנק דיסקונט לישראל בע"מ</option>
                                <option>בנק הפועלים בע"מ</option>
                                <option>בנק "יהב" לעובדי המדינה בע"מ</option>
                                <option>בנק ירושלים בע"מ</option>
                                <option>בנק לאומי לישראל בע"מ</option>
                                <option>וואן זירו הבנק הדיגיטלי בע"מ</option>
                                <option>בנק מזרחי טפחות בע"מ</option>
                                <option>בנק מסד בע"מ</option>
                                <option>בנק מרכנתיל דיסקונט בע"מ</option>
                                <option>הבנק הבינלאומי הראשון לישראל בע"מ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="">בבעלות</label>
                            <input type="text" name="owner" id="owner" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="">מספר זהב</label>
                            <input type="text" name="gold" id="gold" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label for="">כתובת</label>
                            <input type="text" name="address" id="address" class="form-control" />
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                        <button type="submit" class="btn btn-primary">הוסף חשבון</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- View incomes and expenses Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" dir="rtl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ריכוז הכנסות והוצאות</h5>
                    <button type="button" class="btn-close btn-close-left" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="get" action="generateEconomicpdf.php" target="_blank">
                    <div class="modal-body">

                        <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                        <div class="mb-3">
                            <label for="">ריכוז עבור:</label>
                            <select class="form-select" id="type" name="type" aria-label="Floating label select example" required>
                                <option>הכנסות</option>
                                <option>הוצאות</option>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="">מתאריך</label>
                            <input type="date" name="fromDate" id="fromDate" class="form-control" required />
                        </div>

                        <div class="mb-3">
                            <label for="">עד תאריך</label>
                            <input type="date" name="toDate" id="toDate" class="form-control" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                        <button type="submit" class="btn btn-primary" name="pdf_report_generate">אישור</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add income Modal -->
    <div class="modal fade" id="incomeAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" dir="rtl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">הוספת הכנסה</h5>
                    <button type="button" class="btn-close btn-close-left" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addIncome">
                    <div class="modal-body">
                        <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                        <div class="mb-3">
                            <label for="">עבור</label>
                            <textarea type="text" name="details" id="details" class="form-control" required></textarea> <!-- Add closing tag here -->
                        </div>

                        <div class="mb-3">
                            <label for="">סכום</label>
                            <input type="number" name="price" id="price" class="form-control" required />
                        </div>

                        <div class="mb-3">
                            <label for="">תאריך</label>
                            <input type="date" name="date" id="date" class="form-control" required /> <!-- Changed id to "date" -->
                        </div>

                        <div class="mb-3">
                            <label for="">קטגוריה</label>
                            <select class="form-select" name="category" id="category" aria-label="Floating label select example" required>
                                <option>פרויקטים</option>
                                <option>רכבים</option>
                                <option>ספקים</option>
                                <option>עובדים</option>
                                <option>קבלני משני</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="">פרויקט</label>
                            <select class="form-select" id="projectId" name="projectId" aria-label="Floating label select example">
                                <option value="-1" selected>בחר/י</option>
                                <?php
                                $projectQuery = "SELECT * FROM project";
                                $projectResult = mysqli_query($conn, $projectQuery);

                                if ($projectResult && mysqli_num_rows($projectResult) > 0) {
                                    while ($row = mysqli_fetch_assoc($projectResult)) {
                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                        <button type="submit" class="btn btn-primary">הוסף הכנסה</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add expense Modal -->
    <div class="modal fade" id="expenseAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" dir="rtl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">הוספת הוצאה</h5>
                    <button type="button" class="btn-close btn-close-left" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addExpense">
                    <div class="modal-body">
                        <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                        <div class="mb-3">
                            <label for="">עבור</label>
                            <textarea type="text" name="details" id="details" class="form-control" required></textarea> <!-- Add closing tag here -->
                        </div>

                        <div class="mb-3">
                            <label for="">סכום</label>
                            <input type="number" name="price" id="price" class="form-control" required />
                        </div>

                        <div class="mb-3">
                            <label for="">תאריך</label>
                            <input type="date" name="date" id="date" class="form-control" required /> <!-- Changed id to "date" -->
                        </div>

                        <div class="mb-3">
                            <label for="">קטגוריה</label>
                            <select class="form-select" name="category" id="category" aria-label="Floating label select example" required>
                                <option>פרויקטים</option>
                                <option>רכבים</option>
                                <option>ספקים</option>
                                <option>עובדים</option>
                                <option>קבלני משני</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="">פרויקט</label>
                            <select class="form-select" id="projectId" name="projectId" aria-label="Floating label select example">
                                <option value="-1" selected>בחר/י</option>
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
                                $projectQuery = "SELECT * FROM project";
                                $projectResult = mysqli_query($conn, $projectQuery);

                                if ($projectResult && mysqli_num_rows($projectResult) > 0) {
                                    while ($row = mysqli_fetch_assoc($projectResult)) {
                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                        <button type="submit" class="btn btn-primary">הוסף הוצאה</button>
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
                    <a href="economic.php" class="nav-item nav-link active"><i class="fa fa-university me-2"></i>כלכלי</a>
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

            <!-- Chart Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div dir="rtl" class="bg-light rounded h-100 p-4">
                            <label class="mb-4" style="color: #5BB498; font-weight: bold; font-size: 18px;">תשלומים עתידיים</label>

                            <div class="">
                                <table class="table" dir="rtl">
                                    <thead>
                                        <tr style="color: #5BB498;">
                                            <th scope="col">עבור</th>
                                            <th scope="col">פרויקט</th>
                                            <th scope="col">סטטוס</th>
                                            <th scope="col">סכום</th>
                                        </tr>
                                    </thead>
                                    <tbody style="color: black;">
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
                                        $query = "SELECT * FROM projectstep WHERE payment != 'שולם' AND (finish = 'נגמר' OR finish='בעבודה')";

                                        $query_run = mysqli_query($conn, $query);


                                        if (mysqli_num_rows($query_run) > 0) {
                                            foreach ($query_run as $step) {
                                                $query2 = "SELECT * FROM project WHERE id='" . $step["projectId"] . "'";
                                                $query_run2 = mysqli_query($conn, $query2);
                                                if (mysqli_num_rows($query_run2) > 0) {
                                                    foreach ($query_run2 as $project) {
                                                        $projectName = $project["name"];
                                                        $totalPrice = $project["totalPrice"];
                                                    }
                                                }
                                                $still = ((100 - $step["paymentPercent"]) / 100) * ($step["projectsPercent"] / 100) * $totalPrice;
                                        ?>
                                                <tr>
                                                    <td><?= $step["description"] ?> </td>
                                                    <td><?= $projectName ?></td>
                                                    <td><?= $step["payment"] ?></td>
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
                    <div class="col-sm-12 col-xl-6">

                        <div dir="rtl" class="bg-light rounded h-100 p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <label class="mb-4" style="color: #E45C67; font-weight: bold; font-size: 18px;">צ'יקים קרובים</label>
                                <a href="reports.php">הצג הכל</a>
                            </div>

                            <div class="">
                                <table class="table" dir="rtl">
                                    <thead>
                                        <tr style="color: #E45C67;">
                                            <th scope="col">עבור</th>
                                            <th scope="col">תאריך</th>
                                            <th scope="col">מס' צ'יק</th>
                                            <th scope="col">סכום</th>
                                        </tr>
                                    </thead>
                                    <tbody style="color: black;">
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
                                        $query = "SELECT * FROM checks WHERE `checkDate` >= NOW() - INTERVAL 1 DAY
                                            AND `checkDate` < NOW() + INTERVAL 10 DAY ORDER BY checkDate";

                                        $query_run = mysqli_query($conn, $query);


                                        if (mysqli_num_rows($query_run) > 0) {
                                            foreach ($query_run as $check) {

                                        ?>

                                                <tr>
                                                    <td><?= $check["forName"] ?> </td>
                                                    <td><?= date('d.m.Y', strtotime($check["checkDate"])) ?></td>
                                                    <td><?= $check["id"] ?></td>
                                                    <td><?= number_format($check["price"]) ?>₪</td>

                                                </tr>
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
                    <div class="bg-light text-center rounded p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4" dir="rtl">
                            <h6 class="mb-0" style="color: #DE9670; font-weight: bold; font-size: 19px;">ריכוז הכנסות והוצאות מאז חודש</h6>
                            <a class="getAll">הצג הכל</a>
                        </div>
                        <div class="table-responsive" dir="rtl">
                            <table dir="rtl" class="table text-start align-middle table-bordered table-hover mb-0" id="myTable2">
                                <thead>
                                    <tr class="text-white text-center" style="background-color: #0E2038;">
                                        <!--<th scope="col"><input class="form-check-input" type="checkbox"></th>-->
                                        <th scope="col">עבור</th>
                                        <th scope="col">טיפוס</th>
                                        <th scope="col">פרויקט</th>
                                        <th scope="col">תאריך</th>
                                        <th scope="col">הכנסה</th>
                                        <th scope="col">הוצאה</th>
                                        <th scope="col">הערות</th>
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

                                    // Calculate the date for one month ago from today
                                    $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));

                                    // Fetch data from the income table for the last month until today
                                    $incomeQuery = "SELECT * FROM income WHERE date >= '$oneMonthAgo' AND date <= CURDATE() ORDER BY date DESC";
                                    $incomeResult = mysqli_query($conn, $incomeQuery);

                                    // Fetch data from the expense table for the last month until today
                                    $expenseQuery = "SELECT * FROM expense WHERE date >= '$oneMonthAgo' AND date <= CURDATE() ORDER BY date DESC";
                                    $expenseResult = mysqli_query($conn, $expenseQuery);

                                    // Loop through the income records
                                    while ($income = mysqli_fetch_assoc($incomeResult)) {
                                        $query2 = "SELECT * FROM project WHERE id='" . $income["projectId"] . "'";
                                        $query_run2 = mysqli_query($conn, $query2);
                                        $projectName = "";
                                        if (mysqli_num_rows($query_run2) > 0) {
                                            foreach ($query_run2 as $project) {
                                                $projectName = $project["name"];
                                            }
                                        }
                                    ?>
                                        <tr class="text-center" style="color: black">
                                            <td><?= $income["details"] ?></td>
                                            <td><?= $income["category"] ?></td>
                                            <td><?= $projectName ?></td>
                                            <td style="color: #010000; font-weight: bold;"> <?= date('d.m.Y', strtotime($income["date"])) ?></td>
                                            <td style="color: #217C45;"> <?= number_format($income["price"]) ?></td>
                                            <td style="color: #FE0C0C;">-</td>
                                            <td></td>
                                        </tr>
                                    <?php
                                    }

                                    // Loop through the expense records
                                    while ($expense = mysqli_fetch_assoc($expenseResult)) {
                                    ?>
                                        <tr class="text-center" style="color: black">
                                            <td><?= $expense["details"] ?></td>
                                            <td><?= $expense["category"] ?></td>
                                            <td></td>
                                            <td style="color: #010000; font-weight: bold;"> <?= date('d.m.Y', strtotime($expense["date"])) ?></td>
                                            <td style="color: #217C45;">-</td>
                                            <td style="color: #FE0C0C;"> <?= number_format($expense["price"]) ?></td>
                                            <td></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4 d-flex justify-content-center align-items-center position-relative">
                            <button class="addIncome btn btn-primary btn-lg px-4 py-3 mx-2" type="button">
                                <span class="button-label">הכנסה</span>
                                <i class="fas fa-arrow-up"></i>
                            </button>
                            <button class="addExpense btn btn-danger btn-lg px-4 py-3 mx-2" type="button">
                                <span class="button-label">הוצאה</span>
                                <i class="fas fa-arrow-down"></i>
                            </button>
                        </div>
                    </div>



                    <div class="col-sm-12 col-xl-6">
                        <div dir="rtl" class="bg-light rounded h-100 p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <label class="mb-4" style="color: #397ED3; font-weight: bold; font-size: 19px;">חשבונות בנק</label>
                                <button class="addAccount btn btn-primary">הוספת חשבון</button>
                            </div>


                            <div class="">
                                <table class="table" dir="rtl" id="myTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">בעל</th>
                                            <th scope="col">מס'</th>

                                            <th scope="col"></th>
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
                                        $query = "SELECT * FROM bankaccount ORDER BY owner";

                                        $query_run = mysqli_query($conn, $query);


                                        if (mysqli_num_rows($query_run) > 0) {
                                            foreach ($query_run as $bank) {

                                        ?>
                                                <tr>
                                                    <td><?= $bank["owner"] ?></td>
                                                    <td><?= $bank["accountNumber"] ?></td>


                                                    <td>
                                                        <button type="button" value="<?= $bank['accountNumber']; ?>" class="viewBankBtn btn btn-info btn-sm">הצג</button>
                                                        <button type="button" value="<?= $bank['accountNumber']; ?>" class="editBankBtn btn btn-success btn-sm">עדכון</button>
                                                        <button type="button" value="<?= $bank['accountNumber']; ?>" class="deleteBankBtn btn btn-danger btn-sm">מחיקה</button>
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
                </div>
            </div>
            <!-- Chart End -->


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
        $(document).on('click', '.editBankBtn', function() {

            var account_number = $(this).val();
            //alert(account_number);

            $.ajax({
                type: "GET",
                url: "bankcode.php?account_number=" + account_number,
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status == 404) {

                        alert(res.message);
                    } else if (res.status == 200) {



                        $('#accountNumber').val(res.data.accountNumber);
                        $('#branch').val(res.data.branchNumber);
                        $('#bank').val(res.data.bankName);
                        $('#owner').val(res.data.owner);
                        $('#gold').val(res.data.goldNumber);
                        $('#address').val(res.data.address);

                        $('#bankEditModal').modal('show');

                    }
                }
            });

        });

        $(document).on('submit', '#updateBank', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("update_bank", true);

            $.ajax({
                type: "POST",
                url: "bankcode.php",
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

                        $('#bankEditModal').modal('hide');
                        $('#updateBank')[0].reset();

                        $('#myTable').load(location.href + " #myTable");

                    } else if (res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });

        $(document).on('click', '.viewBankBtn', function() {
            var account_number = $(this).val();
            $.ajax({
                type: "GET",
                url: "bankcode.php?account_number=" + account_number,
                success: function(response) {

                    var res = jQuery.parseJSON(response);
                    if (res.status == 404) {

                        alert(res.message);
                    } else if (res.status == 200) {

                        $('#view_accountNumber').text(res.data.accountNumber);
                        $('#view_branch').text(res.data.branchNumber);
                        $('#view_bank').text(res.data.bankName);
                        $('#view_owner').text(res.data.owner);
                        $('#view_gold').text(res.data.goldNumber);
                        $('#view_address').text(res.data.address);


                        $('#bankViewModal').modal('show');
                    }
                }
            });
        });

        $(document).on('click', '.deleteBankBtn', function(e) {
            e.preventDefault();

            if (confirm('האם אתה בטוח שברצונך למחוק את הנתונים האלה?')) {
                var account_number = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "bankcode.php",
                    data: {
                        'delete_account': true,
                        'account_number': account_number
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


        $(document).on('click', '.addAccount', function() {

            //alert(projectid);
            $('#bankAddModal').modal('show');
        });

        $(document).on('submit', '#addBank', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("add_account", true);

            $.ajax({
                type: "POST",
                url: "addBank.php",
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

                        $('#bankAddModal').modal('hide');
                        $('#addBank')[0].reset();

                        $('#myTable').load(location.href + " #myTable");


                    } else if (res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });

        $(document).on('click', '.getAll', function() {

            $('#reportModal').modal('show');
        });

        $(document).on('click', '.addIncome', function() {

            //alert(projectid);
            $('#incomeAddModal').modal('show');
        });

        $(document).on('submit', '#addIncome', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("add_income", true);

            $.ajax({
                type: "POST",
                url: "addIncome.php",
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

                        $('#incomeAddModal').modal('hide');
                        $('#addIncome')[0].reset();

                        $('#myTable2').load(location.href + " #myTable2");


                    } else if (res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });

        $(document).on('click', '.addExpense', function() {

            //alert(projectid);
            $('#expenseAddModal').modal('show');
        });

        $(document).on('submit', '#addExpense', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("add_expense", true);

            $.ajax({
                type: "POST",
                url: "addExpense.php",
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

                        $('#expenseAddModal').modal('hide');
                        $('#addExpense')[0].reset();

                        $('#myTable2').load(location.href + " #myTable2");


                    } else if (res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });
    </script>
    <script>
        // Get the button and all the data elements
        const copyButton = document.getElementById('copyButton');
        const dataElements = document.querySelectorAll('#dataModal p');

        // Add click event listener to the button
        copyButton.addEventListener('click', function() {
            // Concatenate the content of all the data elements
            let dataToCopy = '';
            dataElements.forEach(function(dataElement) {
                dataToCopy += dataElement.innerText + '\n';
            });

            // Create a new textarea element to hold the data temporarily
            const textarea = document.createElement('textarea');
            textarea.value = dataToCopy;

            // Append the textarea to the document
            document.body.appendChild(textarea);

            // Select the content of the textarea
            textarea.select();
            textarea.setSelectionRange(0, 99999); // For mobile devices

            // Copy the selected text
            document.execCommand('copy');

            // Remove the textarea from the document
            document.body.removeChild(textarea);

            // Provide visual feedback or notify the user that the data has been copied
            alert('פרטי החשבון הועתקו בהצלחה!');
        });
    </script>



</body>

</html>