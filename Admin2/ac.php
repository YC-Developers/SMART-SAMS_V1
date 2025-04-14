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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Promotion System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        select, input[type="submit"] { padding: 8px; width: 100%; max-width: 300px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .message { padding: 10px; background-color: #dff0d8; color: #3c763d; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Promotion System</h1>
        
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="get">
            <div class="form-group">
                <label for="class_id">Select Current Class:</label>
                <select name="class_id" id="class_id" required onchange="this.form.submit()">
                    <option value="">-- Select Class --</option>
                    <?php foreach ($classes as $id => $name): ?>
                        <option value="<?php echo $id; ?>" <?php echo (isset($_GET['class_id']) && $_GET['class_id'] == $id) ? 'selected' : ''; ?>>
                            <?php echo $name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
        
        <?php if (!empty($students)): ?>
            <form method="post">
                <div class="form-group">
                    <label for="new_class_id">Promote To:</label>
                    <select name="new_class_id" id="new_class_id" required>
                        <option value="">-- Select New Class --</option>
                        <?php foreach ($classes as $id => $name): ?>
                            <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <table>
                    <thead>
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
                
                <div class="form-group">
                    <input type="submit" name="promote_students" value="Promote Selected Students">
                </div>
            </form>
            
            <script>
                // Select all checkbox functionality
                document.getElementById('select_all').addEventListener('change', function() {
                    var checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
                    for (var i = 0; i < checkboxes.length; i++) {
                        checkboxes[i].checked = this.checked;
                    }
                });
            </script>
        <?php endif; ?>
    </div>
</body>
</html>