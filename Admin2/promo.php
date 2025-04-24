<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Function to get all classes
function getClasses($conn) {
    $qu = "SELECT * FROM tbladmin WHERE Id = " . $_SESSION['userId'] . "";
    $rs = $conn->query($qu);
    $rows = $rs->fetch_assoc();
    $school = $rows['school'];

    $sql = "SELECT * FROM tblclass WHERE school='$school'";
    $result = $conn->query($sql);
    
    $classes = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $classes[$row['Id']] = $row['level'] . " - " . $row['className'];
        }
    }
    return $classes;
}

// Function to get students in a class
function getStudentsInClass($conn, $classId) {
    $sql = "SELECT Id, name, admissionNumber, gender FROM tblstudents WHERE classId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $classId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $students = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    return $students;
}

// Function to archive student data before promotion
function archiveStudentData($conn, $studentId, $currentClassId) {
    // Archive attendance records
    $sql = "INSERT INTO student_attendance_archive 
            SELECT NULL, studentId, lessonId, attendanceDate, hour, status, NOW() 
            FROM tblattendance 
            WHERE studentId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    
    // Archive absent records
    $sql = "INSERT INTO student_absents_archive 
            SELECT NULL, name, gender, className, regNumber, mode, day, reason, school, date, NOW() 
            FROM absents 
            WHERE regNumber = (SELECT admissionNumber FROM tblstudents WHERE Id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    
    // Archive class information
    $sql = "INSERT INTO student_class_history 
            (student_id, class_id, admission_number, start_date, end_date) 
            VALUES (?, ?, (SELECT admissionNumber FROM tblstudents WHERE Id = ?), 
            (SELECT MIN(attendanceDate) FROM tblattendance WHERE studentId = ?), NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $studentId, $currentClassId, $studentId, $studentId);
    $stmt->execute();
}

// Function to promote students
function promoteStudents($conn, $studentIds, $newClassId) {
    // First get the new class information
    $sql = "SELECT className, level FROM tblclass WHERE Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $newClassId);
    $stmt->execute();
    $result = $stmt->get_result();
    $newClass = $result->fetch_assoc();

    // Generate new admission numbers based on new class
    $prefix = substr($newClass['className'], 0, 3) . substr($newClass['level'], 0, 1);
    
    foreach ($studentIds as $studentId) {
        // Archive current data before promotion
        $currentClassId = getStudentCurrentClass($conn, $studentId);
        archiveStudentData($conn, $studentId, $currentClassId);

        //school code
        $qm = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
        $rs = $conn->query($qm);
        $num = $rs->num_rows;
        $rows = $rs->fetch_assoc();
        $school = $rows['school']; 

        $a ="SELECT code FROM schools WHERE schoolName='$school'";
        $ree = $conn->query($a);
        $qs = $ree->fetch_assoc();
        
        // Fetch class arms
        $a = "SELECT * FROM tblclass WHERE Id='$newClassId'";
        $re = $conn->query($a);
        $ro = $re->fetch_assoc();

        //YEAR
        $year = date("Y");

        $p = "SELECT * FROM tblstudents WHERE classId='$newClassId'";
        $s = $conn->query($p);
        $n = $s->num_rows;
        $regCounter = $n;
        $regCounter++; // Increment the counter

        // Generate new admission number
        $newAdmissionNumber = $qs['code'].$ro['level'] . sprintf('%03d', $regCounter) . $year;
        
        // Update student record
        $sql = "UPDATE tblstudents SET classId = ?, admissionNumber = ? WHERE Id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $newClassId, $newAdmissionNumber, $studentId);
        $stmt->execute();
    }
}

// Helper function to get student's current class
function getStudentCurrentClass($conn, $studentId) {
    $sql = "SELECT classId FROM tblstudents WHERE Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['classId'];
}

// Helper function to generate new admission number
function generateNewAdmissionNumber($conn, $prefix) {
    $sql = "SELECT MAX(CAST(SUBSTRING(admissionNumber, LENGTH(?) + 1) AS UNSIGNED)) as max_num 
            FROM tblstudents 
            WHERE admissionNumber LIKE ?";
    $likePrefix = $prefix . '%';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $prefix, $likePrefix);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $nextNum = ($row['max_num'] ?? 0) + 1;
    return $prefix . str_pad($nextNum, 2, '0', STR_PAD_LEFT);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['promote_students']) && isset($_POST['student_ids']) && isset($_POST['new_class_id'])) {
        $studentIds = $_POST['student_ids'];
        $newClassId = $_POST['new_class_id'];
        
        promoteStudents($conn, $studentIds, $newClassId);
        
        $message = "Students promoted successfully!";
    }
}

// Get classes for dropdown
$classes = getClasses($conn);

// Get students for selected class if a class is selected
$students = array();
if (isset($_GET['class_id'])) {
    $students = getStudentsInClass($conn, $_GET['class_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Student Promotion</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>
<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>
        <!-- Topbar -->
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Student Promotion</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Promotion</li>
            </ol>
          </div>

          <!-- Promotion Form -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Promote Students</h6>
                </div>
                <div class="card-body">
                  <?php if (isset($message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                      <?php echo htmlspecialchars($message); ?>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  <?php endif; ?>

                  <!-- Select Current Class -->
                  <form method="get" class="mb-4">
                    <div class="form-group">
                      <label for="class_id" class="font-weight-bold text-gray-800">Select Current Class</label>
                      <select name="class_id" id="class_id" class="form-control" required onchange="this.form.submit()">
                        <option value="">-- Select Class --</option>
                        <?php foreach ($classes as $id => $name): ?>
                          <option value="<?php echo $id; ?>" <?php echo (isset($_GET['class_id']) && $_GET['class_id'] == $id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($name); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </form>

                  <?php if (!empty($students)): ?>
                    <!-- Promote Students Form -->
                    <form method="post">
                      <div class="form-group mb-4">
                        <label for="new_class_id" class="font-weight-bold text-gray-800">Promote To</label>
                        <select name="new_class_id" id="new_class_id" class="form-control" required>
                          <option value="">-- Select New Class --</option>
                          <?php foreach ($classes as $id => $name): ?>
                            <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <!-- Students Table -->
                      <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                          <thead class="thead-dark">
                            <tr>
                              <th><input type="checkbox" id="select_all"></th>
                              <th>Name</th>
                              <th>Admission Number</th>
                              <th>Gender</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($students as $student): ?>
                              <tr>
                                <td><input type="checkbox" name="student_ids[]" value="<?php echo $student['Id']; ?>"></td>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo htmlspecialchars($student['admissionNumber']); ?></td>
                                <td><?php echo htmlspecialchars($student['gender']); ?></td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>

                      <!-- Submit Button -->
                      <div class="form-group mt-4">
                        <button type="submit" name="promote_students" class="btn btn-primary btn-sm shadow-sm">
                          <i class="fas fa-arrow-up mr-2"></i>Promote Selected Students
                        </button>
                      </div>
                    </form>

                    <!-- Select All Checkbox Script -->
                    <script>
                      document.getElementById('select_all').addEventListener('change', function() {
                        var checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
                        for (var i = 0; i < checkboxes.length; i++) {
                          checkboxes[i].checked = this.checked;
                        }
                      });
                    </script>
                  <?php endif; ?>
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
</body>
</html>