<?php
include '../includes/dbcon.php';
include '../Includes/session.php'; 

if (isset($_POST['save'])) {
    $absentees = $_POST['check']; // Selected absent student IDs
    $reasons = $_POST['reason']; // Reasons for absence
    $mode = $_POST['mode'];
    $dateTaken = date("Y-m-d");
    $day = date('N', strtotime($dateTaken));

    for ($i = 0; $i < count($absentees); $i++) {
        $studentId = $absentees[$i];
        $reason = mysqli_real_escape_string($conn, $reasons[$i]);

        // Fetch student details
        $query = "SELECT tblstudents.Id,tblclass.className,tblclassarms.classArmName,tblclassarms.Id AS classArmId,tblstudents.name,tblstudents.gender,
            tblstudents.admissionNumber,tblstudents.dateCreated
            FROM tblstudents
            JOIN tblclass ON tblclass.Id = tblstudents.classId
            INNER JOIN tblclassarms ON tblclassarms.Id = tblstudents.classArmId
            where tblstudents.Id = '$studentId'";
        $result = $conn->query($query);
        $student = $result->fetch_assoc();
        $name = $student['name'];
        $gender = $student['gender'];
        $className = $student['className'];
        $reg = $student['admissionNumber'];
//session check
        $query = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
        $rs = $conn->query($query);
        $num = $rs->num_rows;
        $rows = $rs->fetch_assoc();
        $s = $rows['school'];
      
        // Insert absence record
        $sql = "INSERT INTO absents (name, gender, className, regNumber, mode, day, reason,school, date) 
                VALUES ('$name', '$gender', '$className', '$reg', '$mode', '$day', '$reason','$s', '$dateTaken')";
        mysqli_query($conn, $sql);
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

</head>
<body><center><br><br><br><br><br><br><br>
<img src="../img/saved.webp" width="100px">
<h2>Successfully Data Is Saved</h2>
<a href='takeAttendance.php' class='btn btn-primary'>Back To Take Attendance Of Another Class</a>
</body>
</html>

