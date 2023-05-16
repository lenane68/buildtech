<?php
    $conn = require __DIR__ . "/database.php";

    $query = "SELECT * FROM project";
    $query_run = mysqli_query($conn, $query);
                         
    $total=0;

    if(mysqli_num_rows($query_run) > 0)
    { 
        foreach($query_run as $project)
        {
            $total++;
        }
    }

    $query2 = "SELECT * FROM employee";
    $query_run2 = mysqli_query($conn, $query2);
                         
    $totalEmployee=0;

    if(mysqli_num_rows($query_run2) > 0)
    { 
        foreach($query_run2 as $employee)
        {
            $totalEmployee++;
        }
    }

    $query3 = "SELECT * FROM expense WHERE MONTH(date) = MONTH(now())
    and YEAR(date) = YEAR(now())";
    $query_run3 = mysqli_query($conn, $query3);
                         
    $totalexpenses=0;

    if(mysqli_num_rows($query_run3) > 0)
    { 
        foreach($query_run3 as $expense)
        {
            $totalexpenses+=$expense["price"];
        }
    }

    $query4 = "SELECT * FROM income WHERE MONTH(date) = MONTH(now())
    and YEAR(date) = YEAR(now())";
    $query_run4 = mysqli_query($conn, $query4);
                         
    $totalincomes=0;

    if(mysqli_num_rows($query_run4) > 0)
    { 
        foreach($query_run4 as $income)
        {
            $totalincomes+=$income["price"];
        }
    }

    if(isset($_POST["submit"])){
        
        $description = $_POST['description'];

        $stmt = $conn->prepare("insert into tasks(description) values(?)");
        $stmt->bind_param("s", $description);

        $execval = $stmt->execute();
        if($execval){
            echo "Adding successfully...";
        } else {
            die($conn->error . " " . $conn->errno);
        }
        echo $execval;
        $stmt->close();
       

    }

    if(isset($_POST["delete"])){
        
        $id = $_POST['id'];

        $query = "UPDATE tasks SET done='1' WHERE id='$id'";
        $query_run = mysqli_query($conn, $query);

        if($query_run)
        {
            echo "Delete successfully...";
        }
        else
        {
            die($conn->error . " " . $conn->errno);
        }
       

    }
?>
<?php  $conn = require __DIR__ . "/database.php";?>
<!DOCTYPE html>
<html lang="en">

<head>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['חודש', 'הכנסות', 'הוצאות', 'רווח'],
          <?php
            $query="select * FROM (select * from income UNION ALL select * from expense)";
            $res=mysqli_query($conn,$query);
            while($data=mysqli_fetch_array($res)){
              $month = date('F', strtotime($data['date']));
              //$year=$data['id'];
              $sale=$data['price'];
              $expenses=$data['price'];
              $profit=$data['price'];
           ?>
           ['<?php echo $month;?>',<?php echo $sale;?>,<?php echo $expenses;?>,<?php echo $profit;?>],   
           <?php   
            }
           ?> 
        ]);

        var options = {
          chart: {
            title: '',
            subtitle: 'הכנסות, הוצאות ורווח',
          },
          bars: 'vertical' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
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
                    <a href="home.html" class="nav-item nav-link active"><i class="fa fa-home me-2"></i>ראשי</a>
                    <a href="projectsTable.html" class="nav-item nav-link"><i class="fa fa-map me-2"></i>פרויקטים</a>
                    <a href="bid.html" class="nav-item nav-link"><i class="fa fa-superscript"></i>הצעת מחיר</a>
                    <a href="economic.html" class="nav-item nav-link"><i class="fa fa-university me-2"></i>כלכלי</a>
                    <a href="inventory.html" class="nav-item nav-link"><i class="fa fa-cubes me-2"></i>מחסן</a>
                    <a href="addShift.html" class="nav-item nav-link"><i class="fa fa-book me-2"></i>דיווח משמרת</a>
                    <a href="reports.html" class="nav-item nav-link"><i class="far fa-file-alt me-2 me-2"></i>דוחות</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-plus-square me-2"></i>הוספה</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="addEmployee.html" class="dropdown-item">עובד</a>
                            <a href="addClient.html" class="dropdown-item">לקוח</a>
                            <a href="addMaterial.html" class="dropdown-item">חומר</a>
                            <a href="addProject.html" class="dropdown-item">פרויקט</a>
                            <a href="addException.html" class="dropdown-item">חריגה</a>
                            
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-edit me-2"></i>עריכה & מחיקה</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="editEmployee.html" class="dropdown-item">עובד</a>
                            <a href="editClient.html" class="dropdown-item">לקוח</a>
                            <a href="editMaterial.html" class="dropdown-item">חומר</a>
                            <a href="editProject.html" class="dropdown-item">פרויקט</a>
                            <a href="editShift.html" class="dropdown-item">משמרת</a>
                            <a href="editException.html" class="dropdown-item">חריגה</a>
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


            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">מס' פרויקטים </p>
                                <h6 id="projectsNumber" class="mb-0"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">מס' עובדים</p>
                                <h6 id="totalEmployee" class="mb-0"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-area fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">הוצאות החודש</p>
                                <h6 id="totalexpenses" class="mb-0"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-pie fa-3x text-primary"></i>
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
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light text-center rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <a href="">הצג הכל</a>
                                <h6 class="mb-0">קצב גידול לקוחות</h6>
                            </div>
                          
                            <div id="barchart_material"></div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light text-center rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <a href="">הצג הכל</a>
                                <h6 class="mb-0"> קצב גידול הכנסות</h6>
                            </div>
                            <canvas id="salse-revenue"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sales Chart End -->


            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4" dir="rtl">
                        <h6 class="mb-0">פרויקטים אחרונים</h6>
                        <a href="projectsTable.php">הצג הכל</a>
                    </div>
                    <div class="table-responsive">
                        <table dir="rtl" class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <!--<th scope="col"><input class="form-check-input" type="checkbox"></th>-->
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
                                 $query = "SELECT * FROM project";
                          
                                 $query_run = mysqli_query($conn, $query);
                         
                                $i=-1;
                               if(mysqli_num_rows($query_run) > 0)
                               {
                                   foreach($query_run as $project)
                                   {
                                    $i++;
                                    if ($i == 5) {
                                        break;
                                      }
                                       ?>
                                <tr>
                                    <!--<td><input class="form-check-input" type="checkbox"></td>-->
                                    <td><?= $project["startDate"] ?> </td>
                                    <td><?= $project["clientName"] ?></td>
                                    <td><?= $project["address"] ?></td>
                                    <td>30%</td>
                                    <td>700,000₪</td>
                        
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
                                <iframe class="position-relative rounded w-100 h-100"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3487739.32553939!2d32.83681830135883!3d31.386689395772862!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1500492432a7c98b%3A0x6a6b422013352cba!2sIsrael!5e0!3m2!1sen!2sbd!4v1676362338089!5m2!1sen!2sbd" 
                                frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
                                tabindex="0"></iframe>
                            </div>
                    
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-4">
                        <div class="h-100 bg-light rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <a href="">הצג הכל</a>
                                <h6 class="mb-0">לוח שנה</h6>
                            
                            </div>
                            <div id="calender"></div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-4">
                        <div class="h-100 bg-light rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
        
                            <a href="">הצג הכל</a>
                                <h6 class="mb-0">רשימת מטלות</h6>
                            </div>

                            <form action="" method="post">
                            <div class="d-flex mb-2">
                                <input class="form-control bg-transparent" type="text" placeholder="הזן משימה" name="description" id="description">
                                <button type="submit" name="submit" class="btn btn-primary ms-2">הוספה</button>
                            </div>
                            </form>
                            <?php
                                          $conn = require __DIR__ . "/database.php";
                                          $query = "SELECT * FROM tasks WHERE done='0'";
                                   
                                          $query_run = mysqli_query($conn, $query);
                                  
                                 
                                        if(mysqli_num_rows($query_run) > 0)
                                        {
                                            foreach($query_run as $task)
                                            {
                             
                                                ?>
                            <form method="post">
                            <div class="d-flex align-items-center border-bottom py-2">
                                <input class="form-check-input m-0" type="checkbox">
                                <div class="w-100 ms-3">
                                    <div class="d-flex w-100 align-items-center justify-content-between">
                                            <input type="hidden" name="id" id="id" value="<?= $task["id"];?>" ></input>
                                            <span><?= $task["description"] ?></span>
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
        document.addEventListener("DOMContentLoaded", function(event) { 
            var scrollpos = localStorage.getItem('scrollpos');
            if (scrollpos) window.scrollTo(0, scrollpos);
        });

        window.onbeforeunload = function(e) {
            localStorage.setItem('scrollpos', window.scrollY);
        };
    </script>

</body>

</html>