<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = "";

// Save Attendance
if (isset($_POST['saveAttendance'])) {
    $lessonId = $_POST['lesson_id'];
    $classId = $_POST['class_id'];
    $attendanceDate = date('Y-m-d'); // Current date
    $hour = $_POST['hour']; // Selected hour

    // Validate inputs
    if (empty($lessonId) || empty($classId) || empty($hour) || empty($_POST['attendance'])) {
        $statusMsg = "<div class='alert alert-danger'>All fields are required!</div>";
    } else {
        // Prepare the insert query
        $query = "INSERT INTO tblattendance (lessonId, studentId, attendanceDate, hour, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $success = true;
            // Loop through students and save their attendance
            foreach ($_POST['attendance'] as $studentId => $status) {
                // Validate status
                if (!in_array($status, ['Present', 'Absent'])) {
                    $success = false;
                    $statusMsg = "<div class='alert alert-danger'>Invalid attendance status!</div>";
                    break;
                }
                // Bind parameters and execute
                $stmt->bind_param("iisss", $lessonId, $studentId, $attendanceDate, $hour, $status);
                if (!$stmt->execute()) {
                    $success = false;
                    break;
                }
            }
            $stmt->close();

            if ($success) {
                $statusMsg = "<div class='alert alert-success'>Attendance saved successfully!</div>";
            } else {
                $statusMsg = "<div class='alert alert-danger'>An error occurred while saving attendance!</div>";
            }
        } else {
            $statusMsg = "<div class='alert alert-danger'>Failed to prepare the query!</div>";
        }
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
                  // Secure query for lessons using prepared statement
                  $query = "SELECT id, lesson_title FROM lessons WHERE teacher_id = ? ORDER BY lesson_title ASC";
                  $stmt = $conn->prepare($query);
                  $stmt->bind_param("i", $_SESSION['userId']);
                  $stmt->execute();
                  $lessons = $stmt->get_result();
                  while ($lesson = $lessons->fetch_assoc()) {
                      $selected = (isset($_POST['lesson_id']) && $_POST['lesson_id'] == $lesson['id']) ? "selected" : "";
                      echo "<option value='{$lesson['id']}' $selected>{$lesson['lesson_title']}</option>";
                  }
                  $stmt->close();
                  ?>
                </select>
              </div>

              <!-- Select Class -->
              <div class="form-group">
                <label for="class_id">Select Class</label>
                <select name="class_id" class="form-control" id="class_id" onchange="this.form.submit()" required>
                  <option value="">-- Select Class --</option>
                  <?php
                  // Secure query for classes using prepared statement
                  $query = "
                    SELECT DISTINCT tc.Id, tc.className
                    FROM tblclass tc
                    INNER JOIN class_lessons cl ON cl.class_id = tc.Id
                    INNER JOIN lessons l ON l.id = cl.lesson_id
                    WHERE l.teacher_id = ?
                    ORDER BY tc.className ASC
                  ";
                  $stmt = $conn->prepare($query);
                  $stmt->bind_param("i", $_SESSION['userId']);
                  $stmt->execute();
                  $classes = $stmt->get_result();
                  while ($class = $classes->fetch_assoc()) {
                      $selected = (isset($_POST['class_id']) && $_POST['class_id'] == $class['Id']) ? "selected" : "";
                      echo "<option value='{$class['Id']}' $selected>{$class['className']}</option>";
                  }
                  $stmt->close();
                  ?>
                </select>
              </div>

              <!-- Select Hour -->
              <div class="form-group">
                <label for="hour">Select Hour</label>
                <select name="hour" class="form-control" required>
                  <option value="">-- Select Hour --</option>
                  <?php
                  for ($i = 1; $i <= 10; $i++) {
                      $selected = (isset($_POST['hour']) && $_POST['hour'] == "Hour $i") ? "selected" : "";
                      echo "<option value='Hour $i' $selected>Hour $i</option>";
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
                    if (isset($_POST['class_id']) && !empty($_POST['class_id'])) {
                        // Secure query for students using prepared statement
                        $classId = $_POST['class_id'];
                        $query = "SELECT Id, name, admissionNumber FROM tblstudents WHERE classId = ? ORDER BY name ASC";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $classId);
                        $stmt->execute();
                        $students = $stmt->get_result();
                        $sn = 1;
                        if ($students->num_rows > 0) {
                            while ($student = $students->fetch_assoc()) {
                                echo "<tr>
                                    <td>$sn</td>
                                    <td>{$student['name']}</td>
                                    <td>{$student['admissionNumber']}</td>
                                    <td>
                                        <input type='radio' name='attendance[{$student['Id']}]' value='Present' required> Present
                                        <input type='radio' name='attendance[{$student['Id']}]' value='Absent'> Absent
                                    </td>
                                </tr>";
                                $sn++;
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No students found in this class.</td></tr>";
                        }
                        $stmt->close();
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
    <script src="js/ruang-admin.min.js"></script>
  </div>
</body>

</html>