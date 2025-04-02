<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Directory to store images
$uploadDir = "student_images/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle single image upload/update
if (isset($_POST['upload']) && isset($_FILES['student_image'])) {
    $studentId = $_POST['student_id'];
    $file = $_FILES['student_image'];
    $fileName = $studentId . "_" . time() . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $sql = "UPDATE tblstudents SET image_path = '$fileName' WHERE Id = $studentId";
        mysqli_query($conn, $sql);
        $message = "Image uploaded successfully!";
        $msgType = "success";
    } else {
        $message = "Failed to upload image.";
        $msgType = "danger";
    }
}

// Handle image deletion
if (isset($_GET['delete'])) {
    $studentId = $_GET['delete'];
    $classId = $_GET['class'] ?? '';
    $sql = "SELECT image_path FROM tblstudents WHERE Id = $studentId";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['image_path']) {
        unlink($uploadDir . $row['image_path']);
        $sql = "UPDATE tblstudents SET image_path = NULL WHERE Id = $studentId";
        mysqli_query($conn, $sql);
        $message = "Image deleted successfully!";
        $msgType = "success";
    }
    header("Location: ?class=$classId");
    exit;
}

// Handle bulk image upload
if (isset($_POST['bulk_upload']) && isset($_FILES['bulk_images'])) {
    $classId = $_POST['class_id'];
    $files = $_FILES['bulk_images'];
    $successCount = 0;
    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] == 0) {
            $studentId = pathinfo($files['name'][$i], PATHINFO_FILENAME);
            $fileName = $studentId . "_" . time() . "." . pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $targetPath = $uploadDir . $fileName;

            $sql = "SELECT Id FROM tblstudents WHERE Id = '$studentId' AND classId = '$classId'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
                    $sql = "UPDATE tblstudents SET image_path = '$fileName' WHERE Id = '$studentId'";
                    mysqli_query($conn, $sql);
                    $successCount++;
                }
            }
        }
    }
    $message = "Bulk upload completed! $successCount images uploaded successfully.";
    $msgType = "success";
}

// Add image_path column if it doesn't exist
$sql = "ALTER TABLE tblstudents ADD COLUMN IF NOT EXISTS image_path VARCHAR(255)";
mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Student Image Management</title>
  <link href="img/logo/attnlg.jpg" rel="icon">
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
    <style>
        .student-image {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>
        <!-- Topbar -->
    <div class="container mt-4">
        <h1 class="mb-4">Students Image Management</h1>

        <?php if (isset($message)) { ?>
            <div class="alert alert-<?php echo $msgType; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <!-- Class Selection -->
        <div class="card">
            <div class="card-header">Select Class</div>
            <div class="card-body">
                <form method="get">
                    <div class="row">
                        <div class="col-md-6">
                            <select name="class" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Select a Class --</option>
                                <?php
                                $sql = "SELECT Id, className FROM tblclass WHERE school='$s'";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $selected = (isset($_GET['class']) && $_GET['class'] == $row['Id']) ? 'selected' : '';
                                    echo "<option value='{$row['Id']}' $selected>{$row['className']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($_GET['class']) && !empty($_GET['class'])): 
            $selectedClass = $_GET['class'];
        ?>
            <!-- Single Image Upload -->
            <div class="card">
                <div class="card-header">Upload/Update Student Image</div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <select name="student_id" class="form-control" required>
                                    <option value="">Select Student</option>
                                    <?php
                                    $sql = "SELECT Id, name FROM tblstudents WHERE classId = '$selectedClass' ORDER BY name";
                                    $result = mysqli_query($conn, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='{$row['Id']}'>{$row['name']} ({$row['Id']})</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="file" name="student_image" class="form-control" accept="image/*" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="upload" class="btn btn-primary">Upload/Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Image Upload -->
            <div class="card">
                <div class="card-header">Bulk Image Upload</div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="class_id" value="<?php echo $selectedClass; ?>">
                        <div class="mb-3">
                            <input type="file" name="bulk_images[]" class="form-control" multiple accept="image/*" required>
                            <small class="form-text text-muted">Note: Filename should match student ID (e.g., 73.jpg)</small>
                        </div>
                        <button type="submit" name="bulk_upload" class="btn btn-success">Upload All</button>
                    </form>
                </div>
            </div>

            <!-- Students List -->
            <div class="card">
                <div class="card-header">
                    Students in <?php 
                        $sql = "SELECT className FROM tblclass WHERE Id = '$selectedClass'";
                        $result = mysqli_query($conn, $sql);
                        echo mysqli_fetch_assoc($result)['className'];
                    ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Admission No</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tblstudents WHERE classId = '$selectedClass' ORDER BY name";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>{$row['Id']}</td>";
                                    echo "<td>{$row['name']}</td>";
                                    echo "<td>{$row['gender']}</td>";
                                    echo "<td>{$row['admissionNumber']}</td>";
                                    echo "<td>";
                                    if ($row['image_path']) {
                                        echo "<a href='student_images/".$row['image_path']."' target='_black'><img src='{$uploadDir}{$row['image_path']}' class='student-image rounded' alt='Student Image'></a>";
                                    } else {
                                        echo "<span class='badge bg-success'>No image</span>";
                                    }
                                    echo "</td>";
                                    echo "<td>";
                                    if ($row['image_path']) {
                                        echo "<a href='?class=$selectedClass&delete={$row['Id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this image?\")'>Delete</a>";
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    </body>
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/ruang-admin.min.js"></script>
</html>

<?php mysqli_close($conn); ?>