<?php
include '../includes/dbcon.php';
include '../Includes/session.php';

if (isset($_POST['take'])) {
    $className = $_POST['className'];
    $mode = $_POST['event'];
    $presentboys = $_POST['presentboys'];
    $absentboys = $_POST['absentboys'];
    $presentgirls = $_POST['presentgirls'];
    $absentgirls = $_POST['absentgirls'];
    $dateTaken = DATE("Y-m-d");
    $day = date('N', strtotime($dateTaken));
    
    $query = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
    $rs = $conn->query($query);
    $num = $rs->num_rows;
    $rows = $rs->fetch_assoc();
    $fullName = $rows['username'];
    $school = $rows['school'];
  
    $sql = "INSERT INTO atdetails (className,mode,presentboys,absentboys,presentgirls,absentgirls,day,school,date) 
            VALUES ('$className','$mode','$presentboys','$absentboys','$presentgirls','$absentgirls','$day','$school','$dateTaken')";
    mysqli_query($conn,$sql);
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
                xmlhttp = new XMLHttpRequest();
            } else {
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
    
    // Function to update absent counts when checking/unchecking students
    function updateAbsentCounts() {
        let checkedBoys = 0;
        let checkedGirls = 0;
        
        // Count checked boys and girls
        document.querySelectorAll('input[name="check[]"]:checked').forEach(function(checkbox) {
            const gender = checkbox.closest('tr').querySelector('.student-gender').textContent;
            if (gender === 'MALE') {
                checkedBoys++;
            } else {
                checkedGirls++;
            }
        });
        
        // Update the remaining absent counts
        const totalAbsentBoys = parseInt(document.getElementById('totalAbsentBoys').value);
        const totalAbsentGirls = parseInt(document.getElementById('totalAbsentGirls').value);
        
        const remainingAbsentBoys = totalAbsentBoys - checkedBoys;
        const remainingAbsentGirls = totalAbsentGirls - checkedGirls;
        
        document.getElementById('remainingAbsentBoys').textContent = remainingAbsentBoys;
        document.getElementById('remainingAbsentGirls').textContent = remainingAbsentGirls;
        
        // Highlight if counts don't match
        if (remainingAbsentBoys < 0 || remainingAbsentGirls < 0) {
            document.getElementById('countsSummary').className = 'alert alert-danger';
        } else {
            document.getElementById('countsSummary').className = 'alert alert-info';
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
  
        <div class='alert alert-success'>Data Taken Successfully! Then</div>
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Choose All Absent Students In <?php echo $className;?></h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Student in Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <?php
              $sql = "SELECT * FROM tblclass WHERE className = '$className'";
              $res = $conn->query($sql);

              while ($row = $res->fetch_assoc()) {
                  $totalS = $row["totalStudents"];
                  $totalPresent = $presentboys + $presentgirls;
                  $totalAbsent = $absentboys + $absentgirls;

                  if ($totalS == $totalPresent) {
                      echo '
                      <div class="alert alert-info" role="alert">
                          In '.$className.' all students are present (Total: '.$totalS.')
                      </div>
                      <a href="takeAttendance.php" class="btn btn-primary">Back To Take Attendance Of Another Class</a>';
                  } else {
                      echo '
                      <div id="countsSummary" class="alert alert-info">
                          <strong>Attendance Summary:</strong><br>
                          Total Students: '.$totalS.'<br>
                          Present: '.$totalPresent.' (Boys: '.$presentboys.', Girls: '.$presentgirls.')<br>
                          Absent: '.$totalAbsent.' (Boys: '.$absentboys.', Girls: '.$absentgirls.')<br>
                          <strong>Remaining Absent:</strong> 
                          Boys: <span id="remainingAbsentBoys">'.$absentboys.'</span>, 
                          Girls: <span id="remainingAbsentGirls">'.$absentgirls.'</span>
                      </div>
                      
                      <input type="hidden" id="totalAbsentBoys" value="'.$absentboys.'">
                      <input type="hidden" id="totalAbsentGirls" value="'.$absentgirls.'">
                      
                      <form action="attend.php" method="post">
                          <div class="card mb-4">
                              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                  <h6 class="m-0 font-weight-bold text-primary">Mark Absent Students</h6>
                              </div>
                              <div class="table-responsive p-3">
                                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                      <thead class="thead-light">
                                          <tr>
                                              <th>#</th>
                                              <th>FullName</th>
                                              <th>Gender</th>
                                              <th>Class</th>
                                              <th>Absent?</th>
                                          </tr>
                                      </thead>
                                      <input type="hidden" name="mode" value="'.$mode.'">
                                      <input type="hidden" name="className" value="'.$className.'">
                                      <input type="hidden" name="presentboys" value="'.$presentboys.'">
                                      <input type="hidden" name="presentgirls" value="'.$presentgirls.'">
                                      <tbody>';
                      
                      $query = "SELECT tblstudents.Id, tblclass.className, tblclassarms.classArmName, 
                                tblclassarms.Id AS classArmId, tblstudents.name, tblstudents.gender, 
                                tblstudents.admissionNumber, tblstudents.dateCreated
                                FROM tblstudents
                                JOIN tblclass ON tblclass.Id = tblstudents.classId
                                INNER JOIN tblclassarms ON tblclassarms.Id = tblstudents.classArmId
                                WHERE tblclass.className = '$className'";
                      
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn = 0;
                      
                      if($num > 0) { 
                          while ($rows = $rs->fetch_assoc()) {
                              $sn++;
                              echo '
                              <tr>
                                  <td>'.$sn.'</td>
                                  <td>'.$rows["name"].'</td>
                                  <td class="student-gender">'.$rows["gender"].'</td>
                                  <td>'.$rows["className"].'</td>
                                  <td>
                                      <input name="check[]" type="checkbox" value="'.$rows["Id"].'" 
                                      class="form-control" onclick="updateAbsentCounts()">
                                  </td>
                              </tr>';
                          }
                      } else {
                          echo '<div class="alert alert-danger" role="alert">No Record Found!</div>';
                      }
                      
                      echo '
                                      </tbody>
                                  </table>
                                  <br>
                                  <button type="submit" name="sav" class="btn btn-primary">Save Attendance</button>
                              </div>
                          </div>
                      </form>';
                  }
              }
              ?>
            </div>
          </div>
        </div>
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
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable();
      $('#dataTableHover').DataTable();
    });
  </script>
</body>
</html>