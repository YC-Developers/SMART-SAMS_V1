
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
<div class="cont">
  
                  <!-- Form Basic -->
                  <style>
                    .atvir{
                      margin-left: 30px;
                      margin-top: 80px;
                    }
                  </style>
<div class="atvir">

        <script>
    const urlParams = new URLSearchParams(window.location.search);
    const decodedText = urlParams.get("data");
    console.log(decodedText);

    // Update the URL with the decodedText value
    const baseUrl = window.location.origin + window.location.pathname;
    window.history.pushState({}, "", baseUrl + "?data=" + encodeURIComponent(decodedText));
</script>

<?php
$p = $_GET['data'];  // Example: "9002L4 SOFTWARE DEVELOPMENT B"

// Use regex to remove leading numbers
$cleaned = preg_replace('/^\d+/', '', $p);

// Trim to remove any unwanted spaces
$result = trim($cleaned);

//echo $result; // Output: "L4 SOFTWARE DEVELOPMENT B"

$sql = "SELECT * FROM tblclass WHERE className='$result'";
$res = $conn->query($sql);

  while ($row = $res->fetch_assoc())
    {
echo "
  <h2 >Class Name:".$row['className']."</h2>
    <h2> Total Student:".$row['totalStudents']."</h2>
    <span><h3>Boys:".$row['boys']."</h3></span><span><h3>Girls:".$row['girls']."</h3></span></span>              
<form action='atdetails.php' method='post'>
  <table>
  <tr>
  <td></td>
  <td><input type='hidden' name='className' value='".$row['className']."' </td>
  </tr>
    <tr>
      <td>Prep Mode:</td>
      <td><select name='event' required id='' class='form-control'>
      <option value=''>--Select Event--</option>
      <option value='morning'>Morning Prep</option>    
      <option value='evening'>Evening Prep</option>    
    </select>
    </td>
    </tr>
<tr>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
    </tr>

<tr>
    <td>Present Boys:</td>
    <td><input type='number' name='presentboys' class='form-control'>
    
</td>
</tr>
<tr>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
    </tr>

<tr>
    <td>Absent Boys:</td>
    <td><input type='number' name='absentboys' class='form-control'>
</td>
</tr>
<tr>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
    </tr>

<tr>
    <td>Present Girls:</td>
    <td><input type='number' name='presentgirls' class='form-control'>
</td>
</tr>
<tr>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
    </tr>

<tr>
    <td>Absent Girls:</td>
    <td><input type='number' name='absentgirls' class='form-control'>
</td>
</tr>

<tr>
      <td></td>
      <td></td>
    </tr>
    
    <tr>
      <td></td>
      <td><input type='submit' name='take' class='btn btn asa' value='Take Attandance'></td>
    </tr>

  </table>
</form>";
    }
?>
</div><br><br>

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