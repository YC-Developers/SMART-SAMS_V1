<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session3.php';

// Handle form submission for school registration
if (isset($_POST['save'])) {
  $schoolName = $_POST['schoolName'];
  $district = $_POST['district'];
  $type = $_POST['type'];

  $q = "SELECT * FROM location WHERE districtName = '$district'";
  $r = $conn->query($q);
  $row = $r->fetch_assoc();
  $province = $row['province'];

  $s = "SELECT * FROM schools WHERE district = '$district'";
  $a = $conn->query($s);
  $n = $a->num_rows;
  $Counter = $n;
  $Counter++;

  $code = $row['id'] . sprintf('%03d', $Counter);

  $o = "INSERT INTO schools (schoolName, province, district, code, type) VALUES ('$schoolName','$province','$district', '$code', '$type')";
  $res = mysqli_query($conn, $o);
  if ($res) {
    $statusMsg = "<div class='alert alert-success'>School Registered Successfully!</div>";
  } else {
    $statusMsg = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
  }
}

// Handle edit action
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['id'];
  $query = mysqli_query($conn, "SELECT * FROM schools WHERE id ='$Id'");
  $row = mysqli_fetch_array($query);

  if (isset($_POST['update'])) {
    $schoolName = $_POST['schoolName'];
    $district = $_POST['district'];
    $type = $_POST['type'];

    $q = "SELECT * FROM location WHERE districtName = '$district'";
    $r = $conn->query($q);
    $row = $r->fetch_assoc();
    $province = $row['province'];

    $s = "SELECT * FROM schools WHERE district = '$district'";
    $a = $conn->query($s);
    $n = $a->num_rows;
    $Counter = $n;
    $Counter++;

    $code = $row['id'] . sprintf('%03d', $Counter);

    $query = mysqli_query($conn, "UPDATE schools SET schoolName='$schoolName', district='$district', 
                  province='$province', code='$code', type='$type' WHERE id='$Id'");
    if ($query) {
      echo "<script>window.location = 'createSchool.php';</script>";
    } else {
      $statusMsg = "<div class='alert alert-danger'>An error Occurred!</div>";
    }
  }
}

// Handle delete action
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['id'];
  $query = mysqli_query($conn, "DELETE FROM schools WHERE id='$Id'");

  if ($query == TRUE) {
    echo "<script>window.location = 'createSchool.php';</script>";
  } else {
    $statusMsg = "<div class='alert alert-danger'>An error Occurred!</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="School Management">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>SMART-SAMS | School Management</title>

  <!-- Custom fonts -->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles -->
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

  <style>
    :root {
      --primary: #4e73df;
      --success: #1cc88a;
      --info: #36b9cc;
      --warning: #f6c23e;
      --danger: #e74a3b;
      --secondary: #858796;
      --light: #f8f9fc;
      --dark: #5a5c69;
    }

    .card {
      border: none;
      border-radius: 0.35rem;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      margin-bottom: 1.5rem;
    }

    .card-header {
      background-color: #f8f9fc;
      border-bottom: 1px solid #e3e6f0;
      padding: 1rem 1.35rem;
    }

    .badge-pill {
      padding: 0.5em 0.8em;
      font-size: 0.75rem;
      font-weight: 700;
    }

    .badge-tvet {
      background-color: var(--info);
      color: white;
    }

    .badge-reb {
      background-color: var(--success);
      color: white;
    }

    .action-btns .btn {
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
      margin: 0 2px;
    }

    .form-section {
      background-color: white;
      padding: 2rem;
      border-radius: 0.35rem;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
      margin-bottom: 2rem;
    }

    @media (max-width: 768px) {

      .form-row>.col,
      .form-row>[class*="col-"] {
        padding-right: 5px;
        padding-left: 5px;
      }
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

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 ">SCHOOL REGISTERATION PANNEL</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Registration</li>
            </ol>
          </div>

          <!-- Notification -->
          <?php if (!empty($statusMsg)) { ?>
            <div class="alert alert-dismissible fade show" role="alert">
              <?php echo $statusMsg; ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php } ?>

          <!-- Form Section -->
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">
                <?php echo isset($Id) ? 'Edit School' : 'Register New School'; ?>
              </h6>
            </div>
            <div class="card-body">
              <form method="post">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="schoolName">School Name</label>
                    <input type="text" class="form-control" id="schoolName" name="schoolName"
                      value="<?php echo $row['schoolName'] ?? ''; ?>" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="district">District</label>
                    <select class="form-control" id="district" name="district" required>
                      <option value="">--Select District--</option>
                      <?php
                      $qry = "SELECT DISTINCT districtName FROM location ORDER BY districtName ASC";
                      $result = $conn->query($qry);
                      if ($result->num_rows > 0) {
                        while ($districtRow = $result->fetch_assoc()) {
                          $selected = ($row['district'] ?? '') == $districtRow['districtName'] ? 'selected' : '';
                          echo '<option value="' . $districtRow['districtName'] . '" ' . $selected . '>' .
                            $districtRow['districtName'] . '</option>';
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="type">School Type</label>
                    <select class="form-control" id="type" name="type" required>
                      <option value="">--Select Type--</option>
                      <option value="TVET" <?php if (($row['type'] ?? '') == "TVET") echo 'selected'; ?>>TVET</option>
                      <option value="REB" <?php if (($row['type'] ?? '') == "REB") echo 'selected'; ?>>REB</option>
                    </select>
                  </div>
                </div>

                <?php if (isset($Id)) { ?>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="code">School Code</label>
                      <input type="text" class="form-control" id="code"
                        value="<?php echo $row['code'] ?? ''; ?>" readonly>
                    </div>
                  </div>
                <?php } ?>

                <div class="form-group mt-4">
                  <?php if (isset($Id)) { ?>
                    <button type="submit" name="update" class="btn btn-primary">
                      <i class="fas fa-save mr-1"></i> Update School
                    </button>
                    <a href="createSchool.php" class="btn btn-secondary">
                      <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                  <?php } else { ?>
                    <button type="submit" name="save" class="btn btn-success">
                      <i class="fas fa-plus mr-1"></i> Register School
                    </button>
                  <?php } ?>
                </div>
              </form>
            </div>
          </div>

          <!-- Schools Table -->
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">All Registered Schools</h6>
              <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                  aria-labelledby="dropdownMenuLink">
                  <div class="dropdown-header">Table Options:</div>
                  <a class="dropdown-item" href="#" onclick="$('#dataTable').DataTable().ajax.reload();">
                    <i class="fas fa-sync-alt fa-fw mr-1"></i> Refresh
                  </a>
                  <a class="dropdown-item" href="#" onclick="exportTableToCSV('schools.csv')">
                    <i class="fas fa-file-csv fa-fw mr-1"></i> Export to CSV
                  </a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>School Name</th>
                      <th>District</th>
                      <th>Province</th>
                      <th>School Code</th>
                      <th>Type</th>
                      <th>Date Created</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = "SELECT * FROM schools";
                    $rs = $conn->query($query);
                    $num = $rs->num_rows;
                    $sn = 0;
                    if ($num > 0) {
                      while ($rows = $rs->fetch_assoc()) {
                        $sn++;
                        echo "
                            <tr>
                              <td>$sn</td>
                              <td>{$rows['schoolName']}</td>
                              <td>{$rows['district']}</td>
                              <td>{$rows['province']}</td>
                              <td>{$rows['code']}</td>
                              <td>
                                <span class='badge badge-pill " .
                          ($rows['type'] == 'TVET' ? 'badge-tvet' : 'badge-reb') . "'>
                                  {$rows['type']}
                                </span>
                              </td>
                              <td>" . date('M j, Y', strtotime($rows['date'])) . "</td>
                              <td class='action-btns'>
                                <a href='?action=edit&id={$rows['id']}' class='btn btn-primary btn-sm'>
                                  <i class='fas fa-edit'></i>
                                </a>
                                <a href='?action=delete&id={$rows['id']}' class='btn btn-danger btn-sm' 
                                   onclick=\"return confirm('Are you sure you want to delete this school?');\">
                                  <i class='fas fa-trash'></i>
                                </a>
                              </td>
                            </tr>";
                      }
                    } else {
                      echo "<tr><td colspan='8' class='text-center'>No schools found</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
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

  <!-- JavaScript Libraries -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page Level Custom Scripts -->
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      $('#dataTable').DataTable({
        "pageLength": 10,
        "responsive": true,
        "language": {
          "lengthMenu": "Show _MENU_ schools per page",
          "zeroRecords": "No schools found",
          "info": "Showing _START_ to _END_ of _TOTAL_ schools",
          "infoEmpty": "No schools available",
          "infoFiltered": "(filtered from _MAX_ total schools)",
          "search": "Search:",
          "paginate": {
            "first": "First",
            "last": "Last",
            "next": "Next",
            "previous": "Previous"
          }
        }
      });

      // Update province when district changes
      $('#district').change(function() {
        var district = $(this).val();
        if (district) {
          $.ajax({
            url: 'getProvince.php',
            type: 'POST',
            data: {
              district: district
            },
            success: function(response) {
              $('#province').val(response);
            }
          });
        } else {
          $('#province').val('');
        }
      });
    });

    function exportTableToCSV(filename) {
      var csv = [];
      var rows = document.querySelectorAll("#dataTable tr");

      for (var i = 0; i < rows.length; i++) {
        var row = [],
          cols = rows[i].querySelectorAll("td, th");

        for (var j = 0; j < cols.length - 1; j++) { // Skip actions column
          row.push(cols[j].innerText);
        }

        csv.push(row.join(","));
      }

      // Download CSV file
      var csvFile = new Blob([csv.join("\n")], {
        type: "text/csv"
      });
      var downloadLink = document.createElement("a");
      downloadLink.download = filename;
      downloadLink.href = window.URL.createObjectURL(csvFile);
      downloadLink.style.display = "none";
      document.body.appendChild(downloadLink);
      downloadLink.click();
    }
  </script>
</body>

</html>