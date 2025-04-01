<?php  

if (isset($_POST['submit'])) {
    $class = $_POST['class'];

    if (isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != "") {
        $allowedExtensions = array("xls", "xlsx");
        $ext = pathinfo($_FILES['uploadFile']['name'], PATHINFO_EXTENSION);

        if (in_array($ext, $allowedExtensions)) {
            // Uploaded file
            $file = "uploads/" . $_FILES['uploadFile']['name'];
            $isUploaded = copy($_FILES['uploadFile']['tmp_name'], $file);

            if ($isUploaded) {
                // Include PHPExcel files and database configuration file
                include("../Includes/dbcon.php");
                require_once __DIR__ . '/vendor/autoload.php';
                include(__DIR__ . '/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php');

                try {
                    // Load uploaded file
                    $objPHPExcel = PHPExcel_IOFactory::load($file);
                } catch (Exception $e) {
                    die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                }

                // Specify the excel sheet index
                $sheet = $objPHPExcel->getSheet(0);
                $total_rows = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

                // Loop over the rows
                for ($row = 1; $row <= $total_rows; ++$row) {
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $sheet->getCellByColumnAndRow($col, $row);
                        $val = $cell->getValue();
                        $records[$row][$col] = $val;
                    }
                }

                $html = "<table border='1'>";
                $html .= "<tr><th>#</th><th>Name</th><th>Gender</th><th>RegNumber</th><th>Class ID</th><th>ClassAmId</th></tr>";

                $regCounter = 1; // Initialize the RegNumber counter

                foreach ($records as $row) {
                    if ($row[0] == '' && $row[1] == '' && $row[2] == '') {
                        break;
                    }

                    // Extract data from the row
                    $id = isset($row[0]) ? $row[0] : '';
                    $name = isset($row[1]) ? $row[1] : '';
                    $gender = isset($row[2]) ? $row[2] : '';

  
  //school code
  $qm = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
  $rs = $conn->query($qm);
  $num = $rs->num_rows;
  $rows = $rs->fetch_assoc();
  $school = $rows['school']; 


  $a ="SELECT code FROM schools WHERE schoolName='$school'";
  $ree = $conn->query($a);
  $qs = $ree->fetch_assoc();
  
                      // Fetch class arms
                      $a = "SELECT * FROM tblclass WHERE Id='$classId'";
                      $re = $conn->query($a);
                      $ro = $re->fetch_assoc();

                      //YEAR
                      $year = date("Y");

                      $p = "SELECT * FROM tblstudents WHERE classId='$classId'";
                      $s = $conn->query($p);
                      $n = $s->num_rows;
                      $regCounter = $n;
                      $regCounter++; // Increment the counter


                      // Generate registration number
                      $admissionNumber = $qs['code'].$ro['level'] . sprintf('%03d', $regCounter) . $year;

                    // Build the table row
                    $html .= "<tr>";
                    $html .= "<td>" . $id . "</td>";
                    $html .= "<td>" . $name . "</td>";
                    $html .= "<td>" . $gender . "</td>";
                    $html .= "<td>" . $admissionNumber . "</td>";
                   $html .= "<td>" . $rp['Id'] . "</td>";
                   $html .= "<td>" . $ro['Id'] . "</td>";
                    $html .= "</tr>";
                    $dateCreated = date("Y-m-d");


                    						// Insert into database
						$query = "INSERT INTO `tblstudents`(`name`, `gender`, `admissionNumber`, `classId`, `classArmId`, `dateCreated`) VALUES ('$name','$gender','$regNumber',".$rp['Id'].",". $ro['Id'] .",'$dateCreated')";
						$conn->query($query);		

                }

                $html .= "</table>";
                echo "
                <link href='img/logo/attnlg.jpg' rel='icon'>
                <link href='../vendor/fontawesome-free/css/all.min.css' rel='stylesheet' type='text/css'>
                <link href='../vendor/bootstrap/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
                <link href='css/ruang-admin.min.css' rel='stylesheet'>
            
                <center><br><br><br><br><br><br><br><br><br>
                <img src='../img/saved.webp' width='200px'>
<h2>Successfully Data Is Saved</h2>
<a href='excel2.php' class='btn btn-primary'>Back To View</a>
</center>

                
                ";
            


                // Remove the uploaded file
                unlink($file);
            } else {
                echo "<script>alert('Importing Failed. Try Again'); location.href='excel2.php';</script>";
            }
        } else {
            echo "<script>alert('Please choose an Excel file'); location.href='excel2.php';</script>";
        }
    } else {
        echo "<script>alert('Try to choose an Excel file'); location.href='excel2.php';</script>";
    }
}
?>
