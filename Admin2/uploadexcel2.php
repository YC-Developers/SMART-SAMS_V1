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
                // Include database configuration and PhpSpreadsheet
                include("../Includes/dbcon.php");
                require_once __DIR__ . '/vendor/autoload.php';

                try {
                    // Identify the file type and create appropriate reader
                    if ($ext == "xlsx") {
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    } else {
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                    }
                    
                    // Load the spreadsheet file
                    $spreadsheet = $reader->load($file);
                    $sheet = $spreadsheet->getActiveSheet();
                    
                    // Get the highest row and column
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                    $html = "<table border='1'>";
                    $html .= "<tr><th>#</th><th>Name</th><th>Gender</th><th>RegNumber</th><th>Class ID</th><th>ClassAmId</th></tr>";

                    $regCounter = 1; // Initialize the RegNumber counter

                    // Loop through each row of the worksheet
                    for ($row = 1; $row <= $highestRow; $row++) {
                        // Get row data
                        $rowData = [];
                        for ($col = 1; $col <= $highestColumnIndex; $col++) {
                            $cell = $sheet->getCellByColumnAndRow($col, $row);
                            $rowData[$col - 1] = $cell->getValue();
                        }

                        // Skip empty rows
                        if (empty(array_filter($rowData))) {
                            continue;
                        }

                        // Extract data from the row
                        $id = $rowData[0] ?? '';
                        $name = $rowData[1] ?? '';
                        $gender = $rowData[2] ?? '';

                        // Skip header row if needed
                        if ($row == 1 && (strtolower($name) == 'name' || strtolower($gender) == 'gender')) {
                            continue;
                        }

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
                        $html .= "<td>" . htmlspecialchars($id) . "</td>";
                        $html .= "<td>" . htmlspecialchars($name) . "</td>";
                        $html .= "<td>" . htmlspecialchars($gender) . "</td>";
                        $html .= "<td>" . htmlspecialchars($regNumber) . "</td>";
                        $html .= "<td>" . htmlspecialchars($rp['Id']) . "</td>";
                        $html .= "<td>" . htmlspecialchars($ro['Id']) . "</td>";
                        $html .= "</tr>";
                        
                        $dateCreated = date("Y-m-d");

                        // Insert into database
                        $query = "INSERT INTO `tblstudents`(`name`, `gender`, `admissionNumber`, `classId`, `classArmId`, `dateCreated`) 
                                 VALUES (?, ?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("sssiis", $name, $gender, $regNumber, $rp['Id'], $ro['Id'], $dateCreated);
                        $stmt->execute();
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

                } catch (Exception $e) {
                    die('Error processing file: ' . $e->getMessage());
                }
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