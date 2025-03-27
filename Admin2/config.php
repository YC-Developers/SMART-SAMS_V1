<?php
$conn = new mysqli("127.0.0.1", "root", "", "attendancemsystem");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Time slots available
$timeSlots = [
    '08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00',
    '13:00-14:00', '14:00-15:00', '15:00-16:00'
];

// Days of the week
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
?>