
<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';




?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
   <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
           <?php include "Includes/topbar.php";?>
        <!-- Topbar -->
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><?php echo $s?> Dean Of Study Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
          <!-- Students Card -->
          <?php 
                      $qm = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
                      $rs = $conn->query($qm);
                      $num = $rs->num_rows;
                      $rows = $rs->fetch_assoc();
                      $school = $rows['school'];
          

$query = "SELECT tblstudents.Id,tblstudents.classId
FROM tblstudents
INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
WHERE tblclass.school = '$school'
";
$rs = $conn->query($query);
$num = $rs->num_rows;?>

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Students</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $num;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 20.4%</span>
                        <span>Since last month</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Class Card -->
             <?php 
                                   $qi = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
                                   $rs = $conn->query($qi);
                                   $num = $rs->num_rows;
                                   $rows = $rs->fetch_assoc();
                                   $school = $rows['school'];
             
$query1=mysqli_query($conn,"SELECT * from tblclass WHERE school='$school'");                       
$class = mysqli_num_rows($query1);
?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Classes</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $class;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                        <span>Since last month</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chalkboard fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            

            

                               



                        <!-- Terms Card  -->
            <?php 
                                               $qb = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
                                               $rs = $conn->query($qb);
                                               $num = $rs->num_rows;
                                               $rows = $rs->fetch_assoc();
                                               $school = $rows['school'];
            
            $query1=mysqli_query($conn,"SELECT * from tblteachers WHERE school='$school'");                       
            $termonly = mysqli_num_rows($query1);
            ?>
                        <div class="col-xl-3 col-md-6 mb-4">
                          <div class="card h-100">
                            <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                  <div class="text-xs font-weight-bold text-uppercase mb-1">Teachers</div>
                                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $termonly;?></div>
                                  <div class="mt-2 mb-0 text-muted text-xs">
                                    <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                    <span>Since last years</span> -->
                                  </div>
                                </div>
                                <div class="col-auto">
                                  <i class="fas fa-users fa-2x text-info"></i>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        </div>
          <!--Row-->


        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <?php include 'includes/footer.php';?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>  
</body>

</html>