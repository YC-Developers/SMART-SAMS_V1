<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Weekly Attendance Statistics</title>
    <link href="img/logo/attnlg.jpg" rel="icon">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .export-buttons { margin-top: 10px; }
        .table-responsive { overflow-x: auto; }
    </style>
</head>

<body id="page-top">
<div id="wrapper">
    <?php include "Includes/sidebar.php";?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include "Includes/topbar.php";?>

            <div class="container-fluid" id="container-wrapper">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Weekly Attendance Statistics</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="./">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Weekly Attendance</li>
                    </ol>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-dark">View Attendance by Week</h6>
                            </div>
                            <div class="card-body">
                                <form method="post" id="filterForm">
                                    <div class="form-group row mb-3">
                                        <div class="col-xl-6">
                                            <label>Select Class<span class="text-danger">*</span></label>
                                            <?php
                                            $qry = "SELECT * FROM tblclass WHERE school='$s' ORDER BY className ASC";
                                            $result = $conn->query($qry);
                                            if ($result->num_rows > 0) {
                                                echo '<select required name="classId" class="form-control mb-3">';
                                                echo '<option value="">--Select Class--</option>';
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="'.$row['Id'].'">'.$row['className'].'</option>';
                                                }
                                                echo '</select>';
                                            }
                                            ?>
                                        </div>

                                        <div class="col-xl-6">
                                            <label>Select Week<span class="text-danger">*</span></label>
                                            <select required name="date" class="form-control mb-3">
                                                <option value="">---Select Week---</option>
                                                <?php
                                                  $qm = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
                                                  $rs = $conn->query($qm);
                                                  $num = $rs->num_rows;
                                                  $rows = $rs->fetch_assoc();
                                                  $school = $rows['school']; 
                                                
                                                $q = "SELECT * FROM tblsessionterm WHERE school='$school'";
                                                $res = $conn->query($q);
        // Loop through the sessions/terms
        while ($rows = $res->fetch_assoc()) {
            $start_date = $rows['start'];
            $end_date = date("Y-m-d"); // Use today's date as the end date
            $weeks = get_weeks($start_date, $end_date); // Get all weeks
            
            // Loop through the weeks
            foreach ($weeks as $week) {
                // Only display weeks with no remaining days
                if ($week['remaining_days'] === 0) {
                    echo "<option value='{$week['start_date']} AND {$week['end_date']}'>{$week['week']}</option>";
                }
            }
        }
        ?>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" name="view" class="btn btn-primary">Filter</button>
                                </form>
                            </div>
                        </div>

                        <?php
                        function remaining_days($start_date, $end_date) {
                            $start_date_obj = new DateTime($start_date);
                            $end_date_obj = new DateTime($end_date);
                            $today = new DateTime();
                        
                            if ($start_date_obj <= $today && $today <= $end_date_obj) {
                                $remaining_days = $end_date_obj->diff($today)->format('%a');
                                return $remaining_days + 1;
                            }
                        
                            return 0;
                        }
function get_weeks($start_date, $end_date) {
    $start_date_obj = new DateTime($start_date);
    $end_date_obj = new DateTime($end_date);

    $week_dates = array();
    $week_number = 1;

    while ($start_date_obj <= $end_date_obj) {
        $end_of_week = clone $start_date_obj;
        $end_of_week->modify('+7 days');

        

        $wk = 'Week ' . $week_number;

        $remaining_days = remaining_days($start_date_obj->format('Y-m-d'), $end_of_week->format('Y-m-d'));
        $week_dates[] = [
            'w' => $wk,
            'week' => $wk . ' [' . $start_date_obj->format('Y-m-d') . ' to ' . $end_of_week->format('Y-m-d') . ']',
            'start_date' => $start_date_obj->format('Y-m-d'),
            'end_date' => $end_of_week->format('Y-m-d'),
            'remaining_days' => $remaining_days
        ];        

        $start_date_obj->modify('+8 days');
        $week_number++;
    }

    return $week_dates;
}
                        // Process form submission
                        if (isset($_POST['view'])) {
                            $classId = $_POST['classId'];
                            $week = $_POST['date'];
                            list($start_date, $end_date) = explode(' AND ', $week);

                            $query = "SELECT * FROM tblstudents WHERE classId='$classId'";
                            $students = $conn->query($query);

                            if ($students->num_rows > 0) {
                                echo '
                                <div class="card mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <div class="export-buttons">
                                            <button class="btn btn-success btn-sm" onclick="exportToPDF()">Export to PDF</button>
                                            <button class="btn btn-info btn-sm" onclick="exportToCSV()">Export to CSV</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive p-3" id="reportContent">
                                                                            <h6 class="m-0 font-weight-bold text-dark">Weekly Attendance In Prep from '.$start_date.' to '.$end_date.'</h6>

                                        <table class="table table-hover" id="dataTableHover" border="1">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">Names</th>
                                                    <th rowspan="2">Session</th>
                                                    <th>Mon</th>
                                                    <th>Tue</th>
                                                    <th>Wed</th>
                                                    <th>Thu</th>
                                                    <th>Fri</th>
                                                    <th>Sat</th>
                                                    <th>Sun</th>
                                                    <th rowspan="2">Weekly Average (%)</th>
                                                </tr>
                                                <tr>
                                                    <th>Status</th><th>Status</th><th>Status</th><th>Status</th><th>Status</th><th>Status</th><th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                                $total_class_days = 0;
                                $total_class_presence = 0;

                                while ($student = $students->fetch_assoc()) {
                                    $student_present_days = 0;
                                    $total_days_checked = 7;

                                    echo '<tr><td rowspan="2">'.$student['name'].'</td><td>Morning</td>';

                                    for ($day = 1; $day <= 7; $day++) {
                                        $absentQuery = "SELECT * FROM absents WHERE regNumber = '{$student['admissionNumber']}' AND date BETWEEN '$start_date' AND '$end_date' AND day = $day AND mode = 'morning' AND school='$s'";
                                        $absentResult = $conn->query($absentQuery);
                                        $status = ($absentResult->num_rows > 0) ? '<img src="../img/close.png" width="30px">' : '<img src="../img/vii.png" width="30px">';
                                        echo "<td>$status</td>";
                                        if ($absentResult->num_rows == 0) {
                                            $student_present_days++;
                                            $total_class_presence++;
                                        }
                                    }

                                    echo '</tr><tr><td>Evening</td>';

                                    for ($day = 1; $day <= 7; $day++) {
                                        $absentQuery = "SELECT * FROM absents WHERE regNumber = '{$student['admissionNumber']}' AND date BETWEEN '$start_date' AND '$end_date' AND day = $day AND mode = 'evening' AND school='$s'";
                                        $absentResult = $conn->query($absentQuery);
                                        $status = ($absentResult->num_rows > 0) ? '<img src="../img/close.png" width="30px">' : '<img src="../img/vii.png" width="30px">';
                                        echo "<td>$status</td>";
                                        if ($absentResult->num_rows == 0) {
                                            $student_present_days++;
                                            $total_class_presence++;
                                        }
                                    }

                                    $student_weekly_average = ($student_present_days / (2 * $total_days_checked)) * 100;
                                    echo "<td>".round($student_weekly_average, 2)."%</td></tr>";
                                    $total_class_days += 2 * $total_days_checked;
                                }

                                $class_weekly_average = ($total_class_presence / $total_class_days) * 100;
                                echo '</tbody></table>
                                    <div class="card-footer">Class Weekly Average: '.round($class_weekly_average, 2).'%</div>
                                </div></div>';
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/ruang-admin.min.js"></script>
<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    $(document).ready(function () {
        $('#dataTableHover').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true
        });
    });

    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        const element = document.getElementById('reportContent');

        html2canvas(element, { scale: 2 }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const imgWidth = 190; // A4 width in mm minus margins
            const pageHeight = 295; // A4 height in mm
            const imgHeight = canvas.height * imgWidth / canvas.width;
            let heightLeft = imgHeight;
            let position = 10;

            doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            while (heightLeft >= 0) {
                position = heightLeft - imgHeight;
                doc.addPage();
                doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            doc.save('Weekly_Attendance_Report.pdf');
        });
    }

    function exportToCSV() {
        const table = document.getElementById('dataTableHover');
        let csv = [];
        const rows = table.querySelectorAll('tr');

        // Extract headers
        const headers = [];
        rows[0].querySelectorAll('th').forEach((th, index) => {
            if (index === 0 || index === 1 || index === 9) {
                headers.push(th.textContent.trim()); // Names, Session, Weekly Average
            }
        });
        headers.push('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        csv.push(headers.join(','));

        // Extract data rows
        for (let i = 2; i < rows.length; i += 2) { // Step by 2 for Morning/Evening pairs
            const morningRow = rows[i].querySelectorAll('td');
            const eveningRow = rows[i + 1].querySelectorAll('td');
            const name = morningRow[0].textContent.trim();
            const weeklyAvg = morningRow[morningRow.length - 1].textContent.trim();

            let morningData = [name, 'Morning'];
            let eveningData = ['', 'Evening'];
            for (let j = 1; j < 8; j++) { // Days Mon-Sun
                const morningStatus = morningRow[j].querySelector('img') ? (morningRow[j].querySelector('img').src.includes('close') ? 'Absent' : 'Present') : '';
                const eveningStatus = eveningRow[j - 1].querySelector('img') ? (eveningRow[j - 1].querySelector('img').src.includes('close') ? 'Absent' : 'Present') : '';
                morningData.push(morningStatus);
                eveningData.push(eveningStatus);
            }
            morningData.push(weeklyAvg);
            eveningData.push('');
            csv.push(morningData.join(','));
            csv.push(eveningData.join(','));
        }

        // Add Class Weekly Average
        const footer = document.querySelector('.card-footer').textContent.trim();
        csv.push(['', '', '', '', '', '', '', '', footer]);

        // Download CSV
        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'Weekly_Attendance_Report.csv';
        link.click();
    }
</script>
</body>
</html>