<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {

  $className = $_POST['className'];
  $totalStudents = $_POST['totalStudents'];
  $boys = $_POST['boys'];
  $girls = $_POST['girls'];
  $level = $_POST['level'];

  $query = "SELECT * FROM tbladmin WHERE Id = " . $_SESSION['userId'] . "";
  $rs = $conn->query($query);
  $num = $rs->num_rows;
  $rows = $rs->fetch_assoc();
  $school = $rows['school'];
  $a = "SELECT * FROM tblclass WHERE className='$className' AND school='$school'";
  $r = $conn->query($a);
  $n = $r->num_rows;
  if ($n > 0) {

    $statusMsg = "<div class='alert alert-danger'  style='margin-right:700px;'>Class Is Exist</div>";
  } else {



    $query = mysqli_query($conn, "insert into tblclass(level,className,totalStudents,boys,girls,school) values ('$level','$className','$totalStudents','$boys','$girls','$school')");

    if ($query) {

      $statusMsg = "<div class='alert alert-success'  style='margin-right:700px;'>Created Successfully!</div>";
    } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}

//---------------------------------------EDIT-------------------------------------------------------------






//--------------------EDIT------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];

  $query = mysqli_query($conn, "select * from tblclass where Id ='$Id'");
  $z = mysqli_fetch_array($query);

  //------------UPDATE-----------------------------

  if (isset($_POST['update'])) {

    $className = $_POST['className'];
    $totalStudents = $_POST['totalStudents'];
    $boys = $_POST['boys'];
    $girls = $_POST['girls'];
    $level = $_POST["level"];

    $qu = "SELECT * FROM tbladmin WHERE Id = " . $_SESSION['userId'] . "";
    $rs = $conn->query($qu);
    $num = $rs->num_rows;
    $rows = $rs->fetch_assoc();
    $school = $rows['school'];


    $query = mysqli_query($conn, "update tblclass set level='$level',className='$className',totalStudents='$totalStudents',boys='$boys',girls='$girls',school='$school' where Id='$Id'");

    if ($query) {

      echo "<script type = \"text/javascript\">
                window.location = (\"createClass.php\")
                </script>";
    } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}


//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['Id'];

  $query = mysqli_query($conn, "DELETE FROM tblclass WHERE Id='$Id'");

  if ($query == TRUE) {

    echo "<script type = \"text/javascript\">
                window.location = (\"createClass.php\")
                </script>";
  } else {

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
  <?php include 'includes/title.php'; ?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
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

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">CRUD Classes</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Level<span class="text-danger ml-2">*</span></label>
                        <select name="level" id="" class="form-control">
                          <?php
                          $a = "SELECT * FROM levels WHERE school='$s'";
                          $ris = $conn->query($a);
                          echo "<option>--Select Level--</option>";

                          while ($row = $ris->fetch_assoc()) {
                            echo '<option value="' . $row["levelS"] . '">' . $row["levelS"] . '</option>
                              ';
                          }
                          ?>
                        </select>

                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Class Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="className" value="<?php echo $z['className']; ?>" id="exampleInputFirstName" placeholder="Class Name">
                      </div>

                      <div class="col-xl-6">
                        <label class="form-control-label">Total Students<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="totalStudents" value="<?php echo $z['totalStudents']; ?>" id="exampleInputFirstName" placeholder="Total Students">
                      </div>

                      <div class="col-xl-6">
                        <label class="form-control-label">Boys<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="boys" value="<?php echo $z['boys']; ?>" id="exampleInputFirstName" placeholder="Boys">
                      </div>

                      <div class="col-xl-6">
                        <label class="form-control-label">Girls<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="girls" value="<?php echo $z['girls']; ?>" id="exampleInputFirstName" placeholder="Girls">
                      </div>
                    </div>
                    <?php
                    if (isset($Id)) {
                    ?>
                      <button type="submit" name="update" class="btn btn-warning">Update</button>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    } else {
                    ?>
                      <button type="submit" name="save" class="btn asa">Save</button>
                    <?php
                    }
                    ?>
                  </form>
                </div>

              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-dark">All Classes</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Class Name</th>
                            <th>Total Students</th>
                            <th>Boys</th>
                            <th>Girls</th>
                            <th>Edit</th>
                            <th>Delete</th>

                          </tr>
                        </thead>

                        <tbody>

                          <?php

                          $query = "SELECT * FROM tblclass where school='$s'";
                          $rs = $conn->query($query);
                          $num = $rs->num_rows;
                          $sn = 0;
                          if ($num > 0) {
                            while ($rows = $rs->fetch_assoc()) {
                              $sn = $sn + 1;
                              echo "
                              <tr>
                                <td>" . $sn . "</td>
                                <td>" . $rows['className'] . "</td>
                                <td>" . $rows['totalStudents'] . "</td>
                                <td>" . $rows['boys'] . "</td>
                                <td>" . $rows['girls'] . "</td>
                                <td><a href='?action=edit&Id=" . $rows['Id'] . "' class='aso'><i class='fas fa-fw fa-edit aso'></i>Edit</a></td>
                                <td><a href='?action=delete&Id=" . $rows['Id'] . "' style='color: red'><i class='fas fa-fw fa-trash' style='color: red'></i>Delete</a></td>

                                </tr>";
                            }
                          } else {
                            echo
                            "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
                          }

                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--Row-->

            <!-- Documentation Link -->
            <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

          </div>
          <!---Container Fluid-->
        </div>
        <!-- Footer -->
        <?php include "Includes/footer.php"; ?>
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
      $(document).ready(function() {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
      });
    </script>
</body>

</html>