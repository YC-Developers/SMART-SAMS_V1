<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session3.php';

// Status message for notifications
$statusMsg = "";

// Handle form submission for insertion
if (isset($_POST['save'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type'];
    $school = $_POST['school'];

    $query = "INSERT INTO tbladmin (firstName, lastName, username, password, type, school) 
              VALUES ('$firstName', '$lastName', '$username', '$password', '$type', '$school')";
    if (mysqli_query($conn, $query)) {
        $statusMsg = "<div class='alert alert-success'>Admin Added Successfully!</div>";
    } else {
        $statusMsg = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// Handle edit action
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM tbladmin WHERE id ='$Id'");
    $row = mysqli_fetch_array($query);

    if (isset($_POST['update'])) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $type = $_POST['type'];
        $school = $_POST['school'];

        $updateQuery = "UPDATE tbladmin 
                        SET firstName='$firstName', lastName='$lastName', username='$username', 
                            password='$password', type='$type', school='$school' 
                        WHERE Id='$Id'";
        if (mysqli_query($conn, $updateQuery)) {
            echo "<script>window.location = 'cradmin.php';</script>";
        } else {
            $statusMsg = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}



//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    
    $Id= $_GET['id'];

    $query = mysqli_query($conn,"DELETE FROM tbladmin WHERE Id='$Id'");

    if ($query == TRUE) {

        echo "<script type = \"text/javascript\">
        window.location = (\"cradmin.php\")
        </script>";
    }
    else{

        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
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
<?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

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


<div class="container mt-4">
    <!-- Notification -->
    <?php echo $statusMsg; ?>

    <!-- Form for Insertion and Update -->
    <form method="post">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" class="form-control" name="firstName" value="<?php echo $row['firstName'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" class="form-control" name="lastName" value="<?php echo $row['lastName'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo $row['username'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password" value="<?php echo $row['password'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Type</label>
            <select class="form-control" name="type" required>
                <option value="">--Select Role--</option>
                <option value="displine" <?php if (($row['type'] ?? '') == "displine") echo 'selected'; ?>>Dean of Discipline</option>
                <option value="study" <?php if (($row['type'] ?? '') == "study") echo 'selected'; ?>>Dean of Study</option>
            </select>
        </div>
        <div class="form-group">
            <label>Select School</label>
            <select class="form-control" name="school" required>
                <option value="">--Select School--</option>
                <?php
                $schoolQuery = "SELECT * FROM schools ORDER BY schoolName ASC";
                $schoolResult = $conn->query($schoolQuery);
                while ($schoolRow = $schoolResult->fetch_assoc()) {
                    $selected = ($row['school'] ?? '') == $schoolRow['schoolName'] ? 'selected' : '';
                    echo "<option value='".$schoolRow['schoolName']."' $selected>".$schoolRow['schoolName']."</option>";
                }
                ?>
            </select>
        </div>

        <!-- Buttons -->
        <?php if (isset($Id)) { ?>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        <?php } else { ?>
            <button type="submit" name="save" class="btn btn-success">Save</button>
        <?php } ?>
    </form>

    <!-- Table for Display -->
    <div class="mt-5">
        <h5>All School Admins</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Type</th>
                    <th>School</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetchQuery = "SELECT * FROM tbladmin";
                $result = $conn->query($fetchQuery);
                if ($result->num_rows > 0) {
                    $sn = 0;
                    while ($data = $result->fetch_assoc()) {
                        $sn++;
                        echo "
                        <tr>
                            <td>$sn</td>
                            <td>{$data['firstName']} {$data['lastName']}</td>
                            <td>{$data['username']}</td>
                            <td>{$data['password']}</td>
                            <td>{$data['type']}</td>
                            <td>{$data['school']}</td>
                            <td><a href='?action=edit&id=".$data['Id']."' class='btn btn-primary btn-sm'>Edit</a></td>
                            <td><a href='?action=delete&id=".$data['Id']."' class='btn btn-danger btn-sm'>Delete</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
   <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>
</html>
