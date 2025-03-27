<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';
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
  <title>Dean of Discipline Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <style>
    .attendance-chart { 
      background: white; 
      border-radius: 12px; 
      padding: 20px; 
      margin-top: 20px; 
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); 
    }
    .chart-container { 
      position: relative; 
      height: 300px; 
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
            <h1 class="h3 mb-0 text-gray-800"><?php echo $s?> Dean of Discipline Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
            <!-- Students Card -->
            <?php 
            $qm = "SELECT * FROM tbladmin WHERE Id = " . $_SESSION['userId'];
            $rs = $conn->query($qm);
            $rows = $rs->fetch_assoc();
            $school = $rows['school'];

            $query = "SELECT tblstudents.Id, tblstudents.classId
                      FROM tblstudents
                      INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                      WHERE tblclass.school = '$school'";
            $rs = $conn->query($query);
            $totalStudents = $rs->num_rows;
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Students</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totalStudents;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sick Students Card -->
            <?php 
            $sickQuery = "SELECT COUNT(DISTINCT a.regNumber) as sickCount 
                          FROM absents a
                          INNER JOIN tblstudents s ON s.admissionNumber = a.regNumber
                          INNER JOIN tblclass c ON c.Id = s.classId
                          WHERE c.school = '$school' 
                          AND a.reason = 'Sick' 
                          AND MONTH(a.date) = MONTH(CURDATE()) 
                          AND YEAR(a.date) = YEAR(CURDATE())";
            $rs = $conn->query($sickQuery);
            $sickRow = $rs->fetch_assoc();
            $sickStudents = $sickRow['sickCount'];
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Sick Students</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $sickStudents;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <span>This Month</span>
                      </div>
                    </div>
                    <div class="col-auto">
                    <i class="fas fa-user-md fa-2x text-success"></i>                   </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Absents Today Card -->
            <?php 
            $absentQuery = "SELECT COUNT(DISTINCT a.regNumber) as absentCount 
                            FROM absents a
                            INNER JOIN tblstudents s ON s.admissionNumber = a.regNumber
                            INNER JOIN tblclass c ON c.Id = s.classId
                            WHERE c.school = '$school' 
                            AND a.date = CURDATE()";
            $rs = $conn->query($absentQuery);
            $absentRow = $rs->fetch_assoc();
            $absentToday = $absentRow['absentCount'];
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Absent Students Today</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $absentToday;?></div>
                    </div>
                    <div class="col-auto">
                    <i class="fas fa-user-slash fa-2x text-warning"></i>                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Students at Home Card -->
            <?php 
            $homeQuery = "SELECT COUNT(DISTINCT a.regNumber) as homeCount 
                          FROM absents a
                          INNER JOIN tblstudents s ON s.admissionNumber = a.regNumber
                          INNER JOIN tblclass c ON c.Id = s.classId
                          WHERE c.school = '$school' 
                          AND a.reason = 'Home' 
                          AND MONTH(a.date) = MONTH(CURDATE()) 
                          AND YEAR(a.date) = YEAR(CURDATE())";
            $rs = $conn->query($homeQuery);
            $homeRow = $rs->fetch_assoc();
            $studentsAtHome = $homeRow['homeCount'];
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Students at Home</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $studentsAtHome;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <span>This Month</span>
                      </div>
                    </div>
                    <div class="col-auto">
                    <i class="fas fa-user fa-2x text-primary"></i><i class="fas fa-home fa-2x text-primary"></i>                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Classes Card -->
            <?php 
            $classQuery = "SELECT * FROM tblclass WHERE school = '$school'";
            $rs = $conn->query($classQuery);
            $classCount = $rs->num_rows;
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Classes</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $classCount;?></div>
                    </div>
                    <div class="col-auto">
                    <i class="fas fa-chalkboard-teacher fa-2x text-info"></i>                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Class Arms Card -->
            <?php 
            $classArmQuery = "SELECT tblclassarms.classId 
                              FROM tblclassarms
                              INNER JOIN tblclass ON tblclass.Id = tblclassarms.classId
                              WHERE tblclass.school = '$school'";
            $rs = $conn->query($classArmQuery);
            $classArmCount = $rs->num_rows;
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Class Arms</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $classArmCount;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-code-branch fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Terms Card -->
            <?php 
            $termQuery = "SELECT * FROM tblterm";
            $rs = $conn->query($termQuery);
            $termCount = $rs->num_rows;
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Terms</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $termCount;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-th fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Real-Time Attendance Chart -->
          <div class="row">
            <div class="col-xl-12">
              <div class="attendance-chart">
                <h4 class="text-center mb-4">Daily Attendance Analysis In Prep (Last 30 Days - absents)</h4>
                <div class="chart-container">
                  <canvas id="dailyAttendanceChart"></canvas>
                </div>
              </div>
            </div>
          </div>
          <!-- Row -->
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('dailyAttendanceChart').getContext('2d');
    let dailyChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [],
        datasets: []
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { 
          y: { beginAtZero: true, title: { display: true, text: 'Number of Students' } }, 
          x: { title: { display: true, text: 'Date' } } 
        },
        plugins: { 
          legend: { position: 'top' }, 
          tooltip: { mode: 'index', intersect: false } 
        }
      }
    });

    // Real-time update function
    function updateChart() {
      $.ajax({
        url: 'fetch_attendance_data.php',
        method: 'POST',
        data: { 
          school: '<?php echo $school; ?>',
          totalStudents: <?php echo $totalStudents; ?>
        },
        dataType: 'json',
        success: function(data) {
          dailyChart.data.labels = data.labels;
          dailyChart.data.datasets = [
            { 
              label: 'Present Students', 
              data: data.presentData, 
              backgroundColor: 'rgba(46, 204, 113, 0.7)', 
              borderColor: 'rgba(46, 204, 113, 1)', 
              borderWidth: 1 
            }
          ];

          // Add Morning Absences if present in data
          if (data.morningAbsentData.some(count => count > 0)) {
            dailyChart.data.datasets.push({
              label: 'Morning Absent', 
              data: data.morningAbsentData, 
              backgroundColor: 'rgba(231, 76, 60, 0.7)', 
              borderColor: 'rgba(231, 76, 60, 1)', 
              borderWidth: 1 
            });
          }

          // Add Evening Absences if present in data
          if (data.eveningAbsentData.some(count => count > 0)) {
            dailyChart.data.datasets.push({
              label: 'Evening Absent', 
              data: data.eveningAbsentData, 
              backgroundColor: 'rgba(255, 165, 0, 0.7)', 
              borderColor: 'rgba(255, 165, 0, 1)', 
              borderWidth: 1 
            });
          }

          dailyChart.update();
        },
        error: function(xhr, status, error) {
          console.error('Error fetching real-time data:', error);
        }
      });
    }

    // Update every 10 seconds
    setInterval(updateChart, 10000);
    updateChart(); // Initial call
  });
  </script>
</body>

</html>