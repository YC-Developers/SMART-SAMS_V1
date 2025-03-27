<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = "";

// Handle form inputs for date, lesson, class, and hour selection
$selectedLesson = isset($_POST['lesson_id']) ? $_POST['lesson_id'] : "";
$selectedHour = isset($_POST['hour']) ? $_POST['hour'] : "";  // Hour selection

$weekStart = date('Y-m-d', strtotime('monday this week', strtotime($reportDate)));
$weekEnd = date('Y-m-d', strtotime('sunday this week', strtotime($reportDate)));

// Fetch lessons and classes for the dropdowns
$lessons = mysqli_query($conn, "SELECT * FROM lessons WHERE teacher_id = ".$_SESSION['userId']." ORDER BY lesson_title ASC");
// Fetch available hours for the given date (assuming hours are stored for each lesson)
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
                  <select name="lesson_id" class="form-control" >
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
                    <?php
                                            $query = "SELECT class_lessons.lesson_id,class_lessons.class_id,tblclass.Id,tblclass.className,tblclass.totalStudents,tblclass.boys,tblclass.girls
                                            FROM class_lessons
                                            INNER JOIN lessons ON lessons.id = class_lessons.lesson_id
                                            INNER JOIN tblclass ON tblclass.Id = class_lessons.class_id
                                            WHERE lessons.teacher_id = ".$_SESSION['userId']."
                                            ";
                                            $result = $conn->query($query);
                                            $num = $result->num_rows;		
                                            if ($num > 0){
                                              echo ' <select required name="classId" class="form-control">';
                                              echo'<option value="">--Select Class--</option>';
                                              while ($rows = $result->fetch_assoc()){
                                              echo'<option value="'.$rows['Id'].'" >'.$rows['className'].'</option>';
                                                  }
                    
                                                      echo '</select>';
                                                  }
                                          ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="report_date">Select Date</label>
                  <input type="date" name="date" class="form-control" required>
                </div>


                <button type="submit" class="btn btn-primary" name="view">View Attendance</button>
              </form>
            </div>
          </div>

          <!-- Daily Report -->
           <?php
                              $reportDate = $_POST['date'];
                              $selectedClass = $_POST['classId'];

                              $c = "SELECT * FROM tblclassarms WHERE classId=".$selectedClass."";
                              $rs = $conn->query($c);
                              $v = $rs->fetch_assoc();
                              $r = $v["classArmName"];
            

           ?>
          <div class="card mb-4">
            <div class="card-header">
              <button class="btn btn-success btn-sm float-right" onclick="exportToExcel('daily-report', 'SAMS_Report_<?php echo $reportDate; ?>_Daily.xlsx')">Export to Excel</button>
              <button class="btn btn-danger btn-sm float-right mr-2" onclick="exportToPDF('daily-report', 'SAMS_Report_<?php echo $r.'_'.$reportDate; ?>_Daily')">Export to PDF</button>
              <button class="btn btn-info btn-sm float-right mr-2" onclick="printReport('daily-report')">Print Report</button>
            </div>
            <div class="card-body" id="daily-report">
              <table class="table table-bordered">
                <thead>
                
                  


                  
                  <?php
                  if (isset($_POST['view'])) {
                   
                 
                  $reportDate = $_POST['date'];
                  $selectedClass = $_POST['classId'];

                  $cl = "SELECT * FROM tblclass WHERE id=".$selectedClass."";
                  $res = $conn->query($cl);
                  $x = $res->fetch_assoc();
                  $r = $x["className"];

                  
  $que = "SELECT * FROM `tblteachers`  WHERE Id = " . $_SESSION['userId'] . "";
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

                  
                  <tr style='border: none'><td></td><td></td><td><b>Daily Report for ".$r." on ".$reportDate."</b></td></tr>
                   <tr> </tr> <tr> 
                  <th>NO</th>
                    <th>Student Name</th>
                    <th>Class Name</th>
                    <th>Lesson</th>
                    <th>Hour</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
";
                  $query = "
                    SELECT tblstudents.name, tblstudents.admissionNumber, tblclass.className, lessons.lesson_title, tblattendance.hour, tblattendance.status
                    FROM tblattendance
                    INNER JOIN tblstudents ON tblattendance.studentId = tblstudents.Id
                    INNER JOIN lessons ON tblattendance.lessonId = lessons.id
                    INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                    WHERE tblattendance.attendanceDate = '$reportDate' AND tblattendance.lessonId = '$selectedLesson' AND tblstudents.classId = '$selectedClass'
                    ";
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
              <h6>Generated By SMART-SAMS (SMART-School Attendance Management System) On <?php echo $reportDate;?></h6>

            </div>
          </div>



  <script>
function exportToExcel(tableId, filename) {
    const table = document.getElementById(tableId);
    const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });

    // Get the sheet
    const ws = wb.Sheets["Sheet1"];

    // Define the range of the data
    const range = XLSX.utils.decode_range(ws['!ref']); // Get the range of the data

    // Apply styles to the header row
    for (let col = range.s.c; col <= range.e.c; col++) {
        const cellAddress = { r: range.s.r, c: col }; // Header row
        const cell = ws[XLSX.utils.encode_cell(cellAddress)];

        if (cell) {
            cell.s = {
                font: {
                    bold: true, 
                    color: { rgb: "FFFFFF" }, 
                    name: "Arial", 
                    sz: 14 
                },
                fill: { fgColor: { rgb: "4CAF50" } }, // Green header background
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
                // Alternate row colors for readability
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
                    fill: isEvenRow ? { fgColor: { rgb: "F2F2F2" } } : { fgColor: { rgb: "FFFFFF" } }, // Alternate row colors
                };
            }
        }
    }

    // Set column widths (optional, for better readability)
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
        colWidths.push({ wch: maxLength + 2 }); // Adding extra width for padding
    }
    ws['!cols'] = colWidths;

    // Write the Excel file
    XLSX.writeFile(wb, filename);
}


    function exportToPDF(tableId, filename) {
      const reportElement = document.getElementById(tableId) ;
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
  printWindow.document.write('<html><head>  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"><link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"><link href="css/ruang-admin.min.css" rel="stylesheet"><title>SMART-SAMS </title></head><body>');
  printWindow.document.write(reportContent);
    printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
}

  </script>



</body>
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/ruang-admin.min.js"></script>


</html>
