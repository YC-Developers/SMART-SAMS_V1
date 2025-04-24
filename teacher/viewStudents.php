<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Ensure session userId is set
if (!isset($_SESSION['userId'])) {
    die("Error: User not logged in.");
}
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

  <script>
    function classArmDropdown(str) {
      if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
      } else { 
        if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest();
        } else {
          // code for IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET","ajaxClassArms2.php?cid="+str,true);
        xmlhttp.send();
      }
    }
  </script>
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
            <h1 class="h3 mb-0 text-gray-800">All Students In <?php echo isset($s) ? htmlspecialchars($s) : 'Your Classes'; ?></h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Students in Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Students In Classes</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Class</th>
                            <th>Class Arm</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          // Query to fetch distinct students taught by the teacher
                          $query = "
                            SELECT DISTINCT ts.Id, tc.className, tca.classArmName, ts.name, ts.gender
                            FROM tblstudents ts
                            INNER JOIN tblclass tc ON tc.Id = ts.classId
                            INNER JOIN tblclassarms tca ON tca.Id = ts.classArmId
                            INNER JOIN class_lessons cl ON cl.class_id = tc.Id
                            INNER JOIN lessons l ON l.id = cl.lesson_id
                            WHERE l.teacher_id = ?
                            ORDER BY ts.name
                          ";

                          $stmt = $conn->prepare($query);
                          if (!$stmt) {
                              echo "<div class='alert alert-danger' role='alert'>Database error: " . htmlspecialchars($conn->error) . "</div>";
                              exit;
                          }

                          $stmt->bind_param("i", $_SESSION['userId']);
                          $stmt->execute();
                          $result = $stmt->get_result();
                          $sn = 0;

                          if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                              $sn++;
                              echo "
                                <tr>
                                  <td>$sn</td>
                                  <td>" . htmlspecialchars($row['name']) . "</td>
                                  <td>" . htmlspecialchars($row['gender']) . "</td>
                                  <td>" . htmlspecialchars($row['className']) . "</td>
                                  <td>" . htmlspecialchars($row['classArmName']) . "</td>
                                </tr>";
                            }
                          } else {
                            echo "
                              <div class='alert alert-danger' role='alert'>
                                No Record Found!
                              </div>";
                          }

                          $stmt->close();
                          ?>
                        </tbody>
                      </table>
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
      <?php include "Includes/footer.php";?>
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
  <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>