<?php
// Enable error reporting
error_reporting(0);
include '../Includes/dbcon.php'; // Database connection
include '../Includes/session.php'; // Session handling

$statusMsg = "";

// Handle form submission for saving a new teacher
if (isset($_POST['save'])) {
    $Name = $_POST['levelName'];
    $arm = $_POST['levelS'];

        $qu = mysqli_query($conn, "SELECT * FROM levels WHERE levelName ='$Name'");
        $ret = mysqli_fetch_array($qu);

        if ($ret > 0) {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Level Already exists!</div>";
        } else {
            // Insert new teacher
            $qm = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
            $rs = $conn->query($qm);
            $num = $rs->num_rows;
            $rows = $rs->fetch_assoc();
            $school = $rows['school'];
      
      
            $query = mysqli_query($conn, "INSERT INTO levels (levelName, levelS, school) 
                VALUES ('$Name', '$arm', '$school')");

            if ($query) {
                $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Level created successfully!</div>";
            } else {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred!</div>";
            }
        }
    }



if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];
  $query = mysqli_query($conn, "SELECT * FROM levels WHERE id ='$Id'");
  $row = mysqli_fetch_array($query);

  // Pre-fill values
  $editName = $row['levelName'];
  $editArm = $row['levelS'];

    if (isset($_POST['update'])) {
        $Name = $_POST['levelName'];
        $arm = $_POST['levelS'];

        $qo = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
        $rs = $conn->query($qo);
        $num = $rs->num_rows;
        $rows = $rs->fetch_assoc();
        $school = $rows['school'];


        $query = mysqli_query($conn, "UPDATE levels SET levelName='$Name', levelS='$arm', school='$school' WHERE id='$Id'");

        if ($query) {
            echo "<script type='text/javascript'>
                  window.location = ('createLevels.php');
                  </script>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred!</div>";
        }
    }
}

// Delete teacher
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $Id = $_GET['Id'];

    $query = mysqli_query($conn, "DELETE FROM levels WHERE id='$Id'");

    if ($query) {
        echo "<script type='text/javascript'>
              window.location = ('createLevels.php');
              </script>";
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred!</div>";
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
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Levels</h1>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Create a New Level</h6>
                        <?php echo $statusMsg; ?>
                    </div>
                    <div class="card-body">
    <form method="post">
        <div class="form-group row">
            <div class="col-md-6">
                <label>Level Name</label>
                <input type="text" class="form-control" name="levelName" 
                    placeholder="Enter Level Name" 
                    value="<?php echo isset($editName) ? $editName : ''; ?>" required>
            </div>
            <div class="col-md-6">
                <label>Level Arm</label>
                <input type="text" class="form-control" name="levelS" 
                    placeholder="Enter Level Arm" 
                    value="<?php echo isset($editArm) ? $editArm : ''; ?>" required>
            </div>
            </div>
        <!-- Button text changes based on action -->
        <?php if (isset($_GET['action']) && $_GET['action'] == "edit"): ?>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        <?php else: ?>
            <button type="submit" name="save" class="btn btn-primary">Save</button>
        <?php endif; ?>
    </form>
</div>

                </div>

                <!-- Display Teachers -->
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">All Teachers</h6>
                    </div>
                    <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Level Name</th>
                                    <th>Level Arm</th>
                                    <th>Edit</th>
                                    <th>Delete</th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch all teachers for display
$query = "SELECT * FROM levels WHERE school='$s'
";
$rs = $conn->query($query);
$num= mysqli_num_rows($rs);
$sn = 1;
while ($level = $rs->fetch_assoc()) {


                                if ($num> 0){
                                    
                                    
                                        echo "<tr>
                                            <td>".$sn++."</td>
                                            <td>".$level['levelName']."</td>
                                            <td>".$level['levelS']."</td>
                                            <td><a href='?action=edit&Id=".$level['id']."' class='aso'><i class='fas fa-fw fa-edit aso'></i></a></td>
                                            <td><a href='?action=delete&Id=".$level['id']."' style='color: red'><i class='fas fa-fw fa-trash' style='color: red'></i></a></td>
            
                                        </tr>";
                                    }
                                else {
                                    echo "<tr><td colspan='8' class='text-center'>No Levels Found</td></tr>";
                                }
                            }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
      <!-- Footer -->
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
