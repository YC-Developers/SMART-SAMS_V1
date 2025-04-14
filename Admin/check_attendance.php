<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

header('Content-Type: application/json');

// Function to check if current time is morning (before 12 PM)
function isMorning() {
    return date('H') < 12;
}

$className = $_POST['className'];
$isMorning = $_POST['isMorning'] === 'true';
$currentDate = date('Y-m-d');
$mode = $isMorning ? 'morning' : 'evening';

// Extract school code and class name from the QR code data
$schoolCode = substr($className, 0, 5); // Assuming school code is 5 digits
$actualClassName = substr($className, 5);

// Check if attendance already exists for this class today in this session
$query = "SELECT * FROM atdetails 
          WHERE className = '$actualClassName' 
          AND date = '$currentDate' 
          AND mode = '$mode' 
          AND school = '$s'";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Attendance already taken
    echo json_encode([
        'allowed' => false,
        'message' => 'Attendance for this class has already been taken today in the ' . ($isMorning ? 'morning' : 'evening') . ' session.'
    ]);
} else {
    // Attendance can be taken
    echo json_encode([
        'allowed' => true,
        'message' => 'You can now take attendance for this class.'
    ]);
}
?>