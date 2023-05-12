<?php

 $con = require __DIR__ . "/database.php";

 $sqli = "SELECT * FROM car";

 $result = $con->query($sqli);

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
   
   
</head>

<body>
    <!-- View Report Modal -->
    <div class="modal fade" id="reportViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">הצגת חומר</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                            <div class="mb-3">
                            <label for="">מספר דו"ח </label>
                            <p  id="view_reportNumber" class="form-control" ></p>
                            </div>
                            
                            <div class="mb-3">
                            <label for="">מספר רכב </label>
                            <p  id="view_carNumber" class="form-control" ></p>
                            </div>

                            <div class="mb-3">
                                <label for="">תאריך הדו"ח</label>
                                <p  id="view_reportDate" class="form-control" ></p>
                            </div>
                            <div class="mb-3">
                                <label for="">הסכום</label>
                                <p id="view_price" class="form-control" ></p>
                            </div>
                            <div class="mb-3">
                                <label for=""> שולם</label>
                                <p id="view_paid" class="form-control" ></p>
                            </div>
                            <div class="mb-3">
                                <label for=""> הערות</label>
                                <p id="view_notes" class="form-control" ></p>
                            </div>
                            
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                </div>
            </div>
        </div>
    </div>

     <!-- Edit Report Modal -->
     <div class="modal fade" id="reportEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">עריכת חומר</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateReport">
                    <div class="modal-body">

                        <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                        <div class="mb-3">
                            <label for=""> מספר הדו"ח</label>
                            <input type="text" name="reportNumber" id="reportNumber" class="form-control" />
                        </div>
                       
                        <div class="form-floating mb-3">
                            <select class="form-select" name="carNumber" id="carNumber" 
                                aria-label="Floating label select example">
                                
                                <?php 
                                            foreach($result as $row)
                                            {
                                                echo '<option value="'.$row["number"].'">'.$row["number"].'</option>';
                                            }
                                ?>
                            </select>
                            <label for="carNumber">פרויקט</label>
                        </div>

                        <div class="mb-3">
                            <label for="">תאריך הדו"ח</label>
                            <input type="date" name="reportDate" id="reportDate" class="form-control" />
                        </div>


                        <label for="">הסכום</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">₪</span>
                            <input type="text" name="price" id="price" class="form-control" />
                        </div>

                        <div class="form-check form-switch">
                           <label class="form-check-label" for="paid">שולם</label>
                           <input class="form-check-input" type="checkbox" role="switch"
                             id="paid" name="paid">        
                         </div>

                         <div class="mb-3">
                            <label for=""> הערות</label>
                            <textarea type="text" name="notes" id="notes" class="form-control"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">סגור</button>
                        <button type="submit" class="btn btn-primary">עדכן דו"ח</button>
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
                    <a href="home.html" class="nav-item nav-link "><i class="fa fa-home me-2"></i>ראשי</a>
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
                            <a href="editClient.html" class="dropdown-item active">לקוח</a>
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


            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                          <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">טבלת דוחות תנועה</h6>
                        
                            
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">מספר דו"ח</th>
                                        <th scope="col">מספר רכב</th>
                                        <th scope="col">סכום</th>
                                        <th scope="col">פעולה</th>
                                    </tr>
                                </thead> 
                             
                                <tbody>   
                               <?php 

                                 $conn = require __DIR__ . "/database.php";
                                 $query = "SELECT * FROM report";
                          
                                 $query_run = mysqli_query($conn, $query);
                         
                        
                               if(mysqli_num_rows($query_run) > 0)
                               {
                                   foreach($query_run as $report)
                                   {
                    
                                       ?>
                                <tr>
                                    <th scope="row"></th>
                                    <td> <?= $report["reportNumber"] ?> </td>
                                    <td> <?= $report["carNumber"] ?> </td>
                                    <td> <?= $report["price"] ?> </td>
                                    <td>
                                            <button type="button" value="<?=$report['reportNumber'];?>" class="viewReportBtn btn btn-info btn-sm">הצג</button>
                                            <button type="button" value="<?=$report['reportNumber'];?>" class="editReportBtn btn btn-success btn-sm">עדכון</button>
                                            <button type="button" value="<?=$report['reportNumber'];?>" class="deleteReportBtn btn btn-danger btn-sm">מחיקה</button>
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
        $(document).on('click', '.editReportBtn', function () {
            
            var reportNumber = $(this).val();
            //alert(supplier_id);

            $.ajax({
                type: "GET",
                url: "reportcode.php?reportNumber=" + reportNumber,
                success: function (response) {
                    var res = jQuery.parseJSON(response);
                    if(res.status == 404) {

                        alert(res.message);
                    }else if(res.status == 200){
                        
                    
                    $('#reportNumber').val(res.data.reportNumber);   
                    $('#carNumber').val(res.data.carNumber);
                    $('#reportDate').val(res.data.reportDate);
                    $('#price').val(res.data.price);
                    $('#paid').val(res.data.paid);
                    $('#notes').val(res.data.notes);
                   

                    $('#reportEditModal').modal('show');
                    
                    }
                }
            });
              
        });

        $(document).on('submit', '#updateReport', function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("update_report", true);

            $.ajax({
                type: "POST",
                url: "reportcode.php",
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
                        
                        $('#reportEditModal').modal('hide');
                        $('#updateReport')[0].reset();

                        $('#myTable').load(location.href + " #myTable");

                    }else if(res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });

        $(document).on('click', '.viewReportBtn', function () {
            var reportNumber = $(this).val();
            $.ajax({
            type: "GET",
            url: "reportcode.php?reportNumber=" + reportNumber,
            success: function (response) {

            var res = jQuery.parseJSON(response);
            if(res.status == 404) {

                alert(res.message);
            }else if(res.status == 200){

                $('#view_reportNumber').text(res.data.reportNumber);
                $('#view_carNumber').text(res.data.carNumber);
                $('#view_reportDate').text(res.data.reportDate);
                $('#view_price').text(res.data.price);
                $('#view_paid').text(res.data.paid);
                $('#view_notes').text(res.data.notes);
          
              

                $('#reportViewModal').modal('show');
                }
            }
         });
        });

        $(document).on('click', '.deleteReportBtn', function (e) {
            e.preventDefault();

            if(confirm('האם אתה בטוח שברצונך למחוק את הנתונים האלה?'))
            {
                var reportNumber = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "reportcode.php",
                    data: {
                        'delete_report': true,
                        'reportNumber': reportNumber
                    },
                    success: function (response) {

                        var res = jQuery.parseJSON(response);
                        if(res.status == 500) {

                            alert(res.message);
                        }else{
                            alertify.set('notifier','position', 'top-right');
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