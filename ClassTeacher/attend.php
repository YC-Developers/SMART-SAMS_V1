<?php
include '../includes/dbcon.php';
include '../Includes/session.php'; 

if (isset($_POST['check'])) {
    $absentees = $_POST['check']; // Selected student IDs
    $mode = $_POST['mode'];
    $className = $_POST['className']; // Added class name from previous form
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

  <style>
    .reason-select {
      min-width: 200px;
    }
    .table-container {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      padding: 20px;
      margin: 20px auto;
      max-width: 95%;
    }
    .status-badge {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }
    .badge-absent {
      background-color: #f8d7da;
      color: #721c24;
    }
    .badge-late {
      background-color: #fff3cd;
      color: #856404;
    }
    .badge-present {
      background-color: #d4edda;
      color: #155724;
    }
  </style>

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

    // Function to update status display
    function updateStatusDisplay(selectElement) {
      const row = selectElement.closest('tr');
      const statusCell = row.querySelector('.status-display');
      const selectedValue = selectElement.value;
      
      // Remove all classes
      statusCell.className = 'status-display status-badge';
      
      // Add appropriate class based on selection
      if (selectedValue === 'Late') {
        statusCell.classList.add('badge-late');
        statusCell.textContent = 'Late';
      } else if (selectedValue === 'Present') {
        statusCell.classList.add('badge-present');
        statusCell.textContent = 'Present';
      } else if (selectedValue === 'Sick' || selectedValue === 'Absent' || selectedValue === 'Went Home') {
        statusCell.classList.add('badge-absent');
        statusCell.textContent = 'Absent';
      } else {
        statusCell.textContent = 'Not Set';
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
        
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Attendance Details for <?php echo $className; ?></h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active">Attendance Status</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
                  <h6 class="m-0 font-weight-bold text-white">Student Attendance Status</h6>
                </div>
                
                <div class="card-body">
                  <form action="save_absence.php" method="post">
                    <input type="hidden" name="mode" value="<?php echo $mode; ?>">
                    <input type="hidden" name="className" value="<?php echo $className; ?>">
                    
                    <div class="alert alert-info">
                      <i class="fas fa-info-circle"></i> Please set the status for each student. 
                      Mark students as <span class="badge-absent status-badge">Absent</span>, 
                      <span class="badge-late status-badge">Late</span>as appropriate.
                    </div>
                    
                    <div class="table-responsive">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Status</th>
                            <th>Reason (if absent)</th>
                            <th>Time Arrived (if late)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          foreach ($absentees as $key => $absenteeId) {
                              // Fetch student details
                              $query = "SELECT name, gender FROM tblstudents WHERE Id = '$absenteeId'";
                              $result = $conn->query($query);
                              $student = $result->fetch_assoc();
                              ?>
                              <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td>
                                  <?php echo $student['name']; ?>
                                  <input type="hidden" name="check[]" value="<?php echo $absenteeId; ?>">
                                  <input type="hidden" name="gender[]" value="<?php echo $student['gender']; ?>">
                                </td>
                                <td class="status-display status-badge">Not Set</td>
                                <td>
                                  <select name="status[]" class="form-control reason-select" onchange="updateStatusDisplay(this)" required>
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="Late">Late</option>
                                    <option value="Sick">Sick (Absent)</option>
                                    <option value="Absent">Absent (No Reason)</option>
                                    <option value="Went Home">Went Home (Absent)</option>
                                  </select>
                                </td>
                                <td>
                                  <input type="time" name="late_time[]" class="form-control" style="display: none;">
                                </td>
                              </tr>
                              <?php
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                    
                    <div class="text-center mt-4">
                      <button type="submit" name="save" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Save Attendance Records
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Footer -->
      <?php include "Includes/footer.php"; ?>
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
    $(document).ready(function() {
      $('#dataTable').DataTable();
      $('#dataTableHover').DataTable();
      
      // Show/hide late time input based on status selection
      $(document).on('change', 'select[name="status[]"]', function() {
        const timeInput = $(this).closest('tr').find('input[name="late_time[]"]');
        if ($(this).val() === 'Late') {
          timeInput.show();
          timeInput.prop('required', true);
        } else {
          timeInput.hide();
          timeInput.prop('required', false);
        }
      });
    });
  </script>
</body>
</html>
<?php
}
?>