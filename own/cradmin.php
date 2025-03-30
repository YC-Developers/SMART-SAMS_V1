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

// Handle delete action
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $Id = $_GET['id'];
    $query = mysqli_query($conn, "DELETE FROM tbladmin WHERE Id='$Id'");

    if ($query == TRUE) {
        echo "<script type = \"text/javascript\">
        window.location = (\"cradmin.php\")
        </script>";
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
    <meta name="description" content="Admin Management">
    <meta name="author" content="">
    <link href="img/logo/attnlg.jpg" rel="icon">
    <title>SMART-SAMS | Admin Management</title>

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

        .form-section {
            background-color: white;
            padding: 2rem;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 2rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .action-btns .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            margin: 0 2px;
        }

        .badge-pill {
            padding: 0.5em 0.8em;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .badge-displine {
            background-color: var(--info);
            color: white;
        }

        .badge-study {
            background-color: var(--success);
            color: white;
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
                        <h1 class="h3 mb-0 text-gray-800">ADMIN MANAGEMNT PANNEL</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Admin Management</li>
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
                                <?php echo isset($Id) ? 'Edit Admin' : 'Add New Admin'; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="firstName">First Name</label>
                                        <input type="text" class="form-control" id="firstName" name="firstName"
                                            value="<?php echo $row['firstName'] ?? ''; ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="lastName">Last Name</label>
                                        <input type="text" class="form-control" id="lastName" name="lastName"
                                            value="<?php echo $row['lastName'] ?? ''; ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            value="<?php echo $row['username'] ?? ''; ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            value="<?php echo $row['password'] ?? ''; ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="type">Role</label>
                                        <select class="form-control" id="type" name="type" required>
                                            <option value="">--Select Role--</option>
                                            <option value="displine" <?php if (($row['type'] ?? '') == "displine") echo 'selected'; ?>>Dean of Discipline</option>
                                            <option value="study" <?php if (($row['type'] ?? '') == "study") echo 'selected'; ?>>Dean of Study</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="school">School</label>
                                        <select class="form-control" id="school" name="school" required>
                                            <option value="">--Select School--</option>
                                            <?php
                                            $schoolQuery = "SELECT * FROM schools ORDER BY schoolName ASC";
                                            $schoolResult = $conn->query($schoolQuery);
                                            while ($schoolRow = $schoolResult->fetch_assoc()) {
                                                $selected = ($row['school'] ?? '') == $schoolRow['schoolName'] ? 'selected' : '';
                                                echo "<option value='" . $schoolRow['schoolName'] . "' $selected>" . $schoolRow['schoolName'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <?php if (isset($Id)) { ?>
                                        <button type="submit" name="update" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Update Admin
                                        </button>
                                        <a href="cradmin.php" class="btn btn-secondary">
                                            <i class="fas fa-times mr-1"></i> Cancel
                                        </a>
                                    <?php } else { ?>
                                        <button type="submit" name="save" class="btn btn-success">
                                            <i class="fas fa-plus mr-1"></i> Add Admin
                                        </button>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Admins Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">All School Admins</h6>
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
                                    <a class="dropdown-item" href="#" onclick="exportTableToCSV('admins.csv')">
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
                                            <th>Full Name</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th>School</th>
                                            <th>Actions</th>
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
                              <td>
                                <span class='badge badge-pill " .
                                                    ($data['type'] == 'displine' ? 'badge-displine' : 'badge-study') . "'>
                                  " . ucfirst($data['type']) . "
                                </span>
                              </td>
                              <td>{$data['school']}</td>
                              <td class='action-btns'>
                                <a href='?action=edit&id={$data['Id']}' class='btn btn-primary btn-sm'>
                                  <i class='fas fa-edit'></i>
                                </a>
                                <a href='?action=delete&id={$data['Id']}' class='btn btn-danger btn-sm' 
                                   onclick=\"return confirm('Are you sure you want to delete this admin?');\">
                                  <i class='fas fa-trash'></i>
                                </a>
                              </td>
                            </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>No admins found</td></tr>";
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
            $('#dataTable').DataTable({
                "pageLength": 10,
                "responsive": true,
                "language": {
                    "lengthMenu": "Show _MENU_ admins per page",
                    "zeroRecords": "No admins found",
                    "info": "Showing _START_ to _END_ of _TOTAL_ admins",
                    "infoEmpty": "No admins available",
                    "infoFiltered": "(filtered from _MAX_ total admins)",
                    "search": "Search:",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
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