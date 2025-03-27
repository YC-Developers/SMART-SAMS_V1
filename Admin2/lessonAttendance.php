<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = "";

// Save Attendance
if (isset($_POST['saveAttendance'])) {
    $lessonId = $_POST['lesson_id'];
    $classId = $_POST['class_id'];
    $attendanceDate = date('Y-m-d'); // Current date
    $hour = $_POST['hour']; // Selected hour

    // Loop through students and save their attendance
    foreach ($_POST['attendance'] as $studentId => $status) {
        $query = mysqli_query($conn, "INSERT INTO tblattendance (lessonId, studentId, attendanceDate, hour, status) 
            VALUES ('$lessonId', '$studentId', '$attendanceDate', '$hour', '$status')");
    }

    if ($query) {
        $statusMsg = "<div class='alert alert-success'>Attendance saved successfully!</div>";
    } else {
        $statusMsg = "<div class='alert alert-danger'>An error occurred!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>AMS - Attendance</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  
  <script>
    // Function to mark all students as present
    function markAllAsPresent() {
        const radios = document.querySelectorAll('input[value="Present"]');
        radios.forEach(radio => radio.checked = true);
    }

    // Function to mark all students as absent
    function markAllAsAbsent() {
        const radios = document.querySelectorAll('input[value="Absent"]');
        radios.forEach(radio => radio.checked = true);
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
            <h1 class="h3 mb-0 text-gray-800">Mark Attendance</h1>
          </div>

          <div class="container mt-4">
            <?php echo $statusMsg; ?>
            <form method="post">
              <!-- Select Lesson -->
              <div class="form-group">
                <label for="lesson_id">Select Lesson</label>
                <select name="lesson_id" class="form-control" required>
                  <option value="">-- Select Lesson --</option>
                  <?php
                  $lessons = mysqli_query($conn, "SELECT * FROM lessons WHERE school='$s' ORDER BY lesson_title ASC");
                  while ($lesson = mysqli_fetch_assoc($lessons)) {
                      echo "<option value='".$lesson['id']."'>".$lesson['lesson_title']."</option>";
                  }
                  ?>
                </select>
              </div>

              <!-- Select Class -->
              <div class="form-group">
                <label for="class_id">Select Class</label>
                <select name="class_id" class="form-control" id="class_id" onchange="this.form.submit()" required>
                  <option value="">-- Select Class --</option>
                  <?php
                                           $qm = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
                                           $rs = $conn->query($qm);
                                           $num = $rs->num_rows;
                                           $rows = $rs->fetch_assoc();
                                           $school = $rows['school'];
                         
                       
                  $classes = mysqli_query($conn, "SELECT * FROM tblclass WHERE school='$school' ORDER BY className ASC");
                  while ($class = mysqli_fetch_assoc($classes)) {
                      $selected = (isset($_POST['class_id']) && $_POST['class_id'] == $class['Id']) ? "selected" : "";
                      echo "<option value='".$class['Id']."' $selected>".$class['className']."</option>";
                  }
                  ?>
                </select>
              </div>

              <!-- Select Hour -->
              <div class="form-group">
                <label for="hour">Select Hour</label>
                <select name="hour" class="form-control" required>
                  <option value="">-- Select Hour --</option>
                  <?php
                  for ($i = 1; $i <= 10; $i++) { // Example: 10 hours per day
                      echo "<option value='Hour $i'>Hour $i</option>";
                  }
                  ?>
                </select>
              </div>
              
              <!-- Students Table -->
              <h4>Students</h4>
              <div class="d-flex mb-3">
                <button type="button" class="btn btn-success mr-2" onclick="markAllAsPresent()">Mark All as Present</button>
                <button type="button" class="btn btn-danger" onclick="markAllAsAbsent()">Mark All as Absent</button>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Student Name</th>
                      <th>Admission Number</th>
                      <th>Attendance</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if (isset($_POST['class_id'])) {
                        $classId = $_POST['class_id'];
                        $students = mysqli_query($conn, "SELECT * FROM tblstudents WHERE classId = '$classId' ORDER BY name ASC");
                        $sn = 1;
                        while ($student = mysqli_fetch_assoc($students)) {
                            echo "<tr>
                                <td>".$sn++."</td>
                                <td>".$student['name']."</td>
                                <td>".$student['admissionNumber']."</td>
                                <td>
                                    <input type='radio' name='attendance[".$student['Id']."]' value='Present' required> Present
                                    <input type='radio' name='attendance[".$student['Id']."]' value='Absent' required> Absent
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Please select a class to display students.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>

              <button type="submit" name="saveAttendance" class="btn btn-primary">Save Attendance</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  </div>
</body>

</html>
