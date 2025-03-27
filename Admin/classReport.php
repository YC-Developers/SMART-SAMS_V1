<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

?>
<?php
  $query = "SELECT * FROM tbladmin WHERE Id = " . $_SESSION['userId'] . "";
  $rs = $conn->query($query);
  $num = $rs->num_rows;
  $rows = $rs->fetch_assoc();
  $school = $rows['school'];

  $a = "SELECT * FROM schools WHERE schoolName = '$school'";
  $r = $conn->query($a);
  $r2 = $r->fetch_assoc();
  $code = $r2['code'];
  $d = $r2['district'];
  $p = $r2['province'];

?>

        <table border="1">
        <thead>
        <h4>Province:<?php echo $p?></h4>
            <h4>District:<?php echo $d?></h4>
            <h4>School Name:<?php echo $school?></h4>
            <h4>School Code:<?php echo $code?></h4>
        <center>
            <h3>All classes Attendance list On <?php echo DATE("d/m/Y");
?></h3></center>

            <tr>
            <th>No</th>
            <th>Class Name</th>
            <th>Etude/Prep</th>
            <th>Present Boys</th>
            <th>Absent Boys</th>
            <th>Present Girls</th>
            <th>Absent Girls</th>
            <th>Term</th>
            <th>Session</th>
            <th>Date</th>
            </tr>
        </thead>

<?php

$query = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
$rs = $conn->query($query);
$num = $rs->num_rows;
$rows = $rs->fetch_assoc();
$s = $rows['school'];

$dateTaken = DATE("Y-m-d");
$filename="All classes Attendance list On $dateTaken";
$cnt=1;
$ar = mysqli_query($conn,"SELECT * FROM tblsessionterm WHERE school='$s'
");
while ($row=mysqli_fetch_array($ar)) 
{ 
    $session = $row["sessionName"];
}

$aa = mysqli_query($conn,"SELECT * FROM tblterm
");
while ($row=mysqli_fetch_array($aa)) 
{ 
    $term = $row["termName"];
}

$query = "SELECT * FROM atdetails WHERE date='$dateTaken' AND school='$s'";
$rs = $conn->query($query);
$num = $rs->num_rows;
$sn=0;
$status="";
if($num > 0)
{ 
  while ($row = $rs->fetch_assoc())
    {
       $sn = $sn + 1;
    

echo '  
<tr>  
<td>'.$sn.'</td> 
<td>'.$row['className'].'</td> 
<td>'.$row['mode'].'</td> 
<td>'.$row['presentboys'].'</td> 
<td>'.$row['absentboys'].'</td> 
<td>'.$row['presentgirls'].'</td>
<td>'.$row['absentgirls'].'</td>	 	
<td>'.$term.'</td>
<td>'.$session.'</td>	 
<td>'.$row['date'].'</td>	 					
</tr>  
';
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$filename."-report.xlsx");
header("Pragma: no-cache");
header("Expires: 0");
			}
	}
?>
</table>