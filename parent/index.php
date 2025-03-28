<?php
include '../Includes/dbcon.php';
include '../Includes/session2.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ETS-AMS | Parent Dashboard</title>
    <link href="img/logo/attnlg.jpg" rel="icon">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css1/scrollbar.css">
    <style>
    html, body { height: 100%; overflow: hidden; }
    #wrapper { height: 100vh; overflow: hidden; }
    #content-wrapper { flex: 1; overflow-y: auto; -webkit-overflow-scrolling: touch; }
    #content { flex: 1; overflow: visible; padding-bottom: 60px; }
    .dashboard-card { border: none; border-radius: 15px; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); }
    .dashboard-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2); }
    .stat-card { background: linear-gradient(45deg, #231a6f, #2c82c9); color: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
    .welcome-section { background: linear-gradient(135deg, #231a6f, #0f054c); color: white; padding: 30px; border-radius: 15px; margin-bottom: 30px; }
    .student-profile { background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
    .attendance-chart { background: white; border-radius: 12px; padding: 20px; height: 300px; margin-bottom: 20px; }
    .notification { position: fixed; top: 20px; right: 20px; z-index: 1000; padding: 15px; border-radius: 5px; color: white; display: none; }
    .notification.success { background-color: #28a745; }
    .notification.warning { background-color: #ffc107; }
    .notification.danger { background-color: #dc3545; }
    .status-text { font-size: 1.2rem; margin: 5px 0; }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "Includes/sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "Includes/topbar.php"; ?>

                <div class="container-fluid">
                    <div class="welcome-section">
                        <h2>Welcome Back, Parent of <?php echo $fullName; ?>!</h2>
                        <p>Monitor your child's attendance and academic progress in real-time</p>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card">
                                <h4>Today's Status</h4>
                                <div id="todayStatus">
                                    <p class="status-text">Morning: <span id="morningStatus">Loading...</span></p>
<p class="status-text">Evening: <span id="eveningStatus">Loading...</span></p>

                                </div>
                                <p><?php echo date('F j, Y'); ?></p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card">
                                <h4>Monthly Attendance Rate</h4>
                                <h2 id="monthlyRate">Loading...</h2>
                                <p>Current Month (<?php echo date('F Y'); ?>)</p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card">
                                <h4>Total Absences</h4>
                                <h2 id="totalAbsences">Loading...</h2>
                                <p id="absenceBreakdown">Sick: 0 | Unjustified: 0</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="dashboard-card student-profile">
                                <h4 class="text-center mb-4">Student Information</h4>
                                <?php
                                $studentId = $_SESSION['regNumber'];
                                $query = "SELECT s.*, c.className FROM tblstudents s 
                                         JOIN tblclass c ON s.classId = c.Id 
                                         WHERE s.admissionNumber = '$studentId'";
                                $result = mysqli_query($conn, $query);

                                if ($row = mysqli_fetch_array($result)) {
                                    $admissionNumber = $row['admissionNumber'];
                                    $z = "SELECT classId FROM tblstudents WHERE admissionNumber = '$admissionNumber'";
                                    $R = $conn->query($z);
                                    $cx = $R->fetch_assoc();
                                    $cid = $cx["classId"];

                                    $z2 = "SELECT * FROM tblclass WHERE Id = '$cid'";
                                    $R2 = $conn->query($z2);
                                    $cx2 = $R2->fetch_assoc();
                                    $sch = $cx2["school"];
                                    ?>
                                    <div class="text-center mb-4">
                                        <img src="../img/user-profile.png" class="rounded-circle" width="100">
                                        <h5 class="mt-3 text-dark"><?php echo $row['name']; ?></h5>
                                        <div class="student-details mt-3 text-left">
                                            <p><strong>School:</strong> <?php echo $sch; ?></p>
                                            <p><strong>Class:</strong> <?php echo $row['className']; ?></p>
                                            <p><strong>Admission No:</strong> <?php echo $admissionNumber; ?></p>
                                            <p><strong>Gender:</strong> <?php echo $row['gender']; ?></p>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="alert alert-info">No student information found.</div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="dashboard-card attendance-chart">
                                <h4 class="text-center mb-4">Monthly Attendance Analysis (Prep Attendance)</h4>
                                <div class="chart-container" style="position: relative; height:220px;">
                                    <canvas id="attendanceChart"></canvas>
                                </div>
                            </div>
                            <div class="dashboard-card attendance-chart">
                                <h4 class="text-center mb-4">Daily Attendance Analysis (All Lessons Attendance - Last 30 Days)</h4>
                                <div class="chart-container" style="position: relative; height:220px;">
                                    <canvas id="dailyAttendanceChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Area -->
                <div id="notification" class="notification"></div>
            </div>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

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
        // Monthly Chart (Absents Table)
        const ctx1 = document.getElementById('attendanceChart').getContext('2d');
        let monthlyChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    { label: 'Attendance Rate (%)', data: [], backgroundColor: 'rgba(46, 204, 113, 0.7)', borderColor: 'rgba(46, 204, 113, 1)', borderWidth: 1, type: 'line', fill: false },
                    { label: 'Sick Absences (%)', data: [], backgroundColor: 'rgba(241, 196, 15, 0.7)', borderColor: 'rgba(241, 196, 15, 1)', borderWidth: 1 },
                    { label: 'Unjustified Absences (%)', data: [], backgroundColor: 'rgba(231, 76, 60, 0.7)', borderColor: 'rgba(231, 76, 60, 1)', borderWidth: 1 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, max: 100, title: { display: true, text: 'Percentage (%)' }, ticks: { callback: value => value + '%' } }, x: { title: { display: true, text: 'Month' } } },
                plugins: { legend: { position: 'top' }, tooltip: { mode: 'index', intersect: false, callbacks: { label: context => `${context.dataset.label}: ${context.raw}%` } } }
            }
        });

        // Daily Chart (tblattendance)
        const ctx2 = document.getElementById('dailyAttendanceChart').getContext('2d');
        let dailyChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    { label: 'Present', data: [], backgroundColor: 'rgba(46, 204, 113, 0.7)', borderColor: 'rgba(46, 204, 113, 1)', borderWidth: 1 },
                    { label: 'Absent', data: [], backgroundColor: 'rgba(231, 76, 60, 0.7)', borderColor: 'rgba(231, 76, 60, 1)', borderWidth: 1 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, title: { display: true, text: 'Number of Sessions' } }, x: { title: { display: true, text: 'Date' } } },
                plugins: { legend: { position: 'top' }, tooltip: { mode: 'index', intersect: false } }
            }
        });

        // Notification function
        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = `notification ${type}`;
            notification.style.display = 'block';
            setTimeout(() => { notification.style.display = 'none'; }, 5000);
        }

        // Real-time update function
        function updateDashboard() {
            $.ajax({
                url: 'fetch_attendance_data.php',
                method: 'POST',
                data: { regNumber: '<?php echo $studentId; ?>' },
                dataType: 'json',
                success: function(data) {
                    // Update Today's Status
                    document.getElementById('morningStatus').textContent = data.morningPresent ? 'Present' : 'Absent';
                    document.getElementById('eveningStatus').textContent = data.eveningPresent ? 'Present' : 'Absent';
                    if (data.morningPresent && data.eveningPresent) {
                        showNotification('Your child is present for both morning and evening today!', 'success');
                    } else if (!data.morningPresent && !data.eveningPresent) {
                        showNotification('Your child is absent for both morning and evening today!', 'danger');
                    } else {
                        if (!data.morningPresent) showNotification('Your child is absent this morning!', 'danger');
                        if (!data.eveningPresent) showNotification('Your child is absent this evening!', 'danger');
                    }

                    // Update Monthly Stats (Absents Table)
                    document.getElementById('monthlyRate').textContent = `${data.monthlyRate}%`;
                    document.getElementById('totalAbsences').textContent = data.totalAbsences;
                    document.getElementById('absenceBreakdown').textContent = `Sick: ${data.sickDays} | Unjustified: ${data.unjustifiedAbsences}`;
                    if (data.monthlyRate < 70) {
                        showNotification('Warning: Attendance rate below 70% this month!', 'warning');
                    }

                    // Update Monthly Chart (Absents Table)
                    monthlyChart.data.labels = data.chart.labels;
                    monthlyChart.data.datasets[0].data = data.chart.attendanceRates;
                    monthlyChart.data.datasets[1].data = data.chart.sickRates;
                    monthlyChart.data.datasets[2].data = data.chart.unjustifiedRates;
                    monthlyChart.update();

                    // Update Daily Chart (tblattendance)
                    dailyChart.data.labels = data.dailyChart.labels;
                    dailyChart.data.datasets[0].data = data.dailyChart.presentData;
                    dailyChart.data.datasets[1].data = data.dailyChart.absentData;
                    dailyChart.update();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching real-time data:', error);
                    showNotification('Error fetching attendance data.', 'danger');
                }
            });
        }

        // Update every 10 seconds
        setInterval(updateDashboard, 10000);
        updateDashboard(); // Initial call
    });
    </script>
</body>
</html>