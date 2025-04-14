<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Function to check if current time is morning (before 12 PM)
function isMorning() {
    return date('H') < 12;
}

// Add this at the top to handle form submission and validation
if(isset($_POST['take'])) {
    $className = $_POST['className'];
    $event = $_POST['event'];
    $presentBoys = $_POST['presentboys'];
    $absentBoys = $_POST['absentboys'];
    $presentGirls = $_POST['presentgirls'];
    $absentGirls = $_POST['absentgirls'];
    
    // Fetch class data again for validation
    $validationSql = "SELECT * FROM tblclass WHERE className='$className'";
    $validationRes = $conn->query($validationSql);
    $classData = $validationRes->fetch_assoc();
    
    $errors = array();
    
    // Validate event time
    if($event == 'morning' && !isMorning()) {
        $errors[] = "Morning prep can only be recorded before 12 PM";
    }
    if($event == 'evening' && isMorning()) {
        $errors[] = "Evening prep can only be recorded after 12 PM";
    }
    
    // Validate boys
    if(($presentBoys + $absentBoys) != $classData['boys']) {
        $errors[] = "Sum of present and absent boys must equal total boys (".$classData['boys'].")";
    }
    
    // Validate girls
    if(($presentGirls + $absentGirls) != $classData['girls']) {
        $errors[] = "Sum of present and absent girls must equal total girls (".$classData['girls'].")";
    }
    
    // Validate no negative numbers
    if($presentBoys < 0 || $absentBoys < 0 || $presentGirls < 0 || $absentGirls < 0) {
        $errors[] = "Attendance numbers cannot be negative";
    }
    
    // If no errors, proceed with submission
    if(empty($errors)) {
        // Your code to insert attendance records would go here
        // For now, we'll just show a success message
        echo "<script>alert('Attendance recorded successfully!');</script>";
    } else {
        // Display errors
        echo "<script>alert('".implode("\\n", $errors)."');</script>";
    }
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

  <style>
    .atvir {
      margin-left: 30px;
      margin-top: 30px;
      max-width: 800px;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .class-info {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 30px;
    }
    .class-info h2 {
      color: #2e59d9;
      margin-bottom: 10px;
    }
    .class-info h3 {
      color: #6c757d;
      font-size: 18px;
      margin-bottom: 5px;
    }
    .attendance-form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      font-weight: 600;
      color: #495057;
    }
    .form-control {
      border-radius: 5px;
      border: 1px solid #ced4da;
      padding: 10px 15px;
    }
    .form-control:focus {
      border-color: #2e59d9;
      box-shadow: 0 0 0 0.2rem rgba(46, 89, 217, 0.25);
    }
    .btn-primary {
      background-color: #2e59d9;
      border-color: #2e59d9;
      padding: 10px 25px;
      font-weight: 600;
      border-radius: 5px;
    }
    .btn-primary:hover {
      background-color: #1e3a8a;
      border-color: #1e3a8a;
    }
    .error {
      color: #dc3545;
      font-size: 14px;
      margin-top: 5px;
    }
    .time-indicator {
      font-size: 14px;
      color: #6c757d;
      margin-top: 5px;
    }
    .current-time {
      background: #e9ecef;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
      font-weight: 600;
      color: #495057;
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

    function validateAttendance(input, total, type) {
      const value = parseInt(input.value) || 0;
      const otherInputName = input.name === 'present' + type ? 'absent' + type : 'present' + type;
      const otherValue = parseInt(document.getElementsByName(otherInputName)[0].value) || 0;
      const maxValue = total;
      
      if (value < 0) {
        input.setCustomValidity("Value cannot be negative");
        return false;
      }
      
      if (value + otherValue > maxValue) {
        input.setCustomValidity(`Sum of present and absent ${type.toLowerCase()} cannot exceed ${maxValue}`);
        return false;
      }
      
      input.setCustomValidity("");
      return true;
    }

    function validateForm(totalBoys, totalGirls) {
      const event = document.getElementsByName('event')[0].value;
      const isMorningTime = new Date().getHours() < 12;
      
      // Validate event time
      if (event === 'morning' && !isMorningTime) {
        alert("Morning prep can only be recorded before 12 PM");
        return false;
      }
      if (event === 'evening' && isMorningTime) {
        alert("Evening prep can only be recorded after 12 PM");
        return false;
      }
      
      const presentBoys = parseInt(document.getElementsByName('presentboys')[0].value) || 0;
      const absentBoys = parseInt(document.getElementsByName('absentboys')[0].value) || 0;
      const presentGirls = parseInt(document.getElementsByName('presentgirls')[0].value) || 0;
      const absentGirls = parseInt(document.getElementsByName('absentgirls')[0].value) || 0;
      
      // Validate boys
      if (presentBoys + absentBoys !== totalBoys) {
        alert(`Sum of present and absent boys must equal ${totalBoys}`);
        return false;
      }
      
      // Validate girls
      if (presentGirls + absentGirls !== totalGirls) {
        alert(`Sum of present and absent girls must equal ${totalGirls}`);
        return false;
      }
      
      // Validate no negative numbers
      if (presentBoys < 0 || absentBoys < 0 || presentGirls < 0 || absentGirls < 0) {
        alert("Attendance numbers cannot be negative");
        return false;
      }
      
      return true;
    }
    
  // Function to update the current time display
  function updateCurrentTime() {
      const now = new Date();
      const timeString = now.toLocaleTimeString();
      const dateString = now.toLocaleDateString();
      document.getElementById('current-time').textContent = `Current Date: ${dateString} | Time: ${timeString}`;
      
      // Also update the event options if the period changes (morning/evening)
      const currentHour = now.getHours();
      const wasMorning = currentHour < 12;
      
      // Check again after 1 minute to update if period changed
      setTimeout(updateCurrentTime, 1000);
    }
    
    // Update event options based on current time
    function updateEventOptions() {
      const isMorning = new Date().getHours() < 12;
      const eventSelect = document.getElementById('event');
      
      // Clear existing options
      while (eventSelect.options.length > 0) {
        eventSelect.remove(0);
      }
      
      // Add default option
      const defaultOption = document.createElement('option');
      defaultOption.value = '';
      defaultOption.textContent = '--Select Event--';
      eventSelect.appendChild(defaultOption);
      
      // Add morning option if it's morning
      if (isMorning) {
        const morningOption = document.createElement('option');
        morningOption.value = 'morning';
        morningOption.textContent = 'Morning Prep';
        eventSelect.appendChild(morningOption);
      } else {
        const eveningOption = document.createElement('option');
        eveningOption.value = 'evening';
        eveningOption.textContent = 'Evening Prep';
        eventSelect.appendChild(eveningOption);
      }
    }
    
    // Initialize on page load
    window.onload = function() {
      updateCurrentTime(); // Start the clock
      updateEventOptions();
    };
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
        <div class="container-fluid">
          <div class="atvir">
            <?php
            $p = $_GET['data'];  // Example: "9002L4 SOFTWARE DEVELOPMENT B"
            $cleaned = preg_replace('/^\d+/', '', $p);
            $result = trim($cleaned);

            $sql = "SELECT * FROM tblclass WHERE className='$result'";
            $res = $conn->query($sql);

            while ($row = $res->fetch_assoc()) {
              echo '
              <div class="class-info">
                <h2>Class: '.htmlspecialchars($row['className']).'</h2>
                <div class="row">
                  <div class="col-md-6">
                    <h3>Total Students: '.htmlspecialchars($row['totalStudents']).'</h3>
                  </div>
                  <div class="col-md-6">
                    <h3>Boys: '.htmlspecialchars($row['boys']).' | Girls: '.htmlspecialchars($row['girls']).'</h3>
                  </div>
                </div>
              </div>
              
              <div class="current-time" id="current-time"></div>
              
              <form action="atdetails.php" method="post" onsubmit="return validateForm('.$row['boys'].', '.$row['girls'].')">
                <input type="hidden" name="className" value="'.htmlspecialchars($row['className']).'">
                
                <div class="form-group">
                  <label for="event">Prep Mode:</label>
                  <select name="event" required id="event" class="form-control">
                    <option value="">--Select Event--</option>';
                    
                    if (isMorning()) {
                        echo '<option value="morning">Morning Prep</option>';
                    } else {
                        echo '<option value="evening">Evening Prep</option>';
                    }
                    
                    echo '
                  </select>
                  <div class="time-indicator">';
                    if (isMorning()) {
                        echo 'Morning prep must be recorded before 12 PM';
                    } else {
                        echo 'Evening prep must be recorded after 12 PM';
                    }
                    echo '
                  </div>
                </div>
                
                <div class="attendance-form">
                  <div>
                    <div class="form-group">
                      <label>Present Boys:</label>
                      <input type="number" name="presentboys" class="form-control" 
                             min="0" max="'.$row['boys'].'" 
                             oninput="validateAttendance(this, '.$row['boys'].', \'Boys\')" required>
                      <small class="form-text text-muted">Max: '.$row['boys'].'</small>
                    </div>
                    
                    <div class="form-group">
                      <label>Absent Boys:</label>
                      <input type="number" name="absentboys" class="form-control" 
                             min="0" max="'.$row['boys'].'" 
                             oninput="validateAttendance(this, '.$row['boys'].', \'Boys\')" required>
                      <small class="form-text text-muted">Max: '.$row['boys'].'</small>
                    </div>
                  </div>
                  
                  <div>
                    <div class="form-group">
                      <label>Present Girls:</label>
                      <input type="number" name="presentgirls" class="form-control" 
                             min="0" max="'.$row['girls'].'" 
                             oninput="validateAttendance(this, '.$row['girls'].', \'Girls\')" required>
                      <small class="form-text text-muted">Max: '.$row['girls'].'</small>
                    </div>
                    
                    <div class="form-group">
                      <label>Absent Girls:</label>
                      <input type="number" name="absentgirls" class="form-control" 
                             min="0" max="'.$row['girls'].'" 
                             oninput="validateAttendance(this, '.$row['girls'].', \'Girls\')" required>
                      <small class="form-text text-muted">Max: '.$row['girls'].'</small>
                    </div>
                  </div>
                </div>
                
                <div class="form-group text-right">
                  <input type="submit" name="take" class="btn btn-primary" value="Take Attendance">
                </div>
              </form>';
            }
            ?>
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
    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
      $(document).ready(function () {
        $('#dataTable').DataTable();
        $('#dataTableHover').DataTable();
      });
    </script>
</body>
</html>