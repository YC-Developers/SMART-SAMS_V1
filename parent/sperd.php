<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session2.php';

function get_weeks($start_date, $end_date)
{
    $start_date_obj = new DateTime($start_date);
    $end_date_obj = new DateTime($end_date);

    $week_dates = array();
    $week_number = 1;

    while ($start_date_obj <= $end_date_obj) {
        $end_of_week = clone $start_date_obj;
        $end_of_week->modify('+7 days');

        $wk = 'Week ' . $week_number;
        $week_dates[] = [
            'w' => $wk,
            'week' => $wk . ' [' . $start_date_obj->format('Y-m-d') . ' to ' . $end_of_week->format('Y-m-d') . ']',
            'start_date' => $start_date_obj->format('Y-m-d'),
            'end_date' => $end_of_week->format('Y-m-d')
        ];

        $start_date_obj->modify('+8 days');
        $week_number++;
    }

    return $week_dates;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Daily Attendance Statistics</title>
    <link href="img/logo/attnlg.jpg" rel="icon">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css1/scrollbar.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "Includes/sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "Includes/topbar.php"; ?>

                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Daily Attendance Statistics</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Daily Attendance</li>
                        </ol>
                    </div>

                    <?php
                    $reg = $conn->real_escape_string($_SESSION["regNumber"]);
                    $query = "SELECT * FROM tblstudents WHERE admissionNumber = '$reg'";
                    $res = $conn->query($query);
                    while ($row = $res->fetch_assoc()) {
                        echo '
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daily Attendance for ' . $row['name'] . '</h6>
                </div>
                <div class="table-responsive p-3">
                    <table class="table align-items-center table-flush table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Today</th>
                                <th>Session</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>';
                    }
                    ?>

                    <?php

                    if ($error == $rows['cbs e']) {
                        $error = "error";
                    }






                    date_default_timezone_set('Africa/Kigali'); // Set the timezone
                    $now = new DateTime();
                    $time = $now->format('H:i a');
                    $date = DATE("Y-m-d"); // current date in 'YYYY-MM-DD' format
                    $day_of_week = date('N', strtotime($date));

                    $total_sessions = 0;
                    $present_sessions = 0;

                    // Check morning session attendance
                    $morning_query = "SELECT * FROM absents WHERE regNumber = '$reg' AND day = $day_of_week AND mode = 'morning' AND date ='$date'";
                    $morning_result = $conn->query($morning_query);
                    $morning_status = ($morning_result->num_rows > 0) ? '<img src="../img/close.png" width="30px">' : '<img src="../img/vii.png" width="30px">';
                    
                    if ($time >= '06:00 am' && $time <= '23:00 pm') {
                        echo '
    <tr>
        <th>' . $day_of_week . '</th>
        <th>Morning</th>
        <th>' . $date . '</th>
        <th>' . $morning_status . '</th>
    </tr>';

                        $total_sessions++;
                        if ($morning_result->num_rows == 0) {
                            $present_sessions++;
                        }
                    }

                    // Check evening session attendance
                    $evening_query = "SELECT * FROM absents WHERE regNumber = '$reg' AND day = $day_of_week AND mode = 'evening' AND date ='$date'";
                    $evening_result = $conn->query($evening_query);
                    $evening_status = ($evening_result->num_rows > 0) ? '<img src="../img/close.png" width="30px">' : '<img src="../img/vii.png" width="30px">';
                    if ($time >= '18:00 pm' && $time <= '23:00 pm') {
                        echo '
    <tr>
        <th>' . $day_of_week . '</th>
        <th>Evening</th>
        <th>' . $date . '</th>
        <th>' . $evening_status . '</th>
    </tr>';

                        $total_sessions++;
                        if ($evening_result->num_rows == 0) {
                            $present_sessions++;
                        }
                    }

                    // Calculate daily attendance average
                    if ($total_sessions > 0) {
                        $daily_average = ($present_sessions / $total_sessions) * 100;
                        echo '
    </tbody>
    </table>
    <div class="card-footer">
        Daily Attendance Average: ' . round($daily_average, 2) . '% 
    </div>';
                    } else {
                        echo '<div class="alert alert-info">Waiting Time for recording  Attendance.....</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    </div>

    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTableHover').DataTable();
        });
    </script>
</body>

</html>