<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../Includes/dbcon.php';

// Include PhpSpreadsheet autoloader and namespaces
require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Check session
if (!isset($_SESSION['userId'])) {
    die("<script>alert('User session not found. Please log in.'); location.href='excel2.php';</script>");
}

if (isset($_POST['submit'])) {
    $classId = $_POST['class'] ?? null;

    $a ="SELECT * FROM `tblclassarms` WHERE classId='$classId'";
    $s = $conn->query($a);
    $r = $s->fetch_assoc();
    $classArmId = $r['Id'];

    // Validate form inputs

    if (isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != "") {
        $allowedExtensions = ['xls', 'xlsx'];
        $ext = pathinfo($_FILES['uploadFile']['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($ext), $allowedExtensions)) {
            // Ensure Uploads directory exists
            $uploadDir = 'Uploads/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    die("<script>alert('Failed to create Uploads directory'); location.href='excel2.php';</script>");
                }
            }

            // Move uploaded file
            $file = $uploadDir . basename($_FILES['uploadFile']['name']);
            if (!move_uploaded_file($_FILES['uploadFile']['tmp_name'], $file)) {
                die("<script>alert('Failed to upload file. Check permissions.'); location.href='excel2.php';</script>");
            }

            try {
                // Load uploaded file
                $spreadsheet = IOFactory::load($file);
                $sheet = $spreadsheet->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

                // Debug: Log Excel file content
                $debugLog = "Excel file rows: $highestRow, columns: $highestColumnIndex\n";
                $records = [];
                for ($row = 2; $row <= $highestRow; $row++) {
                    $rowData = [];
                    for ($col = 1; $col <= min($highestColumnIndex, 2); $col++) {
                        $columnLetter = Coordinate::stringFromColumnIndex($col);
                        $cell = $sheet->getCell($columnLetter . $row);
                        $value = $cell->getValue();
                        $rowData[] = $value;
                        $debugLog .= "Row $row, Col $columnLetter: " . ($value ?? 'NULL') . "\n";
                    }
                    $records[] = $rowData;
                }
                // Save debug log to file
                file_put_contents('debug_log.txt', $debugLog);

                // Fetch school code
                $stmt = $conn->prepare("SELECT school FROM tbladmin WHERE Id = ?");
                $stmt->bind_param("i", $_SESSION['userId']);
                if (!$stmt->execute()) {
                    throw new Exception("Database error: Unable to fetch school");
                }
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $school = $row['school'] ?? null;
                $stmt->close();

                if (!$school) {
                    throw new Exception("School not found for user ID {$_SESSION['userId']}");
                }

                $stmt = $conn->prepare("SELECT code FROM schools WHERE schoolName = ?");
                $stmt->bind_param("s", $school);
                if (!$stmt->execute()) {
                    throw new Exception("Database error: Unable to fetch school code");
                }
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $schoolCode = $row['code'] ?? '';
                $stmt->close();

                if (!$schoolCode) {
                    throw new Exception("School code not found for school: $school");
                }

         

                // Fetch class level
                $stmt = $conn->prepare("SELECT level FROM tblclass WHERE Id = ?");
                $stmt->bind_param("i", $classId);
                if (!$stmt->execute()) {
                    throw new Exception("Database error: Unable to fetch class level");
                }
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $classLevel = $row['level'] ?? '';
                $stmt->close();

                if (!$classLevel) {
                    throw new Exception("Class level not found for class ID: $classId");
                }

                // Count existing students for registration number
                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM tblstudents WHERE classId = ?");
                $stmt->bind_param("i", $classId);
                if (!$stmt->execute()) {
                    throw new Exception("Database error: Unable to count students");
                }
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $regCounter = (int)$row['count'];
                $stmt->close();

                // Generate HTML table
                $html = "<table border='1' class='table table-bordered'>";
                $html .= "<tr><th>#</th><th>Name</th><th>Gender</th><th>Admission Number</th><th>Class ID</th><th>Class Arm ID</th></tr>";

                $year = date("Y");
                $rowId = 1;
                $skippedRows = [];

                foreach ($records as $index => $row) {
                    // Skip empty or invalid rows
                    $name = trim($row[0] ?? '');
                    $gender = trim($row[1] ?? '');
         
                    // Generate admission number
                    $regCounter++;
                    $admissionNumber = $schoolCode . $classLevel . sprintf('%03d', $regCounter) . $year;

                    // Current date
                    $dateCreated = date("Y-m-d");

                    // Insert into database
// Prepare the statement
$stmt = $conn->prepare("
    INSERT INTO tblstudents (name, gender, admissionNumber, classId, classArmId, dateCreated)
    VALUES (?, ?, ?, ?, ?, ?)
");
// Bind parameters with correct types: s (string), i (integer)
$stmt->bind_param("sssiss", $name, $gender, $admissionNumber, $classId, $classArmId, $dateCreated);
// Execute the query
if (!$stmt->execute()) {
    throw new Exception("Failed to insert record for $name: " . $stmt->error);
}
// Close the statement
$stmt->close();
                    // Build table row
                    $html .= "<tr>";
                    $html .= "<td>$rowId</td>";
                    $html .= "<td>" . htmlspecialchars($name) . "</td>";
                    $html .= "<td>" . htmlspecialchars($gender) . "</td>";
                    $html .= "<td>" . htmlspecialchars($admissionNumber) . "</td>";
                    $html .= "<td>" . htmlspecialchars($classId) . "</td>";
                    $html .= "<td>".$classArmId . "</td>";

                    $html .= "</tr>";

                    $rowId++;
                }

                // Log skipped rows
                if (!empty($skippedRows)) {
                    file_put_contents('debug_log.txt', "Skipped rows:\n" . implode("\n", $skippedRows) . "\n", FILE_APPEND);
                }

                $html .= "</table>";

                if ($rowId == 1) {
                    throw new Exception("No valid records found in the Excel file. Check debug_log.txt for details.");
                }

                // Display success message
                echo "
                    <link href='img/logo/attnlg.jpg' rel='icon'>
                    <link href='../vendor/fontawesome-free/css/all.min.css' rel='stylesheet' type='text/css'>
                    <link href='../vendor/bootstrap/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
                    <link href='css/ruang-admin.min.css' rel='stylesheet'>
                    <center><br><br><br><br><br><br><br><br><br>
                        <img src='../img/saved.webp' width='200px'>
                        <h2>Successfully Data Is Saved</h2>
                  <a href='excel2.php' class='btn btn-primary mt-3'>Back To View</a>

                        $html
                    </center>
                ";

                // Clean up
                unlink($file);
            } catch (Exception $e) {
                if (file_exists($file)) {
                    unlink($file);
                }
                die("<script>alert('Error: " . addslashes($e->getMessage()) . "'); location.href='excel2.php';</script>");
            }
        } else {
            echo "<script>alert('Please choose a valid Excel file (.xls or .xlsx)'); location.href='excel2.php';</script>";
        }
    } else {
        echo "<script>alert('Please choose an Excel file'); location.href='excel2.php';</script>";
    }
}
?>