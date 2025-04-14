<?php
// Database connection
$host = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "attendancemsystem";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get all promoted students with their current and historical data
function getPromotedStudents($conn, $search = null) {
    $sql = "SELECT s.Id, s.name, s.admissionNumber, s.gender, 
                   c_current.className as current_class, 
                   GROUP_CONCAT(DISTINCT CONCAT(h.class_id, ':', c_hist.className) as history_classes
            FROM tblstudents s
            JOIN tblclass c_current ON s.classId = c_current.Id
            LEFT JOIN student_class_history h ON s.Id = h.student_id
            LEFT JOIN tblclass c_hist ON h.class_id = c_hist.Id";
    
    if ($search) {
        $sql .= " WHERE s.name LIKE ? OR s.admissionNumber LIKE ?";
        $searchTerm = "%$search%";
    }
    
    $sql .= " GROUP BY s.Id HAVING COUNT(h.history_id) > 0";
    
    $stmt = $conn->prepare($sql);
    
    if ($search) {
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $students = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Parse class history
            $history = array();
            if ($row['history_classes']) {
                $pairs = explode(',', $row['history_classes']);
                foreach ($pairs as $pair) {
                    list($class_id, $class_name) = explode(':', $pair);
                    $history[] = array('id' => $class_id, 'name' => $class_name);
                }
            }
            $row['history'] = $history;
            $students[] = $row;
        }
    }
    return $students;
}

// Function to get student's attendance archive
function getStudentAttendanceArchive($conn, $studentId) {
    $sql = "SELECT a.archive_id, a.attendanceDate, a.hour, a.status, 
                   l.lesson_title, c.className
            FROM student_attendance_archive a
            JOIN lessons l ON a.lessonId = l.id
            JOIN class_lessons cl ON l.id = cl.lesson_id
            JOIN tblclass c ON cl.class_id = c.Id
            WHERE a.studentId = ?
            ORDER BY a.attendanceDate DESC, a.hour";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $attendance = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $attendance[] = $row;
        }
    }
    return $attendance;
}

// Function to get student's absents archive
function getStudentAbsentsArchive($conn, $studentId) {
    $sql = "SELECT a.archive_id, a.date, a.day, a.reason, a.mode, a.className, a.school
            FROM student_absents_archive a
            JOIN tblstudents s ON a.regNumber = s.admissionNumber
            WHERE s.Id = ?
            ORDER BY a.date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $absents = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $absents[] = $row;
        }
    }
    return $absents;
}

// Function to get student's class history
function getStudentClassHistory($conn, $studentId) {
    $sql = "SELECT h.history_id, h.start_date, h.end_date, 
                   c.className, c.level, h.admission_number
            FROM student_class_history h
            JOIN tblclass c ON h.class_id = c.Id
            WHERE h.student_id = ?
            ORDER BY h.start_date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $history = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $history[] = $row;
        }
    }
    return $history;
}

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : null;
$students = getPromotedStudents($conn, $search);

// Get detailed data for a specific student if requested
$studentDetails = null;
$attendanceArchive = array();
$absentsArchive = array();
$classHistory = array();

if (isset($_GET['student_id'])) {
    $studentId = intval($_GET['student_id']);
    
    // Get student details
    $sql = "SELECT s.*, c.className as current_class 
            FROM tblstudents s
            JOIN tblclass c ON s.classId = c.Id
            WHERE s.Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $studentDetails = $result->fetch_assoc();
    
    // Get archives if student exists
    if ($studentDetails) {
        $attendanceArchive = getStudentAttendanceArchive($conn, $studentId);
        $absentsArchive = getStudentAbsentsArchive($conn, $studentId);
        $classHistory = getStudentClassHistory($conn, $studentId);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promoted Students Data Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .search-box { margin-bottom: 20px; }
        .student-list, .student-details { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .tab { overflow: hidden; border: 1px solid #ccc; background-color: #f1f1f1; }
        .tab button { background-color: inherit; float: left; border: none; outline: none; cursor: pointer; padding: 10px 16px; transition: 0.3s; }
        .tab button:hover { background-color: #ddd; }
        .tab button.active { background-color: #ccc; }
        .tabcontent { display: none; padding: 6px 12px; border: 1px solid #ccc; border-top: none; }
        .student-info { background-color: #f9f9f9; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .info-row { margin-bottom: 10px; }
        .info-label { font-weight: bold; display: inline-block; width: 150px; }
        .back-link { display: inline-block; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Promoted Students Data Management</h1>
        
        <?php if (!isset($_GET['student_id'])): ?>
            <!-- Search and List View -->
            <div class="search-box">
                <form method="get">
                    <input type="text" name="search" placeholder="Search by name or admission number" value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    <button type="submit">Search</button>
                    <?php if ($search): ?>
                        <a href="?" style="margin-left: 10px;">Clear Search</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="student-list">
                <h2>Promoted Students</h2>
                <?php if (empty($students)): ?>
                    <p>No promoted students found.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Admission Number</th>
                                <th>Gender</th>
                                <th>Current Class</th>
                                <th>Previous Classes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['admissionNumber']); ?></td>
                                    <td><?php echo htmlspecialchars($student['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($student['current_class']); ?></td>
                                    <td>
                                        <?php 
                                            $historyNames = array_map(function($h) { 
                                                return $h['name']; 
                                            }, $student['history']);
                                            echo htmlspecialchars(implode(', ', $historyNames));
                                        ?>
                                    </td>
                                    <td>
                                        <a href="?student_id=<?php echo $student['Id']; ?>">View Details</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            
        <?php else: ?>
            <!-- Student Detail View -->
            <a href="?" class="back-link">&laquo; Back to list</a>
            
            <?php if ($studentDetails): ?>
                <div class="student-info">
                    <h2>Student Information</h2>
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <?php echo htmlspecialchars($studentDetails['name']); ?>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Admission Number:</span>
                        <?php echo htmlspecialchars($studentDetails['admissionNumber']); ?>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Gender:</span>
                        <?php echo htmlspecialchars($studentDetails['gender']); ?>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Current Class:</span>
                        <?php echo htmlspecialchars($studentDetails['current_class']); ?>
                    </div>
                </div>
                
                <div class="tab">
                    <button class="tablinks active" onclick="openTab(event, 'attendance')">Attendance Archive</button>
                    <button class="tablinks" onclick="openTab(event, 'absents')">Absents Archive</button>
                    <button class="tablinks" onclick="openTab(event, 'history')">Class History</button>
                </div>
                
                <!-- Attendance Archive Tab -->
                <div id="attendance" class="tabcontent" style="display: block;">
                    <h3>Attendance Archive</h3>
                    <?php if (empty($attendanceArchive)): ?>
                        <p>No attendance records found in archive.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Class</th>
                                    <th>Lesson</th>
                                    <th>Hour</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attendanceArchive as $record): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($record['attendanceDate']); ?></td>
                                        <td><?php echo htmlspecialchars($record['className']); ?></td>
                                        <td><?php echo htmlspecialchars($record['lesson_title']); ?></td>
                                        <td><?php echo htmlspecialchars($record['hour']); ?></td>
                                        <td><?php echo htmlspecialchars($record['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                
                <!-- Absents Archive Tab -->
                <div id="absents" class="tabcontent">
                    <h3>Absents Archive</h3>
                    <?php if (empty($absentsArchive)): ?>
                        <p>No absent records found in archive.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Class</th>
                                    <th>Day</th>
                                    <th>Mode</th>
                                    <th>Reason</th>
                                    <th>School</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($absentsArchive as $record): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($record['date']); ?></td>
                                        <td><?php echo htmlspecialchars($record['className']); ?></td>
                                        <td><?php echo htmlspecialchars($record['day']); ?></td>
                                        <td><?php echo htmlspecialchars($record['mode']); ?></td>
                                        <td><?php echo htmlspecialchars($record['reason']); ?></td>
                                        <td><?php echo htmlspecialchars($record['school']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                
                <!-- Class History Tab -->
                <div id="history" class="tabcontent">
                    <h3>Class History</h3>
                    <?php if (empty($classHistory)): ?>
                        <p>No class history found.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Level</th>
                                    <th>Admission Number</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($classHistory as $record): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($record['className']); ?></td>
                                        <td><?php echo htmlspecialchars($record['level']); ?></td>
                                        <td><?php echo htmlspecialchars($record['admission_number']); ?></td>
                                        <td><?php echo htmlspecialchars($record['start_date']); ?></td>
                                        <td><?php echo htmlspecialchars($record['end_date']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                
                <script>
                    function openTab(evt, tabName) {
                        var i, tabcontent, tablinks;
                        
                        // Hide all tab content
                        tabcontent = document.getElementsByClassName("tabcontent");
                        for (i = 0; i < tabcontent.length; i++) {
                            tabcontent[i].style.display = "none";
                        }
                        
                        // Remove active class from all tab buttons
                        tablinks = document.getElementsByClassName("tablinks");
                        for (i = 0; i < tablinks.length; i++) {
                            tablinks[i].className = tablinks[i].className.replace(" active", "");
                        }
                        
                        // Show the current tab and add active class to the button
                        document.getElementById(tabName).style.display = "block";
                        evt.currentTarget.className += " active";
                    }
                </script>
                
            <?php else: ?>
                <p>Student not found.</p>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
</body>
</html>