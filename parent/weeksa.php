<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session2.php';
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
    <link rel="stylesheet" href="../css1/scrollbar.css">
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
                                <form method="post">
                                    <div class="form-group row mb-3">
                                

                                    <div class="col-xl-6">
    <label>Select Week<span class="text-danger">*</span></label>
    <select required name="date" class="form-control mb-3">
        <option value="">---Select Week---</option>
        <?php

$z = "SELECT classId FROM tblstudents WHERE admissionNumber = '$regNumber'";
$R = $conn->query($z);
$cx = $R->fetch_assoc();
$cid = $cx["classId"];

$z2 = "SELECT * FROM `tblclass` WHERE Id = '$cid'";
$R2 = $conn->query($z2);
$cx2 = $R2->fetch_assoc();
$sch = $cx2["school"];


    ?>

        <?php
        // Fetch session/term data from the database
        $q = "SELECT * FROM tblsessionterm WHERE school='$sch'";
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
                        <div id="print">

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
    $week = $_POST['date'];
    list($start_date, $end_date) = explode(' AND ', $week);
    $reg = $conn->real_escape_string($_SESSION["regNumber"]);
    $query = "SELECT * FROM tblstudents WHERE admissionNumber = '$reg'";
    $students = $conn->query($query);
    $studentId = $_SESSION['regNumber'];

    $z = "SELECT classId FROM tblstudents WHERE admissionNumber = '$studentId'";
    $R = $conn->query($z);
    $cx = $R->fetch_assoc();
    $cid = $cx["classId"];

    $z2 = "SELECT * FROM tblclass WHERE Id = '$cid'";
    $R2 = $conn->query($z2);
    $cx2 = $R2->fetch_assoc();
    $sch = $cx2["school"];

    $z3 = "SELECT * FROM schools WHERE schoolName = '$sch'";
    $R3 = $conn->query($z3);
    $cx3 = $R3->fetch_assoc();
    $scd = $cx3["district"];
    $scc = $cx3["code"];

    if ($students->num_rows > 0) {
        echo '
        <div class="card mb-4">
                    <h3>District: '.$scd.'</h3>
                        <h3>School Name: '.$sch.'</h3>
            <h3>School Code: '.$scc.'</h3>

            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                <h6 class="m-0 font-weight-bold text-dark">Weekly Attendance In Prep from '.$start_date.' to '.$end_date.'</h6>
            </div>
            <div class="table-responsive p-3">
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
                            <th rowspan="2">Attend Weekly Average (%)</th>
                        </tr>
                        <tr>
                            <th>Status</th><th>Status</th><th>Status</th><th>Status</th><th>Status</th><th>Status</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';

        // Variables to calculate class-wide averages
        $total_class_days = 0;
        $total_class_presence = 0;

        while ($student = $students->fetch_assoc()) {
            $student_present_days = 0;
            $total_days_checked = 7; // Assuming a 7-day week

            echo '<tr><td rowspan="2">'.$student['name'].'</td><td>Morning</td>';

            for ($day = 1; $day <= 7; $day++) {
                $absentQuery = "SELECT * FROM absents WHERE regNumber = '{$student['admissionNumber']}' AND date BETWEEN '$start_date' AND '$end_date' AND day = $day AND mode = 'morning'";
                $absentResult = $conn->query($absentQuery);
                $status = ($absentResult->num_rows > 0) ? '<img src="../img/close.png" width="30px">' : '<img src="../img/vii.png" width="30px">';
                echo "<td>$status</td>";

                // If student is present, increment their present days counter
                if ($absentResult->num_rows == 0) {
                    $student_present_days++;
                    $total_class_presence++;
                }
            }



            echo '</tr><tr><td>Evening</td>';

            for ($day = 1; $day <= 7; $day++) {
                $absentQuery = "SELECT * FROM absents WHERE regNumber = '{$student['admissionNumber']}' AND date BETWEEN '$start_date' AND '$end_date' AND day = $day AND mode = 'evening'";
                $absentResult = $conn->query($absentQuery);
                $status = ($absentResult->num_rows > 0) ? '<img src="../img/close.png" width="30px">' : '<img src="../img/vii.png" width="30px">';
                echo "<td>$status</td>";

                // If student is present, increment their present days counter
                if ($absentResult->num_rows == 0) {
                    $student_present_days++;
                    $total_class_presence++;
                }
            }

            // Calculate and display individual weekly attendance average
            $student_weekly_average = ($student_present_days / (2 * $total_days_checked)) * 100;
            echo "<td>".round($student_weekly_average, 2)."%</td></tr>";
            $total_class_days += 2 * $total_days_checked; // Increment class total days for each student (morning + evening)
        }

        // Calculate and display the class average
        $class_weekly_average = ($total_class_presence / $total_class_days) * 100;
        echo '</tbody></table>
            <div class="card-footer">Attendance Weekly Average: '.round($class_weekly_average, 2).'%</div>
        </div></div>';
    } else {
        echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
    }
}
?>
<h6>Generated By SMART-SAMS (SMART-School Attendance Management System) On <?php echo DATE("D") . ",". DATE("d-m-Y");?></h6>
                    </div>
                    <button onclick="printDiv('print')" class='btn btn asa' id="b">Print</button>
                    <button class="btn btn asa " onclick="exportToPDF()" id="b">Export to PDF</button>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
      function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        const element = document.getElementById('print');

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

            doc.save('<?php echo 'Weekly Attendance In Prep from '.$start_date.' to '.$end_date?>.pdf');
        });
    }


</script>


                </div>

            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>
                        
    <script>
      function printDiv(divName) {
  // Select the element to print
  var printContents = document.getElementById(divName).innerHTML;
  
  // Create a new window for printing
  var popupWin = window.open('width=1000,height=1000');

  // Set the new window's content to the selected element
  popupWin.document.open();
  popupWin.document.write('<html><head><link rel="stylesheet" type="text/css" href="styles.css" /></head><body onload="window.print()">' + printContents + '<br><br>' + '</body></html>');
  popupWin.document.close();

  // Reset the CSS style to its original state
  setTimeout(function() {
    popupWin.close();
  }, 1000);
}



    </script>



<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/ruang-admin.min.js"></script>
<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#dataTableHover').DataTable();
    });
</script>
</body>
</html>
