<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session2.php';

$statusMsg = "";

// Handle form inputs for date, lesson, class, and hour selection
$reportDate = isset($_POST['report_date']) ? $_POST['report_date'] : date('Y-m-d');

$weekStart = date('Y-m-d', strtotime('monday this week', strtotime($reportDate)));
$weekEnd = date('Y-m-d', strtotime('sunday this week', strtotime($reportDate)));



$reg = $conn->real_escape_string($_SESSION["regNumber"]);
$z = "SELECT * FROM tblstudents WHERE admissionNumber = '$reg'";
$R = $conn->query($z);
$cx = $R->fetch_assoc();
$cid = $cx["classId"];
$Name = $cx["name"];
$sid = $cx["Id"];
$selectedClass = $cid;  // Corrected to classId


$z2 = "SELECT * FROM `tblclass` WHERE Id = '$cid'";
$R2 = $conn->query($z2);
$cx2 = $R2->fetch_assoc();
$school = $cx2["school"];


// Fetch lessons and classes for the dropdowns
$classes = mysqli_query($conn, "SELECT * FROM tblclass WHERE school='$school' ORDER BY className ASC");

// Fetch available hours for the given date (assuming hours are stored for each lesson)
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
            <h1 class="h3 mb-0 text-gray-800">General Lessons Attendance Reports Of Your Child</h1>
          </div>


          <!-- Weekly Advanced Report -->
          <div class="card mb-4">
    <div class="card-header">
        <button class="btn btn-success btn-sm float-right" onclick="exportToExcel('advanced-weekly-report', 'AMS_Report_<?php echo $weekStart; ?>_to_<?php echo $weekEnd; ?>_Weekly.xlsx')">Export to Excel</button>
        <button class="btn btn-danger btn-sm float-right mr-2" onclick="exportToPDF('advanced-weekly-report', 'AMS_Report_<?php echo $weekStart; ?>_to_<?php echo $weekEnd; ?>_Weekly')">Export to PDF</button>
        <button class="btn btn-info btn-sm float-right mr-2" onclick="printReport('advanced-weekly-report')">Print Report</button>
    </div>
    <div class="card-body" id="advanced-weekly-report">
        <table class="table table-bordered">
            <thead>

            <?php
echo "

                  
                  <tr style='border: none'><td></td><td></td><td><b>Weekly Attendance Report In All Lessons From ".$weekStart." to ".$weekEnd."</b></td></tr>
                   <tr> </tr> <tr> 
                   ";
                   ?>
                <tr>
                    <th>Student Name</th>
                    <th>Class Name</th>
                    <th>Total Present</th>
                    <th>Total Absent</th>
                    <th>Overall Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
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
                    WHERE tblstudents.Id='$sid' AND tblattendance.attendanceDate BETWEEN '$weekStart' AND '$weekEnd'";
                if ($selectedClass) {
                    $query .= " AND tblstudents.classId = '$selectedClass'";
                }
                $query .= " GROUP BY tblstudents.Id";
                $result = mysqli_query($conn, $query);
                $chartData = []; // To store data for the chart
                while ($row = mysqli_fetch_assoc($result)) {
                    $percentage = ($row['total_present'] / $row['total_days']) * 100;
                    $chartData[] = [
                        'name' => $row['name'],
                        'total_present' => $row['total_present'],
                        'total_absent' => $row['total_absent']
                    ];
                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['className']}</td>
                        <td>{$row['total_present']}</td>
                        <td>{$row['total_absent']}</td>
                        <td>" . number_format($percentage, 2) . "%</td>
                    </tr>";
                    $sn++;
                }
            
                ?>
            </tbody>
        </table>
        <h6>Generated By SMART-SAMS (SMART-School Attendance Management System) On <?php echo $reportDate;?></h6>

    </div>
</div>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">All Lessons Attendance Chart From <?php echo $weekStart." to ".$weekEnd ?></h6>
        <button class="btn btn-primary btn-sm float-right" id="downloadChart">Download Chart</button>
    </div>
    <div class="card-body">
        <canvas id="attendanceChart"></canvas>
    </div>
</div>
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
</script>
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
  printWindow.document.write('<html><head>  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"><link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"><link href="css/ruang-admin.min.css" rel="stylesheet"><title>AMS </title></head><body>');
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
