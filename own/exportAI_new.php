<?php
include '../Includes/dbcon.php';
include '../Includes/session3.php';

function generateSVGContent($student) {
    // Create SVG content
    $content = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="612" height="792" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <!-- Background -->
    <rect x="0" y="0" width="612" height="792" fill="#f8f9fa"/>

    <!-- Card container -->
    <rect x="42.5" y="42.5" width="527" height="680" fill="none" stroke="#000" stroke-width="1"/>

    <!-- Header background -->
    <rect x="42.5" y="42.5" width="527" height="198" fill="#00c6ff"/>

    <!-- School logo -->
    <image x="57.5" y="142.5" width="140" height="140" xlink:href="data:image/jpeg;base64,' . base64_encode(file_get_contents('../img/logo/attnlg.jpg')) . '"/>

    <!-- School name and title -->
    <text x="212.5" y="180" font-family="Helvetica-Bold" font-size="24" fill="#fff">' . htmlspecialchars($student['schoolName']) . '</text>
    <text x="212.5" y="150" font-family="Helvetica-Bold" font-size="18" fill="#fff">STUDENT IDENTIFICATION CARD</text>

    <!-- Student photo -->
    <image x="57.5" y="312.5" width="140" height="140" xlink:href="data:image/jpeg;base64,' . base64_encode(file_get_contents($student['image_path'] ? '../Admin2/student_images/' . $student['image_path'] : '../img/user-icn.png')) . '"/>

    <!-- Student details -->
    <text x="212.5" y="350" font-family="Helvetica-Bold" font-size="20" fill="#000">' . htmlspecialchars($student['name']) . '</text>

    <text x="212.5" y="320" font-family="Helvetica" font-size="14" fill="#666">Reg No:</text>
    <text x="280" y="320" font-family="Helvetica" font-size="14" fill="#000">' . htmlspecialchars($student['admissionNumber']) . '</text>

    <text x="212.5" y="290" font-family="Helvetica" font-size="14" fill="#666">Class:</text>
    <text x="280" y="290" font-family="Helvetica" font-size="14" fill="#000">' . htmlspecialchars($student['classArmName']) . '</text>

    <text x="212.5" y="260" font-family="Helvetica" font-size="14" fill="#666">Gender:</text>
    <text x="280" y="260" font-family="Helvetica" font-size="14" fill="#000">' . htmlspecialchars($student['gender']) . '</text>

    <!-- Valid until date -->
    <text x="57.5" y="200" font-family="Helvetica-Oblique" font-size="12" fill="#666">Valid until: ' . date('Y-m-d', strtotime('+1 year')) . '</text>

    <!-- Decorative elements -->
    <rect x="397.5" y="200" width="85" height="85" fill="#00c6ff"/>
    <rect x="412.5" y="215" width="55" height="55" fill="#00c6ff"/>

    <!-- Footer line -->
    <rect x="42.5" y="42.5" width="527" height="2" fill="#00c6ff"/>
</svg>';

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
            // Create SVG file content
            $svgContent = generateSVGContent($student);

            // Save SVG file with absolute path
            $filename = $tempDir . '/student_card_' . $student['admissionNumber'] . '.svg';
            if (file_put_contents($filename, $svgContent) === false) {
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
        $files = glob($tempDir . '/*.svg');
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
