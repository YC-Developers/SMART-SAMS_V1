<?php
require_once 'timetable_generator.php';

$generator = new TimetableGenerator($conn, $timeSlots, $days);

if (isset($_POST['generate'])) {
    $generator->generateTimetable();
    echo "<p>Timetable generated successfully!</p>";
}

if (isset($_POST['update'])) {
    if ($generator->updateTimetableEntry($_POST['id'], $_POST['time_slot'], $_POST['day'])) {
        echo "<p>Timetable updated successfully!</p>";
    } else {
        echo "<p>Update failed: Teacher conflict or lesson assignment issue!</p>";
    }
}

$classes = $conn->query("SELECT Id, className FROM tblclass WHERE school = 'ETS'")->fetch_all(MYSQLI_ASSOC);

// Handle PDF export
if (isset($_GET['export']) && $_GET['export'] === 'pdf') {
    header('Content-Type: text/html');
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>All Timetables - PDF Export</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f8f9fa;
                padding: 20px;
            }
            .timetable-container {
                margin-bottom: 40px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 20px;
            }
            h2 {
                color: #007bff;
                font-weight: bold;
                margin-bottom: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 14px;
            }
            th, td {
                border: 1px solid #dee2e6;
                padding: 12px;
                text-align: center;
                vertical-align: middle;
            }
            th {
                background-color: #007bff;
                color: white;
                font-weight: 600;
            }
            td {
                background-color: #ffffff;
                transition: background-color 0.3s;
            }
            td:hover {
                background-color: #e9ecef;
            }
            .empty {
                background-color: #f8f9fa;
                color: #6c757d;
            }
            .header {
                text-align: center;
                margin-bottom: 40px;
                padding: 20px;
                background: #007bff;
                color: white;
                border-radius: 10px;
            }

            @media print {
                @page { 
                    margin: 20mm; 
                }
                body { 
                    background-color: white; 
                    padding: 0; 
                }
                .timetable-container {
                    box-shadow: none;
                    margin-bottom: 20mm;
                    padding: 0;
                    page-break-inside: avoid;
                }
                h2 {
                    page-break-before: always;
                    margin-top: 10mm;
                    color: #000;
                }
                h2:first-child {
                    page-break-before: avoid;
                }
                table {
                    font-size: 12pt;
                }
                th {
                    background-color: #e9ecef !important;
                    color: #000 !important;
                }
                td:hover {
                    background-color: inherit;
                }
                .empty {
                    background-color: #f1f3f5;
                }
                .header {
                    background: none;
                    color: #000;
                    padding: 0;
                    margin-bottom: 10mm;
                }
                .no-print {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>All Class Timetables - ETS</h1>
            <p>Generated on: <?php echo date('Y-m-d'); ?></p>
        </div>

        <?php foreach ($classes as $class): ?>
            <div class="timetable-container">
                <h2><?php echo $class['className']; ?></h2>
                <?php $timetable = $generator->getClassTimetable($class['Id']); ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <?php foreach ($days as $day): ?>
                                <th><?php echo $day; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($timeSlots as $slot): ?>
                            <tr>
                                <td><?php echo $slot; ?></td>
                                <?php foreach ($days as $day): 
                                    $entry = array_filter($timetable, function($item) use ($day, $slot) {
                                        return $item['day'] === $day && $item['time_slot'] === $slot;
                                    });
                                    $entry = reset($entry);
                                ?>
                                    <td <?php echo !$entry ? 'class="empty"' : ''; ?>>
                                        <?php if ($entry): ?>
                                            <?php 
                                            $teacherName = explode(' ', trim($entry['teacher_name']))[1] ?? $entry['teacher_name'];
                                            echo "<strong>" . $teacherName . "</strong><br>" . $entry['lesson_title']; 
                                            ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>

        <div class="no-print text-center mt-4">
            <button class="btn btn-primary" onclick="window.print()">Print to PDF</button>
            <button class="btn btn-secondary" onclick="window.close()">Close</button>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Auto-print if not cancelled
            const printWindow = window.open('', '', 'height=800,width=1200');
  printWindow.document.write('<html><head>  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"><link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"><link href="css/ruang-admin.min.css" rel="stylesheet"><title>AMS </title></head><body>');
  printWindow.document.write(reportContent);
    printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
        </script>
    </body>
    </html>
    <?php
    exit;
}

// Handle CSV export (unchanged)
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="all_timetables.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Class', 'Time', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
    
    foreach ($classes as $class) {
        $timetable = $generator->getClassTimetable($class['Id']);
        foreach ($timeSlots as $slot) {
            $row = [$class['className'], $slot];
            foreach ($days as $day) {
                $entry = array_filter($timetable, function($item) use ($day, $slot) {
                    return $item['day'] === $day && $item['time_slot'] === $slot;
                });
                $entry = reset($entry);
                $cell = $entry ? (explode(' ', trim($entry['teacher_name']))[1] ?? $entry['teacher_name']) . " - " . $entry['lesson_title'] : '';
                $row[] = $cell;
            }
            fputcsv($output, $row);
        }
        fputcsv($output, []);
    }
    
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Timetable Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .empty { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Timetable Generator</h1>
        
        <div class="mb-4 text-center">
            <form method="post" class="d-inline">
                <button type="submit" name="generate" class="btn btn-primary">Generate New Timetable</button>
            </form>
            <a href="?export=pdf" target="_blank" class="btn btn-success">Export to PDF</a>
            <a href="?export=csv" class="btn btn-info">Export to CSV</a>
        </div>

        <?php foreach ($classes as $class): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="card-title"><?php echo $class['className']; ?></h2>
                </div>
                <div class="card-body">
                    <?php $timetable = $generator->getClassTimetable($class['Id']); ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Time</th>
                                    <?php foreach ($days as $day): ?>
                                        <th><?php echo $day; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($timeSlots as $slot): ?>
                                    <tr>
                                        <td><?php echo $slot; ?></td>
                                        <?php foreach ($days as $day): 
                                            $entry = array_filter($timetable, function($item) use ($day, $slot) {
                                                return $item['day'] === $day && $item['time_slot'] === $slot;
                                            });
                                            $entry = reset($entry);
                                        ?>
                                            <td <?php echo !$entry ? 'class="empty"' : ''; ?>>
                                                <?php if ($entry): ?>
                                                    <?php 
                                                    $teacherName = explode(' ', trim($entry['teacher_name']))[1] ?? $entry['teacher_name'];
                                                    echo "<strong>" . $teacherName . "</strong><br>" . $entry['lesson_title']; 
                                                    ?>
                                                    <form method="post" class="mt-2">
                                                        <input type="hidden" name="id" value="<?php echo $entry['id']; ?>">
                                                        <select name="time_slot" class="form-select form-select-sm mb-1">
                                                            <?php foreach ($timeSlots as $ts): ?>
                                                                <option value="<?php echo $ts; ?>" <?php echo $ts === $slot ? 'selected' : ''; ?>>
                                                                    <?php echo $ts; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <select name="day" class="form-select form-select-sm mb-1">
                                                            <?php foreach ($days as $d): ?>
                                                                <option value="<?php echo $d; ?>" <?php echo $d === $day ? 'selected' : ''; ?>>
                                                                    <?php echo $d; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <button type="submit" name="update" class="btn btn-sm btn-outline-primary">Update</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>