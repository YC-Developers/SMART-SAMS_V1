<?php
include '../Includes/dbcon.php';
include '../Includes/session3.php';

function generateAIContent($student) {
    // Create AI file content
    $content = "%!PS-Adobe-3.0\n";
    $content .= "%%Creator: SMART-SAMS\n";
    $content .= "%%Title: Student ID Card\n";
    $content .= "%%BoundingBox: 0 0 612 792\n";
    $content .= "%%Pages: 1\n";
    $content .= "%%EndComments\n\n";

    // Define colors
    $content .= "/LightBlue {0.0 0.776 1.0 setrgbcolor} def\n";
    $content .= "/White {1.0 1.0 1.0 setrgbcolor} def\n";
    $content .= "/Gray {0.973 0.976 0.98 setrgbcolor} def\n";
    $content .= "/DarkGray {0.392 0.392 0.392 setrgbcolor} def\n";
    $content .= "/Black {0.0 0.0 0.0 setrgbcolor} def\n\n";

    // Card background
    $content .= "Gray\n";
    $content .= "0 0 612 792 rectfill\n\n";

    // Card container
    $content .= "Black\n";
    $content .= "42.5 42.5 527 680 rectstroke\n\n";

    // Header background
    $content .= "LightBlue\n";
    $content .= "42.5 42.5 527 198 rectfill\n\n";

    // School logo
    $logoPath = '../img/logo/attnlg.jpg';
    if (file_exists($logoPath)) {
        $content .= "White\n";
        $content .= "57.5 142.5 140 140 rectfill\n";
        $content .= "%%BeginDocument: logo\n";
        $content .= "57.5 142.5 translate\n";
        $content .= "140 140 scale\n";
        $content .= "%%BeginImage\n";
        $content .= "%%EndImage\n";
        $content .= "%%EndDocument\n\n";
    }

    // School name and title
    $content .= "White\n";
    $content .= "/Helvetica-Bold findfont 24 scalefont setfont\n";
    $content .= "212.5 180 moveto\n";
    $content .= "(" . $student['schoolName'] . ") show\n";
    $content .= "/Helvetica-Bold findfont 18 scalefont setfont\n";
    $content .= "212.5 150 moveto\n";
    $content .= "(STUDENT IDENTIFICATION CARD) show\n\n";

    // Student photo
    $imagePath = $student['image_path'] ? '../Admin2/student_images/' . $student['image_path'] : '../img/user-icn.png';
    if (file_exists($imagePath)) {
        $content .= "White\n";
        $content .= "57.5 312.5 140 140 rectfill\n";
        $content .= "%%BeginDocument: photo\n";
        $content .= "57.5 312.5 translate\n";
        $content .= "140 140 scale\n";
        $content .= "%%BeginImage\n";
        $content .= "%%EndImage\n";
        $content .= "%%EndDocument\n\n";
    }

    // Student details
    $content .= "Black\n";
    $content .= "/Helvetica-Bold findfont 20 scalefont setfont\n";
    $content .= "212.5 350 moveto\n";
    $content .= "(" . $student['name'] . ") show\n\n";

    $content .= "/Helvetica findfont 14 scalefont setfont\n";
    $content .= "212.5 320 moveto\n";
    $content .= "DarkGray\n";
    $content .= "(Reg No:) show\n";
    $content .= "Black\n";
    $content .= " 280 320 moveto\n";
    $content .= "(" . $student['admissionNumber'] . ") show\n\n";

    $content .= "212.5 290 moveto\n";
    $content .= "DarkGray\n";
    $content .= "(Class:) show\n";
    $content .= "Black\n";
    $content .= " 280 290 moveto\n";
    $content .= "(" . $student['classArmName'] . ") show\n\n";

    $content .= "212.5 260 moveto\n";
    $content .= "DarkGray\n";
    $content .= "(Gender:) show\n";
    $content .= "Black\n";
    $content .= " 280 260 moveto\n";
    $content .= "(" . $student['gender'] . ") show\n\n";

    // Valid until date
    $content .= "/Helvetica-Oblique findfont 12 scalefont setfont\n";
    $content .= "DarkGray\n";
    $content .= "57.5 200 moveto\n";
    $content .= "(Valid until: " . date('Y-m-d', strtotime('+1 year')) . ") show\n\n";

    // Decorative elements
    $content .= "LightBlue\n";
    $content .= "397.5 200 85 85 rectfill\n";
    $content .= "LightBlue\n";
    $content .= "412.5 215 55 55 rectfill\n\n";

    // Footer line
    $content .= "LightBlue\n";
    $content .= "42.5 42.5 527 2 rectfill\n\n";

    $content .= "%%EOF\n";
    return $content;
}

// Handle AI file export
if (isset($_POST['export_all_ai']) && isset($_POST['class'])) {
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
        // Create a temporary directory in the current directory
        $tempDir = __DIR__ . '/temp_export_' . time();
        if (!mkdir($tempDir, 0777, true)) {
            die("Cannot create temporary directory");
        }

        while ($student = $studentsResult->fetch_assoc()) {
            // Create AI file content
            $aiContent = generateAIContent($student);

            // Save AI file with absolute path
            $filename = $tempDir . '/student_card_' . $student['admissionNumber'] . '.ai';
            if (file_put_contents($filename, $aiContent) === false) {
                die("Cannot write to file: " . $filename);
            }
        }

        // Create zip file with absolute path
        $zipFilename = __DIR__ . '/student_cards_' . date('Y-m-d_H-i-s') . '.zip';
        $zip = new ZipArchive();

        if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            die("Cannot create zip file");
        }

        // Add files to zip
        $files = glob($tempDir . '/*.ai');
        foreach ($files as $file) {
            if (file_exists($file)) {
                $zip->addFile($file, basename($file));
            }
        }

        if (!$zip->close()) {
            die("Cannot close zip file");
        }

        // Clean up temporary directory
        array_map('unlink', glob($tempDir . '/*'));
        rmdir($tempDir);

        // Send zip file to browser
        if (file_exists($zipFilename)) {
            // Clear any previous output
            if (ob_get_level()) ob_end_clean();

            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($zipFilename) . '"');
            header('Content-Length: ' . filesize($zipFilename));
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');

            // Read and output file in chunks
            $handle = fopen($zipFilename, 'rb');
            if ($handle) {
                while (!feof($handle)) {
                    echo fread($handle, 8192);
                    flush();
                }
                fclose($handle);
            } else {
                die("Cannot open zip file for reading");
            }

            // Clean up zip file
            unlink($zipFilename);
            exit;
        } else {
            die("Zip file not found");
        }
    }
}
?>
