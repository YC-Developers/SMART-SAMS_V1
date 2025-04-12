<?php
include '../Includes/dbcon.php';
include '../Includes/session3.php';
require_once('../Admin/vendor/autoload.php');

// Handle batch PDF export
if (isset($_POST['export_all_pdf']) && isset($_POST['class'])) {
    $classId = $_POST['class'];

    // Get all students for the class
    $studentsQuery = "SELECT s.*, c.className, ca.classArmName, sc.schoolName
                    FROM tblstudents s
                    INNER JOIN tblclass c ON s.classId = c.Id
                    INNER JOIN tblclassarms ca ON s.classArmId = ca.Id
                    INNER JOIN schools sc ON c.school = sc.schoolName
                    WHERE s.classId = '$classId'";
    $studentsResult = $conn->query($studentsQuery);

    if ($studentsResult && $studentsResult->num_rows > 0) {
        // Create a single PDF document
        $pdf = new FPDF('P', 'mm', 'A4');

        while ($student = $studentsResult->fetch_assoc()) {
            // Add a new page for each student
            $pdf->AddPage();

            // Set background color for the page
            $pdf->SetFillColor(248, 249, 250);
            $pdf->Rect(0, 0, 210, 297, 'F');

            // Main card container with shadow effect
            $pdf->SetDrawColor(200, 200, 200);
            $pdf->SetLineWidth(0.5);
            $pdf->Rect(16, 16, 180, 220, 'F');
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->Rect(15, 15, 180, 220, 'D');

            // Header background with gradient effect
            $pdf->SetFillColor(0, 198, 255);
            $pdf->Rect(15, 15, 180, 70, 'F');

            // School logo with circular effect
            $logoPath = '../img/logo/attnlg.jpg';
            if (file_exists($logoPath)) {
                try {
                    // Create circular effect for logo
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->Rect(15, 25, 50, 50, 'F');
                    $pdf->Image($logoPath, 15, 25, 50, 50);
                } catch (Exception $e) {
                    // Skip logo if there's an error
                }
            }

            // School name and title
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->SetXY(75, 30);
            $pdf->Cell(0, 10, $student['schoolName'], 0, 1);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetXY(75, 45);
            $pdf->Cell(0, 10, 'STUDENT IDENTIFICATION CARD', 0, 1);

            // Reset text color for content
            $pdf->SetTextColor(0, 0, 0);

            // Student photo with circular effect
            $imagePath = $student['image_path'] ? '../Admin2/student_images/' . $student['image_path'] : '../img/user-icn.png';
            if (file_exists($imagePath)) {
                try {
                    // Create circular effect for photo
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->Rect(15, 90, 80, 80, 'F');
                    $pdf->Image($imagePath, 15, 90, 80, 80);
                } catch (Exception $e) {
                    // Skip photo if there's an error
                }
            }

            // Student details with enhanced styling
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->SetXY(90, 100);
            $pdf->Cell(0, 10, $student['name'], 0, 1);

            $pdf->SetFont('Arial', '', 12);
            $pdf->SetXY(90, 120);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(30, 8, 'Reg No:', 0, 0);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, $student['admissionNumber'], 0, 1);

            $pdf->SetXY(90, 135);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(30, 8, 'Class:', 0, 0);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, $student['classArmName'], 0, 1);

            $pdf->SetXY(90, 150);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(30, 8, 'Gender:', 0, 0);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, $student['gender'], 0, 1);

            // Decorative element in bottom right
            $pdf->SetFillColor(0, 198, 255);
            $pdf->Rect(140, 170, 30, 30, 'F');
            $pdf->SetFillColor(0, 198, 255);
            $pdf->Rect(145, 175, 20, 20, 'F');

            // Valid until date with styling
            $pdf->SetFont('Arial', 'I', 10);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->SetXY(20, 200);
            $pdf->Cell(0, 10, 'Valid until: ' . date('Y-m-d', strtotime('+1 year')), 0, 1);

            // Add decorative line
            $pdf->SetDrawColor(0, 198, 255);
            $pdf->SetLineWidth(0.5);
            $pdf->Line(15, 210, 195, 210);
        }

        // Output the PDF
        $pdf->Output('student_cards_' . date('Y-m-d_H-i-s') . '.pdf', 'D');
        exit;
    }
}

// Handle batch AI export
if (isset($_POST['export_all_ai']) && isset($_POST['class'])) {
    include 'exportAI.php';
    exit;
}

// Handle form submission
if (isset($_POST['generate'])) {
    $schoolId = $_POST['school'];
    $classId = $_POST['class'];

    // Get school details
    $schoolQuery = "SELECT * FROM schools WHERE id = '$schoolId'";
    $schoolResult = $conn->query($schoolQuery);
    if ($schoolResult && $schoolResult->num_rows > 0) {
        $school = $schoolResult->fetch_assoc();

        // Get class details
        $classQuery = "SELECT * FROM tblclass WHERE Id = '$classId'";
        $classResult = $conn->query($classQuery);
        if ($classResult && $classResult->num_rows > 0) {
            $class = $classResult->fetch_assoc();

            // Get students for the selected class with their class arm information
            $studentsQuery = "SELECT s.*, c.className, ca.classArmName
                            FROM tblstudents s
                            INNER JOIN tblclass c ON s.classId = c.Id
                            INNER JOIN tblclassarms ca ON s.classArmId = ca.Id
                            WHERE s.classId = '$classId'";
            $studentsResult = $conn->query($studentsQuery);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/logo/attnlg.jpg" rel="icon">
    <title>Generate Student Cards</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <style>
        .student-card {
            width: 370px;
            height: 220px;
            border: none;
            border-radius: 15px;
            padding: 20px;
            margin: 15px;
            display: inline-block;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            position: relative;
            overflow: hidden;
        }
        .student-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 70px;
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            border-radius: 15px 15px 0 0;
            z-index: 1;
        }
        .student-card::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            border-radius: 50%;
            opacity: 0.1;
        }
        .school-header {
            position: relative;
            z-index: 2;
            color: white;
            padding: 5px 0;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .school-logo {
            width: 50px;
            height: 50px;
            background: #fff;
            border-radius: 50%;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .school-logo img {
            max-width: 40px;
            max-height: 40px;
        }
        .school-info {
            flex: 1;
        }
        .school-info h3 {
            margin: 0;
            font-size: 1.2em;
            font-weight: bold;
            text-transform: uppercase;
        }
        .school-info h4 {
            margin: 3px 0 0 0;
            font-size: 0.9em;
            font-weight: normal;
        }
        .student-photo {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-right: 15px;
            object-fit: cover;
            float: left;
        }
        .student-info {
            margin-left: 100px;
            position: relative;
            z-index: 2;
            padding-top: 5px;
        }
        .student-info h3 {
            margin: 0 0 8px 0;
            font-size: 1.1em;
            color: #333;
            font-weight: bold;
        }
        .student-info p {
            margin: 4px 0;
            font-size: 0.85em;
            color: #666;
            line-height: 1.3;
        }
        .student-info .label {
            font-weight: 600;
            color: #444;
            display: inline-block;
            width: 60px;
        }
        .valid-date {
            position: absolute;
            bottom: 2px;
            left: 20px;
            text-align: left;
            font-size: 0.75em;
            color: #666;
        }
        @media print {
            .no-print {
                display: none;
            }
            .student-card {
                page-break-inside: avoid;
                margin: 10px;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include "Includes/sidebar.php";?>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include "Includes/topbar.php";?>
                <!-- Topbar -->

                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Generate Student Cards</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Generate Student Cards</li>
                        </ol>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Select School and Class</h6>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="">
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label>Select School</label>
                                                <select class="form-control" name="school" id="schoolSelect" required>
                                                    <option value="">--Select School--</option>
                                                    <?php
                                                    $schoolsQuery = "SELECT * FROM schools";
                                                    $schoolsResult = $conn->query($schoolsQuery);
                                                    while ($school = $schoolsResult->fetch_assoc()) {
                                                        $selected = (isset($_POST['school']) && $_POST['school'] == $school['id']) ? 'selected' : '';
                                                        echo "<option value='" . $school['id'] . "' " . $selected . ">" . $school['schoolName'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Select Class</label>
                                                <select class="form-control" name="class" id="classSelect" required>
                                                    <option value="">--Select Class--</option>
                                                    <?php
                                                    if (isset($_POST['school'])) {
                                                        $schoolQuery = "SELECT schoolName FROM schools WHERE id = '" . $_POST['school'] . "'";
                                                        $schoolResult = $conn->query($schoolQuery);
                                                        if ($schoolResult && $schoolResult->num_rows > 0) {
                                                            $school = $schoolResult->fetch_assoc();
                                                            $classesQuery = "SELECT * FROM tblclass WHERE school = '" . $school['schoolName'] . "'";
                                                            $classesResult = $conn->query($classesQuery);
                                                            while ($class = $classesResult->fetch_assoc()) {
                                                                $selected = (isset($_POST['class']) && $_POST['class'] == $class['Id']) ? 'selected' : '';
                                                                echo "<option value='" . $class['Id'] . "' " . $selected . ">" . $class['className'] . "</option>";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <button type="submit" name="generate" class="btn btn-primary">Generate Cards</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($_POST['generate']) && isset($studentsResult) && $studentsResult->num_rows > 0): ?>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Student Cards</h6>
                                    <div>
                                        <form method="post" style="display: inline;">
                                            <input type="hidden" name="class" value="<?php echo $classId; ?>">
                                            <button type="submit" name="export_all_pdf" class="btn btn-primary">
                                                <i class="fas fa-file-pdf"></i> Export All Cards
                                            </button>
                                        </form>
                                        <form method="post" action="exportAI_new.php" style="display: inline;">
                                            <input type="hidden" name="class" value="<?php echo $classId; ?>">
                                            <button type="submit" name="export_all_ai" class="btn btn-success">
                                                <i class="fas fa-file-export"></i> Export All as AI
                                            </button>
                                        </form>
                                        <button onclick="window.print()" class="btn btn-secondary">
                                            <i class="fas fa-print"></i> Print Cards
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="print-section">
                                        <?php while ($student = $studentsResult->fetch_assoc()): ?>
                                        <div class="student-card">
                                            <div class="school-header">
                                                <div class="school-logo">
                                                    <img src="../img/logo/attnlg.jpg" alt="School Logo">
                                                </div>
                                                <div class="school-info">
                                                    <h3><?php echo $school['schoolName']; ?></h3>
                                                    <h4>STUDENT IDENTIFICATION CARD</h4>
                                                </div>
                                            </div>
                                            <img class="student-photo" src="<?php echo $student['image_path'] ? '../Admin2/student_images/' . $student['image_path'] : '../img/user-icn.png'; ?>" alt="Student Photo">
                                            <div class="student-info">
                                                <h3><?php echo $student['name']; ?></h3>
                                                <p><span class="label">Regno:</span> <?php echo $student['admissionNumber']; ?></p>
                                                <p><span class="label">Class:</span> <?php echo $student['classArmName']; ?></p>
                                                <p><span class="label">Gender:</span> <?php echo $student['gender']; ?></p>
                                            </div>
                                            <div class="valid-date">Valid until: <br> <?php echo date('Y-m-d', strtotime('+1 year')); ?></div>
                                        </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update class dropdown when school is selected
        $('#schoolSelect').change(function() {
            var schoolId = $(this).val();
            if (schoolId) {
                $.ajax({
                    url: 'getClasses.php',
                    type: 'POST',
                    data: {schoolId: schoolId},
                    success: function(response) {
                        $('#classSelect').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        $('#classSelect').html('<option value="">Error loading classes</option>');
                    }
                });
            } else {
                $('#classSelect').html('<option value="">--Select Class--</option>');
            }
        });
    </script>
</body>
</html>
