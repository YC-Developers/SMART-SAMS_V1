<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Class Management Dashboard">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>Class Management | Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <style>
    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .table-container {
      padding: 20px;
    }

    .page-title {
      color: #2c3e50;
      font-weight: 600;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #f8f9fa;
    }

    .btn-asa {
      background-color: #4e73df;
      color: white;
      border-radius: 5px;
      padding: 5px 15px;
      transition: all 0.3s;
    }

    .btn-asa:hover {
      background-color: #2e59d9;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .thead-light {
      background-color: #f8f9fa;
    }

    .table th {
      font-weight: 600;
      color: #495057;
    }

    .alert {
      margin: 20px;
    }
  </style>

  <script>
    function classArmDropdown(str) {
      if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
      } else {
        if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest();
        } else {
          // code for IE6, IE5
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

        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">All Classes</h1>
          </div>

          <!-- Content Row -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Class List</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTableHover" width="100%" cellspacing="0">
                      <thead class="thead-light">
                        <tr>
                          <th width="5%">#</th>
                          <th>Class Name</th>
                          <th width="15%">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $sql = "SELECT * FROM tblclass WHERE school='$s'";
                        $rs = $conn->query($sql);
                        $num = $rs->num_rows;
                        $sn = 0;

                        if ($num > 0) {
                          while ($rows = $rs->fetch_assoc()) {
                            $sn = $sn + 1;
                            echo "
                              <tr>
                                <td>" . $sn . "</td>
                                <td>" . $rows['className'] . "</td>
                                <td>
                                  <a href='view.php?Id=" . $rows['Id'] . "' class='btn btn-sm btn-asa'>
                                    <i class='fas fa-eye'></i> View
                                  </a>
                                </td>
                              </tr>";
                          }
                        } else {
                          echo "<tr><td colspan='3' class='text-center py-4'>
                            <div class='alert alert-info' role='alert'>
                              No classes found. Please add classes to view them here.
                            </div>
                          </td></tr>";
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
      $('#dataTableHover').DataTable({
        "language": {
          "emptyTable": "No classes available",
          "info": "Showing _START_ to _END_ of _TOTAL_ classes",
          "infoEmpty": "Showing 0 to 0 of 0 classes",
          "infoFiltered": "(filtered from _MAX_ total classes)",
          "lengthMenu": "Show _MENU_ classes per page",
          "search": "Search:",
          "zeroRecords": "No matching classes found"
        },
        "columnDefs": [{
          "orderable": false,
          "targets": [2]
        }]
      });
    });
  </script>
</body>

</html>