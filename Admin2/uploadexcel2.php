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

                    // Fetch class details
                    $q = "SELECT * FROM tblclass WHERE Id='$class'";
                    $r = $conn->query($q);
                    $rp = $r->fetch_assoc();

                    // Fetch class arms
                    $a = "SELECT * FROM tblclassarms WHERE classId=" . $rp['Id'] . "";
                    $re = $conn->query($a);
                    $ro = $re->fetch_assoc();

                    // Generate registration number
                    $regNumber = $ro['classArmName'] . sprintf('%02d', $regCounter);
                    $regCounter++; // Increment the counter

                    // Build the table row
                    $html .= "<tr>";
                    $html .= "<td>" . $id . "</td>";
                    $html .= "<td>" . $name . "</td>";
                    $html .= "<td>" . $gender . "</td>";
                    $html .= "<td>" . $regNumber . "</td>";
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
