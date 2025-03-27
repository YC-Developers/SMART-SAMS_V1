<?php
include '../Includes/dbcon.php';

if (isset($_POST['regNumber'])) {
    $studentId = $_POST['regNumber'];
    $currentDate = date('Y-m-d');
    $currentMonth = date('m');
    $currentYear = date('Y');

    // Check morning presence (absents table)
    $morningQuery = "SELECT COUNT(*) as morningAbsent FROM absents WHERE regNumber = ? AND date = ? AND mode = 'morning'";
    $stmt = $conn->prepare($morningQuery);
    $stmt->bind_param("ss", $studentId, $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $morningPresent = ($row['morningAbsent'] == 0);

    // Check evening presence (absents table)
    $eveningQuery = "SELECT COUNT(*) as eveningAbsent FROM absents WHERE regNumber = ? AND date = ? AND mode = 'evening'";
    $stmt = $conn->prepare($eveningQuery);
    $stmt->bind_param("ss", $studentId, $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $eveningPresent = ($row['eveningAbsent'] == 0);

    // Monthly attendance analysis (absents table)
    $monthQuery = "
        SELECT 
            (SELECT COUNT(DISTINCT CONCAT(date, mode)) 
             FROM atdetails 
             WHERE className = (
                 SELECT c.className 
                 FROM tblstudents s
                 JOIN tblclass c ON s.classId = c.Id 
                 WHERE s.admissionNumber = ?
             )
             AND MONTH(date) = ? AND YEAR(date) = ?) as total_sessions,
            COUNT(*) as absences,
            SUM(CASE WHEN reason = 'Sick' THEN 1 ELSE 0 END) as sick_days,
            SUM(CASE WHEN reason = 'Absent' THEN 1 ELSE 0 END) as unjustified_absences
        FROM absents 
        WHERE regNumber = ? 
        AND MONTH(date) = ? AND YEAR(date) = ?
    ";
    $stmt = $conn->prepare($monthQuery);
    $stmt->bind_param("ssssss", $studentId, $currentMonth, $currentYear, $studentId, $currentMonth, $currentYear);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $totalSessions = $row['total_sessions'] ?: 1;
    $absences = $row['absences'];
    $sickDays = $row['sick_days'];
    $unjustifiedAbsences = $row['unjustified_absences'];
    $monthlyRate = round((($totalSessions - $absences) / $totalSessions) * 100, 1);

    // Monthly chart data (absents table - last 6 months)
    $chartQuery = "
        SELECT 
            DATE_FORMAT(date, '%Y-%m') as month,
            (SELECT COUNT(DISTINCT CONCAT(date, mode)) 
             FROM atdetails 
             WHERE className = (
                 SELECT c.className 
                 FROM tblstudents s
                 JOIN tblclass c ON s.classId = c.Id 
                 WHERE s.admissionNumber = ?
             )
             AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(a.date, '%Y-%m')) as total_sessions,
            COUNT(*) as absences,
            SUM(CASE WHEN reason = 'Sick' THEN 1 ELSE 0 END) as sick_days,
            SUM(CASE WHEN reason = 'Absent' THEN 1 ELSE 0 END) as unjustified_absences
        FROM absents a
        WHERE regNumber = ?
        AND date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(date, '%Y-%m')
        ORDER BY month ASC
    ";
    $stmt = $conn->prepare($chartQuery);
    $stmt->bind_param("ss", $studentId, $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    $labels = [];
    $attendanceRates = [];
    $sickRates = [];
    $unjustifiedRates = [];

    while ($row = $result->fetch_assoc()) {
        $totalSessions = $row['total_sessions'] ?: 1;
        $absences = $row['absences'];
        $sickDays = $row['sick_days'];
        $unjustifiedAbsences = $row['unjustified_absences'];

        $labels[] = date('M Y', strtotime($row['month'] . '-01'));
        $attendanceRates[] = round((($totalSessions - $absences) / $totalSessions) * 100, 1);
        $sickRates[] = round(($sickDays / $totalSessions) * 100, 1);
        $unjustifiedRates[] = round(($unjustifiedAbsences / $totalSessions) * 100, 1);
    }

    // Daily attendance analysis (tblattendance - last 30 days)
    $dailyQuery = "
        SELECT 
            DATE(attendanceDate) as date,
            SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present_count,
            SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent_count
        FROM tblattendance
        WHERE studentId IN (
            SELECT Id FROM tblstudents WHERE admissionNumber = ?
        )
        AND attendanceDate >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY DATE(attendanceDate)
        ORDER BY attendanceDate ASC
    ";
    $stmt = $conn->prepare($dailyQuery);
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    $dailyLabels = [];
    $presentData = [];
    $absentData = [];

    while ($row = $result->fetch_assoc()) {
        $dailyLabels[] = date('M d', strtotime($row['date']));
        $presentData[] = $row['present_count'];
        $absentData[] = $row['absent_count'];
    }

    $response = [
        'morningPresent' => $morningPresent,
        'eveningPresent' => $eveningPresent,
        'monthlyRate' => $monthlyRate,
        'totalAbsences' => $absences,
        'sickDays' => $sickDays,
        'unjustifiedAbsences' => $unjustifiedAbsences,
        'chart' => [
            'labels' => $labels,
            'attendanceRates' => $attendanceRates,
            'sickRates' => $sickRates,
            'unjustifiedRates' => $unjustifiedRates
        ],
        'dailyChart' => [
            'labels' => $dailyLabels,
            'presentData' => $presentData,
            'absentData' => $absentData
        ]
    ];

    echo json_encode($response);
}
?>