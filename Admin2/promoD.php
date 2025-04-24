<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = '';
$errors = [];

// Fetch the school of the logged-in Dean of Study
$stmt = $conn->prepare("SELECT school FROM tbladmin WHERE Id = ? AND type = 'study'");
$stmt->bind_param("i", $_SESSION['userId']);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$school = $admin ? $admin['school'] : null;
$stmt->close();

if (!$school) {
    die("Error: Dean of Study school not found.");
}

// Function to get all promoted students
function getPromotedStudents($conn, $school, $search = null) {
    $sql = "
        SELECT s.Id, s.name, s.admissionNumber, s.gender, 
               c_current.className as current_class, 
               GROUP_CONCAT(DISTINCT c_hist.className ORDER BY h.start_date DESC SEPARATOR ', ') as history_classes
        FROM tblstudents s
        JOIN tblclass c_current ON s.classId = c_current.Id
        LEFT JOIN student_class_history h ON s.Id = h.student_id
        LEFT JOIN tblclass c_hist ON h.class_id = c_hist.Id
        WHERE c_current.school = ?
    ";
    
    if ($search) {
        $sql .= " AND (s.name LIKE ? OR s.admissionNumber LIKE ?)";
    }
    
    $sql .= "
        GROUP BY s.Id, s.name, s.admissionNumber, s.gender, c_current.className
        HAVING COUNT(h.history_id) > 0
    ";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    
    if ($search) {
        $searchTerm = "%$search%";
        $stmt->bind_param("sss", $school, $searchTerm, $searchTerm);
    } else {
        $stmt->bind_param("s", $school);
    }
    
    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    $students = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
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
function getStudentAttendanceArchive($conn, $school, $studentId) {
    $sql = "
        SELECT a.archive_id, a.attendanceDate, a.hour, a.status, 
               l.lesson_title, c.className
        FROM student_attendance_archive a
        JOIN lessons l ON a.lessonId = l.id
        JOIN class_lessons cl ON l.id = cl.lesson_id
        JOIN tblclass c ON cl.class_id = c.Id
        WHERE a.studentId = ? AND c.school = ?
        ORDER BY a.attendanceDate DESC, a.hour
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $studentId, $school);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $attendance = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $attendance[] = $row;
        }
    }
    return $attendance;
}

// Function to get student's absents archive
function getStudentAbsentsArchive($conn, $school, $studentId) {
    $sql = "
        SELECT a.archive_id, a.date, a.day, a.reason, a.mode, a.className, a.school
        FROM student_absents_archive a
        JOIN tblstudents s ON a.regNumber = s.admissionNumber
        JOIN tblclass c ON s.classId = c.Id
        WHERE s.Id = ? AND c.school = ?
        ORDER BY a.date DESC
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $studentId, $school);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $absents = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $absents[] = $row;
        }
    }
    return $absents;
}

// Function to get student's class history
function getStudentClassHistory($conn, $school, $studentId) {
    $sql = "
        SELECT h.history_id, h.start_date, h.end_date, 
               c.className, c.level, h.admission_number
        FROM student_class_history h
        JOIN tblclass c ON h.class_id = c.Id
        WHERE h.student_id = ? AND c.school = ?
        ORDER BY h.start_date DESC
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $studentId, $school);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $history = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }
    }
    return $history;
}

// Function to get attendance statistics
function getAttendanceStatistics($conn, $school, $studentId) {
    // Get present/absent counts
    $sql = "
        SELECT 
            SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present_count,
            SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent_count
        FROM student_attendance_archive a
        JOIN lessons l ON a.lessonId = l.id
        JOIN class_lessons cl ON l.id = cl.lesson_id
        JOIN tblclass c ON cl.class_id = c.Id
        WHERE a.studentId = ? AND c.school = ?
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $studentId, $school);
    $stmt->execute();
    $result = $stmt->get_result();
    $counts = $result->fetch_assoc();
    
    // Get most common absence reason
    $sql = "
        SELECT reason, COUNT(*) as count 
        FROM student_absents_archive a
        JOIN tblstudents s ON a.regNumber = s.admissionNumber
        JOIN tblclass c ON s.classId = c.Id
        WHERE s.Id = ? AND c.school = ?
        GROUP BY reason
        ORDER BY count DESC
        LIMIT 1
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $studentId, $school);
    $stmt->execute();
    $result = $stmt->get_result();
    $reason = $result->fetch_assoc();
    
    // Calculate attendance rate
    $total = ($counts['present_count'] ?? 0) + ($counts['absent_count'] ?? 0);
    $rate = ($total > 0) ? round(($counts['present_count'] / $total) * 100, 2) : 0;
    
    return [
        'present' => $counts['present_count'] ?? 0,
        'absent' => $counts['absent_count'] ?? 0,
        'rate' => $rate,
        'common_reason' => $reason['reason'] ?? 'N/A'
    ];
}

// Function to prepare data for PDF export
function getStudentPDFData($conn, $school, $studentId) {
    $data = [];
    
    // Get student details
    $sql = "
        SELECT s.*, c.className as current_class 
        FROM tblstudents s
        JOIN tblclass c ON s.classId = c.Id
        WHERE s.Id = ? AND c.school = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $studentId, $school);
    $stmt->execute();
    $result = $stmt->get_result();
    $data['student'] = $result->fetch_assoc();
    
    if ($data['student']) {
        $data['attendance'] = getStudentAttendanceArchive($conn, $school, $studentId);
        $data['absents'] = getStudentAbsentsArchive($conn, $school, $studentId);
        $data['history'] = getStudentClassHistory($conn, $school, $studentId);
        $data['stats'] = getAttendanceStatistics($conn, $school, $studentId);
    }
    
    return $data;
}

// Handle data request for PDF generation
if (isset($_GET['get_pdf_data']) && isset($_GET['student_id'])) {
    header('Content-Type: application/json');
    $studentId = intval($_GET['student_id']);
    $pdfData = getStudentPDFData($conn, $school, $studentId);
    echo json_encode($pdfData);
    exit;
}

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : null;
$students = getPromotedStudents($conn, $school, $search);

// Get detailed data for a specific student if requested
$studentDetails = null;
$attendanceArchive = array();
$absentsArchive = array();
$classHistory = array();
$attendanceStats = array();

if (isset($_GET['student_id'])) {
    $studentId = intval($_GET['student_id']);
    
    // Get student details
    $sql = "
        SELECT s.*, c.className as current_class 
        FROM tblstudents s
        JOIN tblclass c ON s.classId = c.Id
        WHERE s.Id = ? AND c.school = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $studentId, $school);
    $stmt->execute();
    $result = $stmt->get_result();
    $studentDetails = $result->fetch_assoc();
    
    // Get archives if student exists
    if ($studentDetails) {
        $attendanceArchive = getStudentAttendanceArchive($conn, $school, $studentId);
        $absentsArchive = getStudentAbsentsArchive($conn, $school, $studentId);
        $classHistory = getStudentClassHistory($conn, $school, $studentId);
        $attendanceStats = getAttendanceStatistics($conn, $school, $studentId);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/logo/attnlg.jpg" rel="icon">
    <title>Promoted Students Data Management</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <!-- Include Chart.js and jsPDF from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <style>
        .student-card {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        .info-row {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: 600;
            color: #5a5c69;
        }
        .stat-card {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #4e73df;
            margin: 10px 0;
        }
        .stat-title {
            color: #858796;
            font-size: 14px;
        }
        .nav-tabs .nav-link {
            color: #5a5c69;
            font-weight: 600;
        }
        .nav-tabs .nav-link.active {
            color: #4e73df;
            border-color: #4e73df;
        }
        .table-responsive {
            border-radius: 5px;
            overflow: hidden;
        }
        .table thead th {
            background-color: #4e73df;
            color: white;
            border-color: #4e73df;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }
        .badge-present {
            background-color: #1cc88a;
        }
        .badge-absent {
            background-color: #e74a3b;
        }
        .search-box {
            margin-bottom: 20px;
        }
        .chart-container {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #4e73df;
            font-weight: 600;
        }
        .btn-export {
            background-color: #4e73df;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
        }
        .btn-export:hover {
            background-color: #2e59d9;
            color: white;
        }
    </style>
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
            <h1 class="h3 mb-0 text-gray-800">Promoted Students Data Management</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Promote</li>
            </ol>
          </div>
          
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-body">
                  <?php if (!isset($_GET['student_id'])): ?>
                      <!-- Search and List View -->
                      <div class="search-box">
                          <form method="get" class="form-inline">
                              <div class="form-group mx-sm-3 mb-2">
                                  <input type="text" name="search" class="form-control" placeholder="Search by name or admission number" value="<?php echo htmlspecialchars($search ?? ''); ?>">
                              </div>
                              <button type="submit" class="btn btn-primary mb-2">Search</button>
                              <?php if ($search): ?>
                                  <a href="?" class="btn btn-secondary mb-2 ml-2">Clear Search</a>
                              <?php endif; ?>
                          </form>
                      </div>
                      
                      <div class="table-responsive">
                          <h4 class="mb-3">Promoted Students</h4>
                          <?php if (empty($students)): ?>
                              <div class="alert alert-info">No promoted students found.</div>
                          <?php else: ?>
                              <table class="table table-bordered table-hover">
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
                                                  <a href="?student_id=<?php echo $student['Id']; ?>" class="btn btn-sm btn-primary">View Details</a>
                                              </td>
                                          </tr>
                                      <?php endforeach; ?>
                                  </tbody>
                              </table>
                          <?php endif; ?>
                      </div>
                      
                  <?php else: ?>
                      <!-- Student Detail View -->
                      <a href="?" class="back-link"><i class="fas fa-arrow-left"></i> Back to list</a>
                      
                      <?php if ($studentDetails): ?>
                          <div class="d-flex justify-content-end mb-3">
                              <button onclick="generatePDF()" class="btn-export"><i class="fas fa-file-pdf mr-2"></i>Export to PDF</button>
                          </div>
                          
                          <div class="student-card">
                              <h4 class="mb-4">Student Information</h4>
                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="info-row">
                                          <span class="info-label">Name:</span>
                                          <span><?php echo htmlspecialchars($studentDetails['name']); ?></span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Admission Number:</span>
                                          <span><?php echo htmlspecialchars($studentDetails['admissionNumber']); ?></span>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="info-row">
                                          <span class="info-label">Gender:</span>
                                          <span><?php echo htmlspecialchars($studentDetails['gender']); ?></span>
                                      </div>
                                      <div class="info-row">
                                          <span class="info-label">Current Class:</span>
                                          <span><?php echo htmlspecialchars($studentDetails['current_class']); ?></span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          
                          <!-- Statistics Section -->
                          <div class="row mb-4">
                              <div class="col-xl-3 col-md-6 mb-4">
                                  <div class="stat-card">
                                      <div class="stat-title">Attendance Rate</div>
                                      <div class="stat-value"><?php echo $attendanceStats['rate']; ?>%</div>
                                      <div class="text-success small">Overall Performance</div>
                                  </div>
                              </div>
                              <div class="col-xl-3 col-md-6 mb-4">
                                  <div class="stat-card">
                                      <div class="stat-title">Present Days</div>
                                      <div class="stat-value"><?php echo $attendanceStats['present']; ?></div>
                                      <div class="text-success small">Good attendance</div>
                                  </div>
                              </div>
                              <div class="col-xl-3 col-md-6 mb-4">
                                  <div class="stat-card">
                                      <div class="stat-title">Absent Days</div>
                                      <div class="stat-value"><?php echo $attendanceStats['absent']; ?></div>
                                      <div class="text-danger small">Needs improvement</div>
                                  </div>
                              </div>
                              <div class="col-xl-3 col-md-6 mb-4">
                                  <div class="stat-card">
                                      <div class="stat-title">Common Reason</div>
                                      <div class="stat-value"><?php echo $attendanceStats['common_reason']; ?></div>
                                      <div class="text-info small">Most frequent</div>
                                  </div>
                              </div>
                          </div>
                          
                          <!-- Attendance Chart -->
                          <div class="chart-container mb-4">
                              <h5 class="mb-4">Attendance Overview</h5>
                              <canvas id="attendanceChart" height="100"></canvas>
                          </div>
                          
                          <!-- Tab Navigation -->
                          <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                              <li class="nav-item">
                                  <a class="nav-link active" id="attendance-tab" data-toggle="tab" href="#attendance" role="tab">Attendance Archive</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="absents-tab" data-toggle="tab" href="#absents" role="tab">Absents Archive</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab">Class History</a>
                              </li>
                          </ul>
                          
                          <!-- Tab Content -->
                          <div class="tab-content" id="myTabContent">
                              <!-- Attendance Archive Tab -->
                              <div class="tab-pane fade show active" id="attendance" role="tabpanel">
                                  <div class="table-responsive">
                                      <h5 class="mb-3">Attendance Records</h5>
                                      <?php if (empty($attendanceArchive)): ?>
                                          <div class="alert alert-info">No attendance records found in archive.</div>
                                      <?php else: ?>
                                          <table class="table table-bordered table-hover">
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
                                                          <td>
                                                              <span class="badge <?php echo $record['status'] == 'Present' ? 'badge-present' : 'badge-absent'; ?>">
                                                                  <?php echo htmlspecialchars($record['status']); ?>
                                                              </span>
                                                          </td>
                                                      </tr>
                                                  <?php endforeach; ?>
                                              </tbody>
                                          </table>
                                      <?php endif; ?>
                                  </div>
                              </div>
                              
                              <!-- Absents Archive Tab -->
                              <div class="tab-pane fade" id="absents" role="tabpanel">
                                  <div class="table-responsive">
                                      <h5 class="mb-3">Absent Records</h5>
                                      <?php if (empty($absentsArchive)): ?>
                                          <div class="alert alert-info">No absent records found in archive.</div>
                                      <?php else: ?>
                                          <table class="table table-bordered table-hover">
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
                              </div>
                              
                              <!-- Class History Tab -->
                              <div class="tab-pane fade" id="history" role="tabpanel">
                                  <div class="table-responsive">
                                      <h5 class="mb-3">Class History</h5>
                                      <?php if (empty($classHistory)): ?>
                                          <div class="alert alert-info">No class history found.</div>
                                      <?php else: ?>
                                          <table class="table table-bordered table-hover">
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
                              </div>
                          </div>
                          
                          <script>
                              // Initialize chart
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
                                          ]
                                      }]
                                  },
                                  options: {
                                      responsive: true,
                                      plugins: {
                                          title: {
                                              display: true,
                                              text: 'Attendance Overview'
                                          }
                                      }
                                  }
                              });

                              // PDF Generation using jsPDF
                              function generatePDF() {
                                  const studentId = <?php echo $studentId; ?>;
                                  const pdfButton = document.querySelector('.btn-export');
                                  
                                  // Show loading state
                                  const originalHtml = pdfButton.innerHTML;
                                  pdfButton.disabled = true;
                                  pdfButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating PDF...';
                                  
                                  // Fetch data for PDF
                                  fetch(`?get_pdf_data=1&student_id=${studentId}`)
                                      .then(response => {
                                          if (!response.ok) {
                                              throw new Error('Network response was not ok');
                                          }
                                          return response.json();
                                      })
                                      .then(data => {
                                          if (!data.student) {
                                              throw new Error('Student data not found');
                                          }
                                          
                                          // Initialize PDF
                                          const { jsPDF } = window.jspdf;
                                          const doc = new jsPDF();
                                          
                                          // Set document properties
                                          doc.setProperties({
                                              title: `Student Report - ${data.student.name}`,
                                              subject: 'Student Academic History',
                                              author: 'Attendance Management System'
                                          });
                                          
                                          // Add logo and title
                                          doc.setFontSize(18);
                                          doc.setTextColor(40);
                                          doc.text('Student Academic Report', 105, 20, { align: 'center' });
                                          doc.setFontSize(12);
                                          doc.text('Generated on: ' + new Date().toLocaleDateString(), 105, 28, { align: 'center' });
                                          
                                          // Add student info
                                          doc.setFontSize(14);
                                          doc.text('Student Information', 14, 40);
                                          doc.setFontSize(12);
                                          doc.text(`Name: ${data.student.name}`, 20, 50);
                                          doc.text(`Admission Number: ${data.student.admissionNumber}`, 20, 60);
                                          doc.text(`Gender: ${data.student.gender}`, 20, 70);
                                          doc.text(`Current Class: ${data.student.current_class}`, 20, 80);
                                          
                                          // Add statistics
                                          doc.setFontSize(14);
                                          doc.text('Attendance Statistics', 14, 100);
                                          doc.setFontSize(12);
                                          doc.text(`• Present Days: ${data.stats.present}`, 20, 110);
                                          doc.text(`• Absent Days: ${data.stats.absent}`, 20, 120);
                                          doc.text(`• Attendance Rate: ${data.stats.rate}%`, 20, 130);
                                          doc.text(`• Most Common Reason: ${data.stats.common_reason}`, 20, 140);
                                          
                                          // Add class history
                                          doc.setFontSize(14);
                                          doc.text('Class History', 14, 160);
                                          doc.setFontSize(12);
                                          let yPos = 170;
                                          data.history.forEach((history, index) => {
                                              doc.text(`${index + 1}. ${history.className} (${history.level})`, 20, yPos);
                                              doc.text(`   Admission: ${history.admission_number}`, 25, yPos + 7);
                                              doc.text(`   Period: ${history.start_date} to ${history.end_date}`, 25, yPos + 14);
                                              yPos += 25;
                                          });
                                          
                                          // Add attendance records (new page if needed)
                                          if (yPos > 250) {
                                              doc.addPage();
                                              yPos = 20;
                                          } else {
                                              yPos += 10;
                                          }
                                          
                                          doc.setFontSize(14);
                                          doc.text('Attendance Records', 14, yPos);
                                          yPos += 10;
                                          
                                          // Prepare table data
                                          const headers = [['Date', 'Class', 'Lesson', 'Hour', 'Status']];
                                          const attendanceData = data.attendance.map(record => [
                                              record.attendanceDate,
                                              record.className,
                                              record.lesson_title,
                                              record.hour,
                                              record.status
                                          ]);
                                          
                                          // Add table
                                          doc.autoTable({
                                              startY: yPos,
                                              head: headers,
                                              body: attendanceData,
                                              margin: { top: 20 },
                                              styles: { 
                                                  fontSize: 10,
                                                  cellPadding: 3,
                                                  overflow: 'linebreak'
                                              },
                                              headStyles: { 
                                                  fillColor: [78, 115, 223],
                                                  textColor: [255, 255, 255],
                                                  fontStyle: 'bold'
                                              },
                                              alternateRowStyles: { fillColor: [240, 240, 240] },
                                              columnStyles: {
                                                  0: { cellWidth: 25 },
                                                  1: { cellWidth: 30 },
                                                  2: { cellWidth: 40 },
                                                  3: { cellWidth: 20 },
                                                  4: { cellWidth: 20 }
                                              }
                                          });
                                          
                                          // Save the PDF
                                          doc.save(`student_report_${data.student.admissionNumber}.pdf`);
                                      })
                                      .catch(error => {
                                          console.error('Error generating PDF:', error);
                                          Swal.fire({
                                              icon: 'error',
                                              title: 'Error',
                                              text: 'Failed to generate PDF: ' + error.message
                                          });
                                      })
                                      .finally(() => {
                                          pdfButton.disabled = false;
                                          pdfButton.innerHTML = originalHtml;
                                      });
                              }
                          </script>
                          
                      <?php else: ?>
                          <div class="alert alert-danger">Student not found.</div>
                      <?php endif; ?>
                      
                  <?php endif; ?>
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
    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="../vendor/sweetalert2/sweetalert2.all.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
      $(document).ready(function() {
        $('table').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true
        });
      });
    </script>
</body>
</html>