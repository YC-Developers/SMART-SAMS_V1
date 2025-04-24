<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Fetch school name for the logged-in Dean of Study
$stmt = $conn->prepare("SELECT school FROM tbladmin WHERE Id = ? AND type = 'study'");
$stmt->bind_param("i", $_SESSION['userId']);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$school = $admin ? htmlspecialchars($admin['school']) : '';
$stmt->close();

// Query for student count
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT ts.Id) as student_count
    FROM tblstudents ts
    INNER JOIN tblclass tc ON tc.Id = ts.classId
    WHERE tc.school = ?
");
$stmt->bind_param("s", $school);
$stmt->execute();
$result = $stmt->get_result();
$studentCount = $result->fetch_assoc()['student_count'] ?? 0;
$stmt->close();

// Query for class count
$stmt = $conn->prepare("SELECT COUNT(*) as class_count FROM tblclass WHERE school = ?");
$stmt->bind_param("s", $school);
$stmt->execute();
$result = $stmt->get_result();
$classCount = $result->fetch_assoc()['class_count'] ?? 0;
$stmt->close();

// Query for teacher count
$stmt = $conn->prepare("SELECT COUNT(*) as teacher_count FROM tblteachers WHERE school = ?");
$stmt->bind_param("s", $school);
$stmt->execute();
$result = $stmt->get_result();
$teacherCount = $result->fetch_assoc()['teacher_count'] ?? 0;
$stmt->close();

// Query for attendance statistics per lesson
$attendanceData = [];
$stmt = $conn->prepare("
    SELECT l.lesson_title, 
           COUNT(ta.Id) as total_attendance,
           SUM(CASE WHEN ta.status = 'Present' THEN 1 ELSE 0 END) as present_count
    FROM lessons l
    LEFT JOIN tblattendance ta ON ta.lessonId = l.id
    LEFT JOIN tblstudents ts ON ta.studentId = ts.Id
    LEFT JOIN tblclass tc ON ts.classId = tc.Id
    WHERE tc.school = ?
    GROUP BY l.id, l.lesson_title
    HAVING total_attendance > 0
    ORDER BY l.lesson_title
");
$stmt->bind_param("s", $school);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $percentagePresent = ($row['total_attendance'] > 0) 
        ? round(($row['present_count'] / $row['total_attendance']) * 100, 2) 
        : 0;
    $attendanceData[] = [
        'lesson' => htmlspecialchars($row['lesson_title']),
        'percentage' => $percentagePresent
    ];
}
$stmt->close();

// Prepare data for charts
$chartLabels = ['Classes', 'Students', 'Teachers'];
$chartData = [$classCount, $studentCount, $teacherCount];

$attendanceLabels = array_column($attendanceData, 'lesson');
$attendancePercentages = array_column($attendanceData, 'percentage');
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
  <title>Dean of Study Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
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
            <h1 class="h3 mb-0 text-gray-800"><?php echo isset($s) ? htmlspecialchars($s) : 'Dean of Study'; ?> Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
            <!-- Students Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Students</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $studentCount;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Class Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Classes</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $classCount;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chalkboard fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Teachers Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Teachers</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $teacherCount;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Charts Section -->
          <div class="row mb-4">
            <!-- Bar Chart: Classes, Students, Teachers -->
            <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">School Overview</h6>
                </div>
                <div class="card-body">
                  <canvas id="schoolOverviewChart"></canvas>
                </div>
              </div>
            </div>
            <!-- Line Chart: Attendance Statistics -->
            <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Attendance Statistics by Lesson</h6>
                </div>
                <div class="card-body">
                  <canvas id="attendanceChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <?php include 'includes/footer.php';?>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

  <script>
    // Bar Chart: School Overview
    const ctxOverview = document.getElementById('schoolOverviewChart').getContext('2d');
    new Chart(ctxOverview, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($chartLabels); ?>,
        datasets: [{
          label: 'Count',
          data: <?php echo json_encode($chartData); ?>,
          backgroundColor: [
            'rgba(54, 162, 235, 0.6)',  // Classes
            'rgba(75, 192, 192, 0.6)',  // Students
            'rgba(255, 159, 64, 0.6)'   // Teachers
          ],
          borderColor: [
            'rgba(54, 162, 235, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Count'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Category'
            }
          }
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return `${context.label}: ${context.raw}`;
              }
            }
          }
        }
      }
    });

    // Line Chart: Attendance Statistics
    const ctxAttendance = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctxAttendance, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($attendanceLabels); ?>,
        datasets: [{
          label: 'Present (%)',
          data: <?php echo json_encode($attendancePercentages); ?>,
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            title: {
              display: true,
              text: 'Attendance (% Present)'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Lessons'
            }
          }
        },
        plugins: {
          legend: {
            display: true,
            position: 'top'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return `${context.dataset.label}: ${context.raw}%`;
              }
            }
          }
        }
      }
    });
  </script>
</body>
</html>