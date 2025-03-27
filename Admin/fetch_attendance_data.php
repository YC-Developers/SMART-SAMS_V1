<?php
include '../Includes/dbcon.php';

if (isset($_POST['school']) && isset($_POST['totalStudents'])) {
    $school = $_POST['school'];
    $totalStudents = (int)$_POST['totalStudents'];

    // Query to fetch daily absence data from absents table for the last 30 days
    $query = "
        SELECT 
            DATE(a.date) as date,
            SUM(CASE WHEN a.mode = 'morning' THEN 1 ELSE 0 END) as morning_absent,
            SUM(CASE WHEN a.mode = 'evening' THEN 1 ELSE 0 END) as evening_absent,
            COUNT(DISTINCT a.regNumber) as total_absent
        FROM absents a
        INNER JOIN tblstudents s ON s.admissionNumber = a.regNumber
        INNER JOIN tblclass c ON c.Id = s.classId
        WHERE c.school = ?
        AND a.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY DATE(a.date)
        ORDER BY a.date ASC
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $school);
    $stmt->execute();
    $result = $stmt->get_result();

    $labels = [];
    $presentData = [];
    $morningAbsentData = [];
    $eveningAbsentData = [];

    // Create a map of dates to absence counts
    $absenceMap = [];
    while ($row = $result->fetch_assoc()) {
        $date = $row['date'];
        $absenceMap[$date] = [
            'total_absent' => $row['total_absent'],
            'morning_absent' => $row['morning_absent'],
            'evening_absent' => $row['evening_absent']
        ];
    }

    // Generate data for the last 30 days
    for ($i = 29; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $formattedDate = date('M d', strtotime($date));
        $labels[] = $formattedDate;

        if (isset($absenceMap[$date])) {
            $totalAbsent = $absenceMap[$date]['total_absent'];
            $morningAbsent = $absenceMap[$date]['morning_absent'];
            $eveningAbsent = $absenceMap[$date]['evening_absent'];
            $presentData[] = $totalStudents - $totalAbsent; // Present = Total - Absent
            $morningAbsentData[] = $morningAbsent;
            $eveningAbsentData[] = $eveningAbsent;
        } else {
            $presentData[] = $totalStudents; // No absences = all present
            $morningAbsentData[] = 0;
            $eveningAbsentData[] = 0;
        }
    }

    $response = [
        'labels' => $labels,
        'presentData' => $presentData,
        'morningAbsentData' => $morningAbsentData,
        'eveningAbsentData' => $eveningAbsentData
    ];

    echo json_encode($response);
}
?>