<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Include PhpSpreadsheet autoloader (assumes Composer installation)
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
  <style>
    .btn-template {
      background-color: #4e73df;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 5px;
      font-weight: 600;
      margin-right: 10px;
    }
    .btn-template:hover {
      background-color: #2e59d9;
      color: white;
    }
  </style>

  <script>
    function classArmDropdown(str) {
      if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
      } else {
        if (window.XMLHttpRequest) {
          xmlhttp = new XMLHttpRequest();
        } else {
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET", "ajaxClassArms2.php?cid=" + str, true);
        xmlhttp.send();
      }
    }
  </script>
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
            <h1 class="h3 mb-0 text-gray-800">Create Students</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Students</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="container">
                <h1>Import Excel File</h1>
                <form method="POST" action="uploadexcel2.php" enctype="multipart/form-data">
                  <div class="form-group row mb-3">
                    <label>Choose File</label>
                    <input type="file" name="uploadFile" class="form-control" accept=".xlsx, .xls" />
                  </div>
                  <div class="form-group row mb-3">
                    <div class="col-xl-6">
                      <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                      <?php
                      $stmt = $conn->prepare("SELECT school FROM tbladmin WHERE Id = ?");
                      $stmt->bind_param("i", $_SESSION['userId']);
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $row = $result->fetch_assoc();
                      $school = $row['school'] ?? null;
                      $stmt->close();

                      if ($school) {
                        $stmt = $conn->prepare("SELECT Id, className FROM tblclass WHERE school = ? ORDER BY className ASC");
                        $stmt->bind_param("s", $school);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                          echo '<select required name="class" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                          echo '<option value="">--Select Class--</option>';
                          while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['Id'] . '">' . htmlspecialchars($row['className']) . '</option>';
                          }
                          echo '</select>';
                        } else {
                          echo '<p class="text-danger">No classes found for this school.</p>';
                        }
                        $stmt->close();
                      } else {
                        echo '<p class="text-danger">School not found for this user.</p>';
                      }
                      ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <a href="download_template.php" class="btn btn-template btn-sm shadow-sm">
                      <i class="fas fa-download mr-2"></i>Download Template
                    </a>
                    <button type="submit" name="submit" class="btn btn-primary btn-sm shadow-sm">
                      <i class="fas fa-upload mr-2"></i>Import
                    </button>
                  </div>
                </form>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-dark">All Students</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Admission No</th>
                            <th>Class</th>
                            <th>Class Arm</th>
                            <th>Date Created</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $stmt = $conn->prepare("SELECT school FROM tbladmin WHERE Id = ?");
                          $stmt->bind_param("i", $_SESSION['userId']);
                          $stmt->execute();
                          $result = $stmt->get_result();
                          $row = $result->fetch_assoc();
                          $school = $row['school'] ?? null;
                          $stmt->close();

                          if ($school) {
                            $query = "
                              SELECT 
                                tblstudents.Id,
                                tblclass.className,
                                tblclassarms.classArmName,
                                tblclassarms.Id AS classArmId,
                                tblstudents.name,
                                tblstudents.gender,
                                tblstudents.admissionNumber,
                                tblstudents.dateCreated
                              FROM tblstudents
                              INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                              INNER JOIN tblclassarms ON tblclassarms.Id = tblstudents.classArmId
                              WHERE tblclass.school = ?
                            ";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("s", $school);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $sn = 0;
                            if ($result->num_rows > 0) {
                              while ($row = $result->fetch_assoc()) {
                                $sn++;
                                echo "
                                  <tr>
                                    <td>$sn</td>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td>" . htmlspecialchars($row['gender']) . "</td>
                                    <td>" . htmlspecialchars($row['admissionNumber']) . "</td>
                                    <td>" . htmlspecialchars($row['className']) . "</td>
                                    <td>" . htmlspecialchars($row['classArmName']) . "</td>
                                    <td>" . htmlspecialchars($row['dateCreated']) . "</td>
                                  </tr>";
                              }
                            } else {
                              echo "
                                <tr>
                                  <td colspan='7'>
                                    <div class='alert alert-danger' role='alert'>
                                      No Record Found!
                                    </div>
                                  </td>
                                </tr>";
                            }
                            $stmt->close();
                          } else {
                            echo "
                              <tr>
                                <td colspan='7'>
                                  <div class='alert alert-danger' role='alert'>
                                    School not found for this user!
                                  </div>
                                </td>
                              </tr>";
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
        $('#dataTable').DataTable();
        $('#dataTableHover').DataTable();
      });
    </script>
</body>

</html>