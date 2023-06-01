<?php 
$conn = require __DIR__ . "/database.php";

$id = $_POST['id'];

$select = "SELECT * FROM project WHERE id = '$id'";
$query = mysqli_query($conn, $select);




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
<html lang="en" >

<head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="UTF-8">
    <title>DASHMIN - Bootstrap Admin Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

     <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>


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
            max-height: 350px; /* Set the desired maximum height for the table */
            direction: ltr; /* Set the text direction to right-to-left */
        }
        .table-container table {
            direction: rtl; /* Set the table direction back to left-to-right */
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
    </style>

</head>

<body>
     <!-- Payment Modal -->
     <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" dir="rtl">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">דיווח על תשלום</h5>
                    <button type="button" class="btn-close btn-close-left" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="insertPayment">
                    <div class="modal-body">

                        <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                        <input type="hidden" name="projectid" id="projectid" >

                        <label for="">הסכום</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">₪</span>
                            <input type="text" name="price" id="price" class="form-control" />
                        </div>

                        <div class="mb-3">
                            <label for="">עבור % מהשלב</label>
                            <input type="text" name="details" id="details" class="form-control" ></input>
                        </div>

                        <div class="mb-3">
                            <label for="">תאריך התשלום</label>
                            <input type="date" name="paymentDate" id="paymentDate" class="form-control" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                        <button type="submit" class="btn btn-primary">דיווח</button>
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
        <div class="sidebar pe-4 pb-3" >
            <nav class="navbar bg-light navbar-light" >
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
                    <a href="home.php" class="nav-item nav-link "><i class="fa fa-home me-2"></i>ראשי</a>
                    <a href="projectsTable.php" class="nav-item nav-link"><i class="fa fa-map me-2 active"></i>פרויקטים</a>
                    <a href="bid.html" class="nav-item nav-link"><i class="fa fa-superscript"></i>הצעת מחיר</a>
                    <a href="economic.php" class="nav-item nav-link"><i class="fa fa-university me-2"></i>כלכלי</a>
                    <a href="inventory.php" class="nav-item nav-link"><i class="fa fa-cubes me-2"></i>מחסן</a>
                    <a href="addShift.html" class="nav-item nav-link"><i class="fa fa-book me-2"></i>דיווח משמרת</a>
                    <a href="reports.php" class="nav-item nav-link"><i class="far fa-file-alt me-2 me-2"></i>דוחות</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-plus-square me-2"></i>הוספה</a>
                        <div class="dropdown-menu bg-transparent border-0">
                        <a href="addEmployee.html" class="dropdown-item">עובד</a>
                            <a href="addClient.html" class="dropdown-item">לקוח</a>
                            <a href="addMaterial.html" class="dropdown-item">חומר</a>
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

            
            <!-- Form Start -->
           
            <div class="container-fluid pt-4 px-4" dir="rtl">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                        <form action="updateproject.php" method="get">
                            <button type="submit" name="submit" class="btn btn-primary border-0" style="float: left; background-color: rgba(54, 162, 235, 1);"><i class="fa fa-check me-2"></i>&nbsp עדכון </button>
                            <h6 class="mb-4" id="name" style="color: black; font-size: 20px;"></h6>

                            <input type="hidden" name="id" id="id" value="" ></input>
                           
                                <div class="col-lg-4 col-xlg-3 col-md-5">
                                    <div >
                                        <div class="mb-4">
                                            <div class="mb-3" style="text-align:center;"> <img src="img/2.jpg"  width="300" height="200" class="mb-3"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">שם הפרויקט</label>
                                    <input type="text" class="form-control" id="projectName" name="projectName"style="font-weight: bold; color: black;"
                                    > 
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">המזמין </label>
                                    <input type="text" class="form-control" id="clientName" name="clientName" style="font-weight: bold; color: black;"
                                     > 
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">כתובת </label>
                                    <input type="text" class="form-control" id="address" name="address" style="font-weight: bold; color: black;"
                                    > 
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">תאריך התחלת הפרויקט</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate" style="font-weight: bold; color: black;"
                                >
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">תאריך סיום משוערך</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate" style="font-weight: bold; color: black;"
                                >
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
        
        <div class="bg-light rounded h-100 p-4">
    <h3 class="mb-4" style="color: black;  font-size: 18px;">שלבי הפרויקט</h3>
    <div class="d-flex align-items-center justify-content-between mb-4"></div>
    <div class="chart-container" style="width: 300px; height: 250px; display: flex; justify-content: center; align-items: center;">
        <canvas id="incomeChart"></canvas>
    </div>
    <br>
    <br>


        <div class="table-container">
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
                <tbody>
                    <?php
                    $i = 1;
                    $conn = require __DIR__ . "/database.php";
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
                                <td><button type="button" value="<?= $id ?>" class="insertPaymentBtn btn btn-primary border-0" style="background-color: #F15156;"><i class="fas fa-piggy-bank"></i></button> </td>
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
            <button type="button" value="<?= $id ?>" class="btn btn-primary border-0" style="background-color:  rgba(54, 162, 235, 1);">הוספת שלב/ים</button>
            
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
                                    <h3 class="mb-4" style="color: black;  font-size: 18px;">קבצים מצורפים</h3>
                                    <?php
                                    if (mysqli_num_rows($fileResult) > 0) {
                                        while ($file = mysqli_fetch_assoc($fileResult)) {
                                            $extension = strtolower(pathinfo($file['filename'], PATHINFO_EXTENSION));
                                            $iconClass = ($extension === 'pdf') ? 'far fa-file-pdf' : 'far fa-file';
                                            echo '<div class="file-panel">';
                                            echo '<div class="file-icon"><i class="' . $iconClass . ' fa-3x" style="color: red;"></i></div>';
                                            echo '<div class="file-details">';
                                            echo '<p><a href="' . $file['filename'] . '">' . basename($file['filename']) . '</a></p>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo 'No files found for this project.';
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

       
    </script>

    <script>
        $(document).on('click', '.insertPaymentBtn', function () {
            
            var projectid = $(this).val();
            //alert(projectid);
            $('#paymentModal').modal('show');
        });

        $(document).on('submit', '#insertPayment', function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("insert_payment", true);

            $.ajax({
                type: "POST",
                url: "insertPayment.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    
                    var res = jQuery.parseJSON(response);
                    if(res.status == 422) {
                        $('#errorMessageUpdate').removeClass('d-none');
                        $('#errorMessageUpdate').text(res.message);

                    }else if(res.status == 200){

                        $('#errorMessageUpdate').addClass('d-none');

                        alertify.set('notifier','position', 'top-right');
                        alertify.success(res.message);
                        
                        $('#paymentModal').modal('hide');
                        $('#insertPayment')[0].reset();

                        
                    }else if(res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });


    </script>

<script>
    // Get the element by its ID
    var penefitElement = document.getElementById("penefit");

    // Set the number value
    var number = "<?php echo $total; ?>"; // Replace with your actual number

    // Add commas to the number and update the element content
    penefitElement.textContent = Number(number).toLocaleString() + "₪" ;
</script>

<script>
    // Get the element by its ID
    var penefitElement = document.getElementById("expenses");

    // Set the number value
    var number = "<?php echo $totalexpenses; ?>"; // Replace with your actual number

    // Add commas to the number and update the element content
    penefitElement.textContent = Number(number).toLocaleString() + "₪" ;
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
    function initializeAutocomplete() {
        var locationInput = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(locationInput);
    }
  
    // Call the initializeAutocomplete function when the page loads
    google.maps.event.addDomListener(window, 'load', initializeAutocomplete);
    </script>


</body>

</html>

