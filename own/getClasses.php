<?php
include '../Includes/dbcon.php';
include '../Includes/session3.php';

if (isset($_POST['schoolId'])) {
    $schoolId = $_POST['schoolId'];

    // Get school name
    $schoolQuery = "SELECT schoolName FROM schools WHERE id = '$schoolId'";
    $schoolResult = $conn->query($schoolQuery);

    if ($schoolResult && $schoolResult->num_rows > 0) {
        $school = $schoolResult->fetch_assoc();
        $schoolName = $school['schoolName'];

        // Get classes for the selected school
        $classesQuery = "SELECT * FROM tblclass WHERE school = '$schoolName'";
        $classesResult = $conn->query($classesQuery);

        $options = '<option value="">--Select Class--</option>';
        if ($classesResult && $classesResult->num_rows > 0) {
            while ($class = $classesResult->fetch_assoc()) {
                $options .= '<option value="' . $class['Id'] . '">' . $class['className'] . '</option>';
            }
        } else {
            $options .= '<option value="" disabled>No classes found for this school</option>';
        }

        echo $options;
    } else {
        echo '<option value="" disabled>School not found</option>';
    }
}
?>
