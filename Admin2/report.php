<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = "";

// Handle form inputs for date, lesson, class, and hour selection
$reportDate = isset($_POST['report_date']) ? $_POST['report_date'] : date('Y-m-d');
$selectedLesson = isset($_POST['lesson_id']) ? $_POST['lesson_id'] : "";
$selectedClass = isset($_POST['classId']) ? $_POST['classId'] : "";
$selectedHour = isset($_POST['hour']) ? $_POST['hour'] : "";

$weekStart = date('Y-m-d', strtotime('monday this week', strtotime($reportDate)));
$weekEnd = date('Y-m-d', strtotime('sunday this week', strtotime($reportDate)));

$qm = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
$rs = $conn->query($qm);
$num = $rs->num_rows;
$rows = $rs->fetch_assoc();
$school = $rows['school'];

// Fetch lessons and classes for the dropdowns
$lessons = mysqli_query($conn, "SELECT * FROM lessons WHERE school='$school' ORDER BY lesson_title ASC");
$classes = mysqli_query($conn, "SELECT * FROM tblclass WHERE school='$school' ORDER BY className ASC");

// Fetch available hours for the given date
$hoursQuery = "SELECT DISTINCT hour FROM tblattendance WHERE attendanceDate = '$reportDate' ORDER BY hour ASC";
$hoursResult = mysqli_query($conn, $hoursQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>AMS - Attendance Reports</title>
  <link href="img/logo/attnlg.jpg" rel="icon">
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
  <style>
    /* Custom styles for responsive tables */
    .table-responsive {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }
    .table {
      min-width: 100%;
      width: 100%;
      margin-bottom: 1rem;
      background-color: #fff;
    }
    .table th, .table td {
      white-space: nowrap;
      padding: 0.75rem;
      vertical-align: middle;
      font-size: 0.9rem;
    }
    @media (max-width: 768px) {
      .table th, .table td {
        font-size: 0.8rem;
        padding: 0.5rem;
      }
    }
    @media (max-width: 576px) {
      .table th, .table td {
        font-size: 0.7rem;
        padding: 0.4rem;
      }
    }
    .table thead th {
      background-color: #f8f9fa;
      border-bottom: 2px solid #dee2e6;
    }
    .table-bordered th, .table-bordered td {
      border: 1px solid #dee2e6;
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
            <h1 class="h3 mb-0 text-gray-800">Attendance Reports</h1>
          </div>

          <!-- Report Filters -->
          <div class="card mb-4">
            <div class="card-header">
              <h6 class="m-0 font-weight-bold text-primary">Filter Reports</h6>
            </div>
            <div class="card-body">
              <form method="post">
                <div class="form-group">
                  <label for="lesson_id">Select Lesson</label>
                  <select name="lesson_id" class="form-control" required>
                    <option value="">-- All Lessons --</option>
                    <?php
                    while ($lesson = mysqli_fetch_assoc($lessons)) {
                        $selected = ($lesson['id'] == $selectedLesson) ? "selected" : "";
                        echo "<option value='{$lesson['id']}' {$selected}>{$lesson['lesson_title']}</option>";
                    }
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="classId">Select Class</label>
                  <select name="classId" class="form-control" required>
                    <option value="">-- All Classes --</option>
                    <?php
                    while ($class = mysqli_fetch_assoc($classes)) {
                        $selected = ($class['Id'] == $selectedClass) ? "selected" : "";
                        echo "<option value='{$class['Id']}' {$selected}>{$class['className']}</option>";
                    }
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="report_date">Select Date</label>
                  <input type="date" name="report_date" class="form-control" value="<?php echo $reportDate; ?>" required>
                </div>

                <button type="submit" name="view" class="btn btn-primary">Generate Report</button>
              </form>
            </div>
          </div>

          <!-- Daily Report -->
          <div class="card mb-4">
            <div class="card-header">
              <button class="btn btn-success btn-sm float-right" onclick="exportToExcel('daily-report', 'SMART-SAMS_Report_<?php echo $reportDate; ?>_Daily.xlsx')">Export to Excel</button>
              <button class="btn btn-danger btn-sm float-right mr-2" onclick="exportToPDF('daily-report', 'SMART-SAMS_Report_<?php echo $reportDate; ?>_Daily')">Export to PDF</button>
              <button class="btn btn-info btn-sm float-right mr-2" onclick="printReport('daily-report')">Print Report</button>
            </div>
            <div class="card-body" id="daily-report">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <?php
                    $que = "SELECT * FROM tbladmin WHERE Id = " . $_SESSION['userId'] . "";
                    $rsv = $conn->query($que);
                    $num = $rsv->num_rows;
                    $rows = $rsv->fetch_assoc();
                    $school = $rows['school'];

                    $a = "SELECT * FROM schools WHERE schoolName = '$school'";
                    $rh = $conn->query($a);
                    $r2 = $rh->fetch_assoc();
                    $code = $r2['code'];
                    $d = $r2['district'];
                    $p = $r2['province'];

                    echo "
                    <tr>
                      <th width='250px'><b>Province: ".$p."</b></th>
                    </tr>
                    <tr>
                      <th width='250px'><b>District: ".$d."</b></th>
                    </tr>
                    <tr>
                      <th width='250px'><b>School Name: ".$school."</b></th>
                    </tr>
                    <tr>
                      <th width='250px'><b>School Code: ".$code."</b></th>
                    </tr>
                    <tr style='border: none'><td></td><td></td><td><b>Daily Report on ".$reportDate."</b></td></tr>
                    <tr></tr><tr></tr>
                    <tr>
                      <th>NO</th>
                      <th>Student Name</th>
                      <th>Class Name</th>
                      <th>Lesson</th>
                      <th>Hour</th>
                      <th>Status</th>
                    </tr>";
                    ?>
                  </thead>
                  <tbody>
                    <?php
                    if (isset($_POST['view'])) {
                      $query = "
                        SELECT tblstudents.name, tblstudents.admissionNumber, lessons.lesson_title, tblattendance.hour, tblattendance.status, tblclass.className
                        FROM tblattendance
                        INNER JOIN tblstudents ON tblattendance.studentId = tblstudents.Id
                        INNER JOIN lessons ON tblattendance.lessonId = lessons.id
                        INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                        WHERE tblattendance.attendanceDate = '$reportDate'";
                      if ($selectedLesson) {
                          $query .= " AND tblattendance.lessonId = '$selectedLesson'";
                      }
                      if ($selectedClass) {
                          $query .= " AND tblstudents.classId = '$selectedClass'";
                      }
                      $result = mysqli_query($conn, $query);
                      $sn = 1;
                      while ($row = mysqli_fetch_assoc($result)) {
                          echo "<tr>
                              <td>{$sn}</td>
                              <td>{$row['name']}</td>
                              <td>{$row['className']}</td>
                              <td>{$row['lesson_title']}</td>
                              <td>{$row['hour']}</td>
                              <td>{$row['status']}</td>
                          </tr>";
                          $sn++;
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <h6>Generated By SMART-SAMS (SMART-School Attendance Management System) On <?php echo $reportDate;?></h6>
            </div>
          </div>

          <!-- Weekly Advanced Report -->
          <div class="card mb-4">
            <div class="card-header">
              <button class="btn btn-success btn-sm float-right" onclick="exportToExcel('advanced-weekly-report', 'AMS_Report_<?php echo $weekStart; ?>_to_<?php echo $weekEnd; ?>_Weekly.xlsx')">Export to Excel</button>
              <button class="btn btn-danger btn-sm float-right mr-2" onclick="exportToPDF('advanced-weekly-report', 'AMS_Report_<?php echo $weekStart; ?>_to_<?php echo $weekEnd; ?>_Weekly')">Export to PDF</button>
              <button class="btn btn-info btn-sm float-right mr-2" onclick="printReport('advanced-weekly-report')">Print Report</button>
            </div>
            <div class="card-body" id="advanced-weekly-report">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <?php
                    echo "
                    <tr>
                      <th width='350px'><b>Province: ".$p."</b></th>
                    </tr>
                    <tr>
                      <th width='250px'><b>District: ".$d."</b></th>
                    </tr>
                    <tr>
                      <th width='250px'><b>School Name: ".$school."</b></th>
                    </tr>
                    <tr>
                      <th width='250px'><b>School Code: ".$code."</b></th>
                    </tr>
                    <tr style='border: none'><td></td><td></td><td><h6>Weekly Report From (".$weekStart." to ".$weekEnd.")</h6></td></tr>
                    <tr></tr><tr></tr>
                    <tr>
                      <th>#</th>
                      <th>Student Name</th>
                      <th>Class Name</th>
                      <th>Lesson</th>
                      <th>Total Present</th>
                      <th>Total Absent</th>
                      <th>Overall Percentage</th>
                    </tr>";
                    ?>
                  </thead>
                  <tbody>
                    <?php
                    if (isset($_POST['view'])) {
                      $query = "
                        SELECT 
                          tblstudents.name, 
                          tblstudents.admissionNumber,
                          tblclass.className,
                          lessons.lesson_title, 
                          SUM(CASE WHEN tblattendance.status = 'Present' THEN 1 ELSE 0 END) AS total_present,
                          SUM(CASE WHEN tblattendance.status = 'Absent' THEN 1 ELSE 0 END) AS total_absent,
                          COUNT(tblattendance.status) AS total_days
                        FROM tblattendance
                        INNER JOIN tblstudents ON tblattendance.studentId = tblstudents.Id
                        INNER JOIN lessons ON tblattendance.lessonId = lessons.id
                        INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                        WHERE tblattendance.attendanceDate BETWEEN '$weekStart' AND '$weekEnd'";
                      if ($selectedLesson) {
                          $query .= " AND tblattendance.lessonId = '$selectedLesson'";
                      }
                      if ($selectedClass) {
                          $query .= " AND tblstudents.classId = '$selectedClass'";
                      }
                      $query .= " GROUP BY tblstudents.Id, lessons.id";
                      $result = mysqli_query($conn, $query);
                      $chartData = [];
                      $sn = 1;
                      while ($row = mysqli_fetch_assoc($result)) {
                          $percentage = ($row['total_present'] / $row['total_days']) * 100;
                          $chartData[] = [
                              'studentName' => $row['name'],
                              'className' => $row['className'],
                              'lessonTitle' => $row['lesson_title'],
                              'totalPresent' => $row['total_present'],
                              'totalAbsent' => $row['total_absent']
                          ];
                          echo "<tr>
                              <td>{$sn}</td>
                              <td>{$row['name']}</td>
                              <td>{$row['className']}</td>
                              <td>{$row['lesson_title']}</td>
                              <td>{$row['total_present']}</td>
                              <td>{$row['total_absent']}</td>
                              <td>" . number_format($percentage, 2) . "%</td>
                          </tr>";
                          $sn++;
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <h6>Generated By SMART-SAMS (SMART-School Attendance Management System) On <?php echo $reportDate;?></h6>
            </div>
          </div>

          <!-- Attendance Chart -->
          <div class="card mb-4">
            <div class="card-header">
              <h6 class="m-0 font-weight-bold text-primary">Attendance Chart <?php echo " From ".$weekStart." to ".$weekEnd ?></h6>
              <button class="btn btn-primary btn-sm float-right" id="downloadChart">Download Chart</button>
            </div>
            <div class="card-body">
              <canvas id="weeklyAttendanceChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
      const chartData = <?php echo json_encode($chartData ?? []); ?>;

      if (!Array.isArray(chartData) || chartData.length === 0) {
          console.error('No data available for the chart');
          document.getElementById('weeklyAttendanceChart').style.display = 'none';
          return;
      }

      const ctx = document.getElementById('weeklyAttendanceChart').getContext('2d');

      const labels = chartData.map(data => `${data.studentName} (${data.lessonTitle})`);
      const totalPresent = chartData.map(data => data.totalPresent);
      const totalAbsent = chartData.map(data => data.totalAbsent);

      const attendanceChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: labels,
              datasets: [
                  {
                      label: 'Total Present',
                      data: totalPresent,
                      backgroundColor: 'rgba(54, 162, 235, 0.7)',
                      borderColor: 'rgba(54, 162, 235, 1)',
                      borderWidth: 1
                  },
                  {
                      label: 'Total Absent',
                      data: totalAbsent,
                      backgroundColor: 'rgba(255, 99, 132, 0.7)',
                      borderColor: 'rgba(255, 99, 132, 1)',
                      borderWidth: 1
                  }
              ]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: { position: 'top' },
                  title: { display: true, text: 'Weekly Attendance Summary' }
              },
              scales: {
                  y: { beginAtZero: true }
              }
          }
      });

      // Export Chart to Image
      document.getElementById('downloadChart').addEventListener('click', function () {
          const link = document.createElement('a');
          link.download = 'Weekly_Attendance_Chart.png';
          link.href = attendanceChart.toBase64Image();
          link.click();
      });
  });

  function exportToExcel(tableId, filename) {
      const table = document.getElementById(tableId);
      const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
      const ws = wb.Sheets["Sheet1"];
      const range = XLSX.utils.decode_range(ws['!ref']);

      for (let col = range.s.c; col <= range.e.c; col++) {
          const cellAddress = { r: range.s.r, c: col };
          const cell = ws[XLSX.utils.encode_cell(cellAddress)];
          if (cell) {
              cell.s = {
                  font: { bold: true, color: { rgb: "FFFFFF" }, name: "Arial", sz: 14 },
                  fill: { fgColor: { rgb: "4CAF50" } },
                  alignment: { horizontal: "center", vertical: "center", wrapText: true },
                  border: {
                      top: { style: "medium", color: { rgb: "000000" } },
                      left: { style: "medium", color: { rgb: "000000" } },
                      bottom: { style: "medium", color: { rgb: "000000" } },
                      right: { style: "medium", color: { rgb: "000000" } }
                  }
              };
          }
      }

      for (let row = range.s.r + 1; row <= range.e.r; row++) {
          for (let col = range.s.c; col <= range.e.c; col++) {
              const cellAddress = { r: row, c: col };
              const cell = ws[XLSX.utils.encode_cell(cellAddress)];
              if (cell) {
                  const isEvenRow = row % 2 === 0;
                  cell.s = {
                      font: { name: "Arial", sz: 12 },
                      alignment: { horizontal: "center", vertical: "center", wrapText: true },
                      border: {
                          top: { style: "thin", color: { rgb: "000000" } },
                          left: { style: "thin", color: { rgb: "000000" } },
                          bottom: { style: "thin", color: { rgb: "000000" } },
                          right: { style: "thin", color: { rgb: "000000" } }
                      },
                      fill: isEvenRow ? { fgColor: { rgb: "F2F2F2" } } : { fgColor: { rgb: "FFFFFF" } }
                  };
              }
          }
      }

      const colWidths = [];
      for (let col = range.s.c; col <= range.e.c; col++) {
          let maxLength = 0;
          for (let row = range.s.r; row <= range.e.r; row++) {
              const cellAddress = { r: row, c: col };
              const cell = ws[XLSX.utils.encode_cell(cellAddress)];
              if (cell && cell.v) {
                  maxLength = Math.max(maxLength, cell.v.toString().length);
              }
          }
          colWidths.push({ wch: maxLength + 2 });
      }
      ws['!cols'] = colWidths;

      XLSX.writeFile(wb, filename);
  }

  function exportToPDF(tableId, filename) {
      const reportElement = document.getElementById(tableId);
      const opt = {
          margin: 1,
          filename: filename + '.pdf',
          html2canvas: { scale: 2 },
          jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
      };
      html2pdf().set(opt).from(reportElement).save();
  }

  function printReport(reportId) {
      const reportContent = document.getElementById(reportId).innerHTML;
      const printWindow = window.open('', '', 'height=800,width=1200');
      printWindow.document.write('<html><head><link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"><link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"><link href="css/ruang-admin.min.css" rel="stylesheet"><title>AMS</title></head><body>');
      printWindow.document.write(reportContent);
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();
  }
  </script>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>
</html>