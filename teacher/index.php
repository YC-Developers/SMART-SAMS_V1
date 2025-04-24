<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Query to count distinct students taught by the teacher
$studentQuery = "
    SELECT COUNT(DISTINCT ts.Id) as student_count
    FROM tblstudents ts
    INNER JOIN tblclass tc ON tc.Id = ts.classId
    INNER JOIN class_lessons cl ON cl.class_id = tc.Id
    INNER JOIN lessons l ON l.id = cl.lesson_id
    WHERE l.teacher_id = ?
";
$stmt = $conn->prepare($studentQuery);
$stmt->bind_param("i", $_SESSION['userId']);
$stmt->execute();
$studentResult = $stmt->get_result();
$studentCount = $studentResult->fetch_assoc()['student_count'];
$stmt->close();

// Query to count distinct classes taught by the teacher
$classQuery = "
    SELECT COUNT(DISTINCT cl.class_id) as class_count
    FROM class_lessons cl
    INNER JOIN lessons l ON l.id = cl.lesson_id
    WHERE l.teacher_id = ?
";
$stmt = $conn->prepare($classQuery);
$stmt->bind_param("i", $_SESSION['userId']);
$stmt->execute();
$classResult = $stmt->get_result();
$classCount = $classResult->fetch_assoc()['class_count'];
$stmt->close();

// Query to count lessons assigned to the teacher
$lessonQuery = "
    SELECT COUNT(*) as lesson_count
    FROM lessons
    WHERE teacher_id = ?
";
$stmt = $conn->prepare($lessonQuery);
$stmt->bind_param("i", $_SESSION['userId']);
$stmt->execute();
$lessonResult = $stmt->get_result();
$lessonCount = $lessonResult->fetch_assoc()['lesson_count'];
$stmt->close();

// Query to get attendance statistics (Present/Absent) for students in teacher's lessons
$attendanceQuery = "
    SELECT ta.status, COUNT(*) as count
    FROM tblattendance ta
    INNER JOIN lessons l ON ta.lessonId = l.id
    WHERE l.teacher_id = ?
    GROUP BY ta.status
";
$stmt = $conn->prepare($attendanceQuery);
$stmt->bind_param("i", $_SESSION['userId']);
$stmt->execute();
$attendanceResult = $stmt->get_result();
$presentCount = 0;
$absentCount = 0;
while ($row = $attendanceResult->fetch_assoc()) {
    if ($row['status'] === 'Present') {
        $presentCount = $row['count'];
    } elseif ($row['status'] === 'Absent') {
        $absentCount = $row['count'];
    }
}
$stmt->close();
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
  <title>Teacher - Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <style>
    .chart-container {
      position: relative;
      margin: auto;
      height: 400px;
      width: 100%;
      margin-bottom: 20px;
    }
    @media (max-width: 768px) {
      .chart-container {
        height: 300px;
      }
    }
  </style>
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
            <h1 class="h3 mb-0 text-gray-800"><?php echo isset($s) ? $s : 'Teacher'; ?> Dashboard</h1>
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

            <!-- Classes Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Classes You Teach</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $classCount;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chalkboard fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Lessons Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Your Lessons</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $lessonCount;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-book fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Charts Section -->
          <div class="row">
            <!-- Teacher Statistics Bar Chart -->
            <div class="col-xl-6 col-lg-6">
              <div class="card mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Teacher Statistics</h6>
                </div>
                <div class="card-body">
                  <div class="chart-container">
                    <canvas id="teacherStatsChart"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- Student Attendance Pie Chart -->
            <div class="col-xl-6 col-lg-6">
              <div class="card mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Student Attendance in Your Lessons</h6>
                </div>
                <div class="card-body">
                  <div class="chart-container">
                    <canvas id="attendanceStatsChart"></canvas>
                  </div>
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
  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
  
  <script>
    // Teacher Statistics Bar Chart
    const teacherStatsChart = new Chart(document.getElementById('teacherStatsChart'), {
      type: 'bar',
      data: {
        labels: ['Classes', 'Students', 'Lessons'],
        datasets: [{
          label: 'Teacher Statistics',
          data: [<?php echo $classCount; ?>, <?php echo $studentCount; ?>, <?php echo $lessonCount; ?>],
          backgroundColor: [
            'rgba(54, 162, 235, 0.6)',  // Blue for Classes
            'rgba(75, 192, 192, 0.6)',  // Teal for Students
            'rgba(255, 159, 64, 0.6)'   // Orange for Lessons
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
              text: 'Metrics'
            }
          }
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            enabled: true
          }
        }
      }
    });

    // Student Attendance Pie Chart
    const attendanceStatsChart = new Chart(document.getElementById('attendanceStatsChart'), {
      type: 'pie',
      data: {
        labels: ['Present', 'Absent'],
        datasets: [{
          label: 'Attendance Status',
          data: [<?php echo $presentCount; ?>, <?php echo $absentCount; ?>],
          backgroundColor: [
            'rgba(40, 167, 69, 0.6)',  // Green for Present
            'rgba(220, 53, 69, 0.6)'   // Red for Absent
          ],
          borderColor: [
            'rgba(40, 167, 69, 1)',
            'rgba(220, 53, 69, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.label || '';
                let value = context.parsed || 0;
                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                return `${label}: ${value} (${percentage}%)`;
              }
            }
          }
        }
      }
    });
  </script>
</body>

</html>