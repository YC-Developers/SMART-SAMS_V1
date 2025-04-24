<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = "";

// Handle form inputs for date, lesson, class, and hour selection
$reportDate = isset($_POST['report_date']) ? $_POST['report_date'] : date('Y-m-d');
$selectedClass = isset($_POST['classId']) ? $_POST['classId'] : "";

$weekStart = date('Y-m-d', strtotime('monday this week', strtotime($reportDate)));
$weekEnd = date('Y-m-d', strtotime('sunday this week', strtotime($reportDate)));
$qm = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
$rs = $conn->query($qm);
$num = $rs->num_rows;
$rows = $rs->fetch_assoc();
$school = $rows['school'];

// Fetch classes for the dropdown
$classes = mysqli_query($conn, "SELECT * FROM tblclass WHERE school='$school' ORDER BY className ASC");
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
    /* Responsive table styles */
    .table-responsive {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    .table {
      width: 100%;
      min-width: 600px; /* Ensures table doesn't collapse too much */
      font-size: 14px; /* Slightly smaller font for mobile */
    }

    .table th, .table td {
      padding: 8px; /* Reduced padding for mobile */
      white-space: nowrap; /* Prevent text wrapping */
      text-align: center;
    }

    /* Adjust font size for smaller screens */
    @media (max-width: 768px) {
      .table {
        font-size: 12px;
      }
      .table th, .table td {
        padding: 6px;
      }
    }

    /* Ensure buttons are responsive */
    .btn-sm {
      font-size: 0.8rem;
      padding: 0.25rem 0.5rem;
    }

    /* Adjust chart canvas for mobile */
    #attendanceChart {
      max-width: 100%;
      height: auto;
    }

    /* Improve readability for school info */
    .school-info th {
      font-size: 14px;
      white-space: normal;
    }

    @media (max-width: 576px) {
      .school-info th {
        font-size: 12px;
      }
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
            <h1 class="h3 mb-0 text-gray-800">General Attendance Reports</h1>
          </div>

          <!-- Report Filters -->
          <div class="card mb-4">
            <div class="card-header">
              <h6 class="m-0 font-weight-bold text-primary">Filter Reports</h6>
            </div>
            <div class="card-body">
              <form method="post">
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
                  <label for="report_date">Select Date In Week</label>
                  <input type="date" name="report_date" class="form-control" value="<?php echo $reportDate; ?>" required>
                </div>

                <button type="submit" name="view" class="btn btn-primary">Generate Report</button>
              </form>
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
                <table class="table table-bordered school-info">
                  <?php
                  $que = "SELECT * FROM tbladmin WHERE Id = " . $_SESSION['userId'] . "";
                  $rsv = $conn->query($que);
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
                  <tr style='border: none'><td><b>Weekly Attendance Report In All Lessons From ".$weekStart." to ".$weekEnd."</b></td></tr>
                  <tr></tr>
                  ";
                  ?>
                </table>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Student Name</th>
                      <th>Class Name</th>
                      <th>Total Present</th>
                      <th>Total Absent</th>
                      <th>Overall Percentage</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if (isset($_POST['view'])) {
                      $query = "
                        SELECT 
                          tblstudents.name, 
                          tblstudents.admissionNumber,
                          tblclass.className,
                          SUM(CASE WHEN tblattendance.status = 'Present' THEN 1 ELSE 0 END) AS total_present,
                          SUM(CASE WHEN tblattendance.status = 'Absent' THEN 1 ELSE 0 END) AS total_absent,
                          COUNT(tblattendance.status) AS total_days
                        FROM tblattendance
                        INNER JOIN tblstudents ON tblattendance.studentId = tblstudents.Id
                        INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                        WHERE tblattendance.attendanceDate BETWEEN '$weekStart' AND '$weekEnd'";
                      if ($selectedClass) {
                        $query .= " AND tblstudents.classId = '$selectedClass'";
                      }
                      $query .= " GROUP BY tblstudents.Id";
                      $result = mysqli_query($conn, $query);
                      $sn = 1;
                      $chartData = [];
                      while ($row = mysqli_fetch_assoc($result)) {
                        $percentage = ($row['total_days'] > 0) ? ($row['total_present'] / $row['total_days']) * 100 : 0;
                        $chartData[] = [
                          'name' => $row['name'],
                          'total_present' => $row['total_present'],
                          'total_absent' => $row['total_absent']
                        ];
                        echo "<tr>
                          <td>{$sn}</td>
                          <td>{$row['name']}</td>
                          <td>{$row['className']}</td>
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
              <h6 class="m-0 font-weight-bold text-primary">All Lessons Attendance Chart <?php echo " From ".$weekStart." to ".$weekEnd ?></h6>
              <button class="btn btn-primary btn-sm float-right" id="downloadChart">Download Chart</button>
            </div>
            <div class="card-body">
              <canvas id="attendanceChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const chartData = <?php echo json_encode($chartData); ?>;
    const ctx = document.getElementById('attendanceChart').getContext('2d');

    const labels = chartData.map(data => data.name);
    const totalPresent = chartData.map(data => data.total_present);
    const totalAbsent = chartData.map(data => data.total_absent);

    const attendanceChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Total Present',
            data: totalPresent,
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          },
          {
            label: 'Total Absent',
            data: totalAbsent,
            backgroundColor: 'rgba(255, 99, 132, 0.5)',
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

    // Chart download functionality
    document.getElementById('downloadChart').addEventListener('click', function () {
      const link = document.createElement('a');
      link.download = 'attendance_chart.png';
      link.href = attendanceChart.toBase64Image();
      link.click();
    });
  });

  function exportToExcel(tableId, filename) {
    const table = document.getElementById(tableId);
    const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
    const ws = wb.Sheets["Sheet1"];
    const range = XLSX.utils.decode_range(ws['!ref']);

    // Apply styles to header row
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

    // Apply styles to data rows
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

    // Set column widths
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
    printWindow.document.write('<html><head><link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"><link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"><link href="css/ruang-admin.min.css" rel="stylesheet"><style>.table-responsive { overflow-x: auto; } .table { font-size: 12px; } .table th, .table td { padding: 6px; text-align: center; }</style><title>AMS</title></head><body>');
    printWindow.document.write(reportContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
  }
  </script>
</body>

</html>