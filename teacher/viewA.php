<?php
error_reporting(E_ALL & ~E_NOTICE); // Show all errors except notices
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = "";

// Handle form inputs for date, lesson, class, and hour selection
$selectedLesson = isset($_POST['lesson_id']) ? intval($_POST['lesson_id']) : 0;
$selectedClass = isset($_POST['classId']) ? intval($_POST['classId']) : 0;
$reportDate = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
$weekStart = date('Y-m-d', strtotime('monday this week', strtotime($reportDate)));
$weekEnd = date('Y-m-d', strtotime('sunday this week', strtotime($reportDate)));

// Fetch lessons for the dropdown
$stmt = $conn->prepare("SELECT id, lesson_title FROM lessons WHERE teacher_id = ? ORDER BY lesson_title ASC");
$stmt->bind_param("i", $_SESSION['userId']);
$stmt->execute();
$lessonsResult = $stmt->get_result();
$stmt->close();

// Fetch available hours for the given date, lesson, and class
$hoursQuery = "
    SELECT DISTINCT hour 
    FROM tblattendance 
    WHERE attendanceDate = ? 
    AND lessonId = ? 
    AND studentId IN (SELECT Id FROM tblstudents WHERE classId = ?)
    ORDER BY hour ASC
";
$stmt = $conn->prepare($hoursQuery);
$stmt->bind_param("sii", $reportDate, $selectedLesson, $selectedClass);
$stmt->execute();
$hoursResult = $stmt->get_result();
$stmt->close();
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
    @media (max-width: 768px) {
      .card-body {
        padding: 0.5rem;
      }
      .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      .table {
        font-size: 0.8rem;
      }
      .btn-group-export {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 10px;
      }
      .btn-group-export .btn {
        flex: 1 1 auto;
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
      }
    }
    .report-header {
      background-color: #f8f9fa;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
    }
    .report-title {
      font-weight: bold;
      margin-bottom: 10px;
    }
    .generated-by {
      font-style: italic;
      font-size: 0.9rem;
      text-align: center;
      margin-top: 20px;
      color: #6c757d;
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
                  <select name="lesson_id" class="form-control">
                    <option value="0">-- All Lessons --</option>
                    <?php
                    while ($lesson = $lessonsResult->fetch_assoc()) {
                        $selected = ($lesson['id'] == $selectedLesson) ? "selected" : "";
                        echo "<option value='{$lesson['id']}' {$selected}>" . htmlspecialchars($lesson['lesson_title']) . "</option>";
                    }
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="classId">Select Class</label>
                  <select required name="classId" class="form-control">
                    <option value="0">-- Select Class --</option>
                    <?php
                    $classQuery = "
                        SELECT DISTINCT tc.Id, tc.className
                        FROM tblclass tc
                        INNER JOIN class_lessons cl ON cl.class_id = tc.Id
                        INNER JOIN lessons l ON l.id = cl.lesson_id
                        WHERE l.teacher_id = ?
                        ORDER BY tc.className ASC
                    ";
                    $stmt = $conn->prepare($classQuery);
                    $stmt->bind_param("i", $_SESSION['userId']);
                    $stmt->execute();
                    $classResult = $stmt->get_result();
                    while ($row = $classResult->fetch_assoc()) {
                        $selected = ($row['Id'] == $selectedClass) ? "selected" : "";
                        echo "<option value='{$row['Id']}' {$selected}>" . htmlspecialchars($row['className']) . "</option>";
                    }
                    $stmt->close();
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="report_date">Select Date</label>
                  <input type="date" name="date" class="form-control" required value="<?php echo htmlspecialchars($reportDate); ?>">
                </div>

                <button type="submit" class="btn btn-primary" name="view">View Attendance</button>
              </form>
            </div>
          </div>

          <!-- Daily Report -->
          <?php
          if (isset($_POST['view']) && $selectedClass > 0) {
            // Fetch class arm name
            $stmt = $conn->prepare("SELECT classArmName FROM tblclassarms WHERE classId = ?");
            $stmt->bind_param("i", $selectedClass);
            $stmt->execute();
            $classArmResult = $stmt->get_result();
            $classArm = $classArmResult->fetch_assoc();
            $classArmName = $classArm ? htmlspecialchars($classArm['classArmName']) : '';
            $stmt->close();

            // Fetch class name
            $stmt = $conn->prepare("SELECT className FROM tblclass WHERE Id = ?");
            $stmt->bind_param("i", $selectedClass);
            $stmt->execute();
            $classResult = $stmt->get_result();
            $class = $classResult->fetch_assoc();
            $className = $class ? htmlspecialchars($class['className']) : '';
            $stmt->close();

            // Fetch teacher and school details
            $stmt = $conn->prepare("SELECT school FROM tblteachers WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['userId']);
            $stmt->execute();
            $teacherResult = $stmt->get_result();
            $teacher = $teacherResult->fetch_assoc();
            $school = $teacher ? htmlspecialchars($teacher['school']) : '';
            $stmt->close();

            // Fetch school details
            $stmt = $conn->prepare("SELECT code, district, province FROM schools WHERE schoolName = ?");
            $stmt->bind_param("s", $school);
            $stmt->execute();
            $schoolResult = $stmt->get_result();
            $schoolDetails = $schoolResult->fetch_assoc();
            $schoolCode = $schoolDetails ? htmlspecialchars($schoolDetails['code']) : '';
            $district = $schoolDetails ? htmlspecialchars($schoolDetails['district']) : '';
            $province = $schoolDetails ? htmlspecialchars($schoolDetails['province']) : '';
            $stmt->close();
          ?>
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h6 class="m-0 font-weight-bold text-primary">Daily Attendance Report</h6>
              <div class="btn-group-export">
                <button class="btn btn-success btn-sm" onclick="exportToExcel('daily-report', 'SAMS_Report_<?php echo $reportDate; ?>_Daily.xlsx')">
                  <i class="fas fa-file-excel"></i> Excel
                </button>
                <button class="btn btn-danger btn-sm" onclick="exportToPDF('daily-report', 'SAMS_Report_<?php echo $classArmName . '_' . $reportDate; ?>_Daily')">
                  <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button class="btn btn-info btn-sm" onclick="printReport('daily-report')">
                  <i class="fas fa-print"></i> Print
                </button>
              </div>
            </div>
            <div class="card-body">
              <div id="daily-report">
                <div class="report-header">
                  <div class="report-title">Province: <?php echo $province; ?></div>
                  <div class="report-title">District: <?php echo $district; ?></div>
                  <div class="report-title">School Name: <?php echo $school; ?></div>
                  <div class="report-title">School Code: <?php echo $schoolCode; ?></div>
                  <div class="report-title">Daily Report for <?php echo $className; ?> on <?php echo htmlspecialchars($reportDate); ?></div>
                </div>

                <div class="table-responsive">
                  <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                      <tr>
                        <th>NO</th>
                        <th>Student Name</th>
                        <th>Class Name</th>
                        <th>Lesson</th>
                        <th>Hour</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $attendanceQuery = "
                          SELECT ts.name, ts.admissionNumber, tc.className, l.lesson_title, ta.hour, ta.status
                          FROM tblattendance ta
                          INNER JOIN tblstudents ts ON ta.studentId = ts.Id
                          INNER JOIN lessons l ON ta.lessonId = l.id
                          INNER JOIN tblclass tc ON tc.Id = ts.classId
                          WHERE ta.attendanceDate = ?
                          AND ts.classId = ?
                          AND l.teacher_id = ?
                      ";
                      if ($selectedLesson > 0) {
                          $attendanceQuery .= " AND ta.lessonId = ?";
                          $stmt = $conn->prepare($attendanceQuery);
                          $stmt->bind_param("siii", $reportDate, $selectedClass, $_SESSION['userId'], $selectedLesson);
                      } else {
                          $stmt = $conn->prepare($attendanceQuery);
                          $stmt->bind_param("sii", $reportDate, $selectedClass, $_SESSION['userId']);
                      }
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $sn = 1;
                      while ($row = $result->fetch_assoc()) {
                          echo "<tr>
                              <td>$sn</td>
                              <td>" . htmlspecialchars($row['name']) . "</td>
                              <td>" . htmlspecialchars($row['className']) . "</td>
                              <td>" . htmlspecialchars($row['lesson_title']) . "</td>
                              <td>" . htmlspecialchars($row['hour']) . "</td>
                              <td>" . htmlspecialchars($row['status']) . "</td>
                          </tr>";
                          $sn++;
                      }
                      $stmt->close();
                      ?>
                    </tbody>
                  </table>
                </div>
                <div class="generated-by">
                  Generated By SMART-SAMS (SMART-School Attendance Management System) On <?php echo htmlspecialchars($reportDate); ?>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>

  <script>
    function exportToExcel(tableId, filename) {
      const table = document.getElementById(tableId);
      const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });

      // Get the sheet
      const ws = wb.Sheets["Sheet1"];

      // Define the range of the data
      const range = XLSX.utils.decode_range(ws['!ref']);

      // Apply styles to the header row
      for (let col = range.s.c; col <= range.e.c; col++) {
          const cellAddress = { r: range.s.r, c: col };
          const cell = ws[XLSX.utils.encode_cell(cellAddress)];

          if (cell) {
              cell.s = {
                  font: {
                      bold: true, 
                      color: { rgb: "FFFFFF" }, 
                      name: "Arial", 
                      sz: 14 
                  },
                  fill: { fgColor: { rgb: "4CAF50" } },
                  alignment: { 
                      horizontal: "center", 
                      vertical: "center", 
                      wrapText: true 
                  },
                  border: {
                      top: { style: "medium", color: { rgb: "000000" } },
                      left: { style: "medium", color: { rgb: "000000" } },
                      bottom: { style: "medium", color: { rgb: "000000" } },
                      right: { style: "medium", color: { rgb: "000000" } }
                  }
              };
          }
      }

      // Apply styles to all rows (data rows)
      for (let row = range.s.r + 1; row <= range.e.r; row++) {
          for (let col = range.s.c; col <= range.e.c; col++) {
              const cellAddress = { r: row, c: col };
              const cell = ws[XLSX.utils.encode_cell(cellAddress)];

              if (cell) {
                  const isEvenRow = row % 2 === 0;
                  cell.s = {
                      font: {
                          name: "Arial", 
                          sz: 12
                      },
                      alignment: { 
                          horizontal: "center", 
                          vertical: "center", 
                          wrapText: true
                      },
                      border: {
                          top: { style: "thin", color: { rgb: "000000" } },
                          left: { style: "thin", color: { rgb: "000000" } },
                          bottom: { style: "thin", color: { rgb: "000000" } },
                          right: { style: "thin", color: { rgb: "000000" } }
                      },
                      fill: isEvenRow ? { fgColor: { rgb: "F2F2F2" } } : { fgColor: { rgb: "FFFFFF" } },
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

      // Write the Excel file
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
      printWindow.document.write('<html><head>');
      printWindow.document.write('<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">');
      printWindow.document.write('<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">');
      printWindow.document.write('<link href="css/ruang-admin.min.css" rel="stylesheet">');
      printWindow.document.write('<style>');
      printWindow.document.write('@media print {');
      printWindow.document.write('  .table-responsive { width: 100%; overflow: visible !important; }');
      printWindow.document.write('  .table { font-size: 12px; }');
      printWindow.document.write('  .report-header { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; }');
      printWindow.document.write('  .thead-dark th { background-color: #343a40 !important; color: white !important; -webkit-print-color-adjust: exact; }');
      printWindow.document.write('}');
      printWindow.document.write('</style>');
      printWindow.document.write('<title>SMART-SAMS Report</title></head><body>');
      printWindow.document.write(reportContent);
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      setTimeout(() => {
        printWindow.print();
      }, 500);
    }
  </script>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>
</html>