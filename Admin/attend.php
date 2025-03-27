<?php
include '../includes/dbcon.php';
include '../Includes/session.php'; 

if (isset($_POST['check'])) {
    $absentees = $_POST['check']; // Selected student IDs
    $mode = $_POST['mode'];
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
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">


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
        xmlhttp.open("GET","ajaxClassArms2.php?cid="+str,true);
        xmlhttp.send();
    }
}
</script>
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
        <br><br><br><br>
       <center>
    <form action="save_absence.php" method="post">
        <input type="hidden" name="mode" value="<?php echo $mode; ?>">
        <h3>Provide Reasons for Each Absent Student:</h3>
                <table width="80%" class="table table-hover table-bordered" id="dataTableHover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Reason for Absence</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php
                    foreach ($absentees as $key => $absenteeId) {
                        // Fetch student details
                        $query = "SELECT name FROM tblstudents WHERE Id = '$absenteeId'";
                        $result = $conn->query($query);
                        $student = $result->fetch_assoc();
                        ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo $student['name']; ?></td>
                            <td>
                                <input type="hidden" name="check[]" value="<?php echo $absenteeId; ?>">
                                <select name="reason[]" class="form-control" required>
                                    <option value="" disabled selected>Select Reason</option>
                                    <option value="Sick">Sick</option>
                                    <option value="Absent">Absent</option>
                                    <option value="Went Home">Went Home</option>
                                </select>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        
        <button type="submit" name="save" class="btn btn-success">Save Absences</button>
    </form>
    </center>
<?php
}
?>

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
