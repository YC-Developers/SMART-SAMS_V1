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

// Function to get all promoted students
function getPromotedStudents($conn, $search = null) {
    $sql = "SELECT s.Id, s.name, s.admissionNumber, s.gender, 
                   c_current.className as current_class, 
                   GROUP_CONCAT(DISTINCT c_hist.className ORDER BY h.start_date DESC SEPARATOR ', ') as history_classes
            FROM tblstudents s
            JOIN tblclass c_current ON s.classId = c_current.Id
            LEFT JOIN student_class_history h ON s.Id = h.student_id
            LEFT JOIN tblclass c_hist ON h.class_id = c_hist.Id";
    
    if ($search) {
        $sql .= " WHERE s.name LIKE ? OR s.admissionNumber LIKE ?";
        $searchTerm = "%$search%";
    }
    
    $sql .= " GROUP BY s.Id, s.name, s.admissionNumber, s.gender, c_current.className
              HAVING COUNT(h.history_id) > 0";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    
    if ($search) {
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
    }
    
    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    $students = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Parse class history
            $history = array();
            if ($row['history_classes']) {
                $classNames = explode(', ', $row['history_classes']);
                foreach ($classNames as $className) {
                    $history[] = array('name' => $className);
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

// Function to get attendance statistics
function getAttendanceStatistics($conn, $studentId) {
    // Get present/absent counts
    $sql = "SELECT 
                SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent_count
            FROM student_attendance_archive
            WHERE studentId = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $counts = $result->fetch_assoc();
    
    // Get most common absence reason
    $sql = "SELECT reason, COUNT(*) as count 
            FROM student_absents_archive a
            JOIN tblstudents s ON a.regNumber = s.admissionNumber
            WHERE s.Id = ?
            GROUP BY reason
            ORDER BY count DESC
            LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $reason = $result->fetch_assoc();
    
    // Calculate attendance rate
    $total = $counts['present_count'] + $counts['absent_count'];
    $rate = ($total > 0) ? round(($counts['present_count'] / $total) * 100, 2) : 0;
    
    return [
        'present' => $counts['present_count'],
        'absent' => $counts['absent_count'],
        'rate' => $rate,
        'common_reason' => $reason['reason'] ?? 'N/A'
    ];
}

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : null;
$students = getPromotedStudents($conn, $search);

// Get detailed data for a specific student if requested
$studentDetails = null;
$attendanceArchive = array();
$absentsArchive = array();
$classHistory = array();
$attendanceStats = array();

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
        $attendanceStats = getAttendanceStatistics($conn, $studentId);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promoted Students Data Management</title>
    <!-- Include Chart.js for statistics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include pdf-lib for PDF generation -->
    <script src="https://unpkg.com/pdf-lib@1.16.0/dist/pdf-lib.min.js"></script>
    <!-- Include downloadjs for file downloads -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/downloadjs/1.4.8/download.min.js"></script>
    <!-- Include FileSaver for alternative download method -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
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
        .action-buttons { margin: 15px 0; }
        .button { background-color: #4CAF50; border: none; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 5px; }
        .chart-container { width: 100%; max-width: 600px; margin: 20px auto; }
        .stats-container { display: flex; flex-wrap: wrap; gap: 20px; margin: 20px 0; }
        .stat-box { flex: 1; min-width: 200px; background: #f8f9fa; padding: 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-value { font-size: 24px; font-weight: bold; color: #007bff; }
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
                <div class="action-buttons">
                    <button class="button" onclick="generatePDF()">Export to PDF</button>
                </div>
                
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
                
                <!-- Statistics Section -->
                <div class="stats-container">
                    <div class="stat-box">
                        <h3>Attendance Rate</h3>
                        <div class="stat-value"><?php echo $attendanceStats['rate']; ?>%</div>
                    </div>
                    <div class="stat-box">
                        <h3>Present Days</h3>
                        <div class="stat-value"><?php echo $attendanceStats['present']; ?></div>
                    </div>
                    <div class="stat-box">
                        <h3>Absent Days</h3>
                        <div class="stat-value"><?php echo $attendanceStats['absent']; ?></div>
                    </div>
                    <div class="stat-box">
                        <h3>Common Reason</h3>
                        <div class="stat-value"><?php echo $attendanceStats['common_reason']; ?></div>
                    </div>
                </div>
                
                <!-- Attendance Chart -->
                <div class="chart-container">
                    <canvas id="attendanceChart"></canvas>
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
                    // Tab functionality
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
                    
                    // Attendance chart
                    const ctx = document.getElementById('attendanceChart').getContext('2d');
                    const chart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ['Present', 'Absent'],
                            datasets: [{
                                data: [
                                    <?php echo $attendanceStats['present']; ?>,
                                    <?php echo $attendanceStats['absent']; ?>
                                ],
                                backgroundColor: [
                                    '#4e73df',
                                    '#e74a3b'
                                ],
                                hoverBackgroundColor: [
                                    '#2e59d9',
                                    '#be2617'
                                ],
                                hoverBorderColor: "rgba(234, 236, 244, 1)",
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Attendance Overview'
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                    
                    // PDF Generation using pdf-lib
                    async function generatePDF() {
                        const { PDFDocument, rgb } = PDFLib;
                        
                        // Create a new PDF document
                        const pdfDoc = await PDFDocument.create();
                        const page = pdfDoc.addPage([600, 800]);
                        
                        // Set font and size
                        const font = await pdfDoc.embedFont(PDFLib.StandardFonts.Helvetica);
                        
                        // Add title
                        page.drawText('Student Academic Report', {
                            x: 50,
                            y: 750,
                            size: 18,
                            font,
                            color: rgb(0, 0, 0)
                        });
                        
                        // Add student information
                        page.drawText('Student Information:', {
                            x: 50,
                            y: 700,
                            size: 14,
                            font,
                            color: rgb(0, 0, 0)
                        });
                        
                        let yPosition = 670;
                        const studentInfo = [
                            `Name: ${<?php echo json_encode($studentDetails['name']); ?>}`,
                            `Admission Number: ${<?php echo json_encode($studentDetails['admissionNumber']); ?>}`,
                            `Gender: ${<?php echo json_encode($studentDetails['gender']); ?>}`,
                            `Current Class: ${<?php echo json_encode($studentDetails['current_class']); ?>}`
                        ];
                        
                        studentInfo.forEach(info => {
                            page.drawText(info, {
                                x: 50,
                                y: yPosition,
                                size: 12,
                                font,
                                color: rgb(0, 0, 0)
                            });
                            yPosition -= 20;
                        });
                        
                        // Add attendance statistics
                        yPosition -= 20;
                        page.drawText('Attendance Statistics:', {
                            x: 50,
                            y: yPosition,
                            size: 14,
                            font,
                            color: rgb(0, 0, 0)
                        });
                        yPosition -= 20;
                        
                        const statsInfo = [
                            `Present Days: ${<?php echo $attendanceStats['present']; ?>}`,
                            `Absent Days: ${<?php echo $attendanceStats['absent']; ?>}`,
                            `Attendance Rate: ${<?php echo $attendanceStats['rate']; ?>}%`,
                            `Most Common Reason: ${<?php echo json_encode($attendanceStats['common_reason']); ?>}`
                        ];
                        
                        statsInfo.forEach(info => {
                            page.drawText(info, {
                                x: 50,
                                y: yPosition,
                                size: 12,
                                font,
                                color: rgb(0, 0, 0)
                            });
                            yPosition -= 20;
                        });
                        
                        // Add class history
                        yPosition -= 20;
                        page.drawText('Class History:', {
                            x: 50,
                            y: yPosition,
                            size: 14,
                            font,
                            color: rgb(0, 0, 0)
                        });
                        yPosition -= 20;
                        
                        <?php foreach ($classHistory as $history): ?>
                            page.drawText(`${<?php echo json_encode($history['className']); ?>} (${<?php echo json_encode($history['level']); ?>)`, {
                                x: 50,
                                y: yPosition,
                                size: 12,
                                font,
                                color: rgb(0, 0, 0)
                            });
                            yPosition -= 15;
                            page.drawText(`From ${<?php echo json_encode($history['start_date']); ?>} to ${<?php echo json_encode($history['end_date']); ?>}`, {
                                x: 50,
                                y: yPosition,
                                size: 10,
                                font,
                                color: rgb(0.5, 0.5, 0.5)
                            });
                            yPosition -= 15;
                            page.drawText(`Admission: ${<?php echo json_encode($history['admission_number']); ?>}`, {
                                x: 50,
                                y: yPosition,
                                size: 10,
                                font,
                                color: rgb(0.5, 0.5, 0.5)
                            });
                            yPosition -= 25;
                        <?php endforeach; ?>
                        
                        // Serialize the PDF to bytes
                        const pdfBytes = await pdfDoc.save();
                        
                        // Download the PDF
                        download(new Blob([pdfBytes], { type: 'application/pdf' }), 
                            `student_report_${<?php echo json_encode($studentDetails['admissionNumber']); ?>}.pdf`);
                    }
                </script>
                
            <?php else: ?>
                <p>Student not found.</p>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
</body>
</html>