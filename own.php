
<?php 
include 'Includes/dbcon.php';
session_start();
   
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
  <title>AMS - Login</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

</head>
<Style>
      .asa{
    color: white;
    background: linear-gradient(130deg, #231a6f, #0f054c);
  }
  .asa:hover{
    color: white;
    background: linear-gradient(210deg, #231a6f, #0f054c);
  }

</Style>

<body class="bg-gradient-login" style="">
  <!-- Login Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                <h5 align="center">A    M    S</h5>
                <h6 style="font-size:8px; margin-left: 148px; margin-top: -10px;">YC Soft</h6>
                  <div class="text-center">
                    <img src="img/logo/attnlg.jpg" style="width:100px;height:100px">
                    <br><br>
                    <h1 class="h4 text-gray-900 mb-4">OWN Login Panel</h1>
                  </div>
                  <form class="user" method="Post" action="">
                    <div class="form-group">
                      <input type="text" name="code" required class="form-control" id="exampleInputPassword" placeholder="COMPANY CODE">
                    </div>
                    <div class="form-group">
                        <input type="submit"  class="btn btn-success btn-block" value="SUBMIT" name="login" />
                    </div>
                     </form>
                     <?php
                       if(isset($_POST['login'])){

                        $code = $_POST['code'];

                        $query = "SELECT * FROM own WHERE code = '$code'";
                        $rs = $conn->query($query);
                        $num = $rs->num_rows;
                        $rows = $rs->fetch_assoc();
                  
                        if($num > 0){

                          $_SESSION['code'] = $rows['code'];
                          
                          echo "<script type = \"text/javascript\">
                          window.location = (\"own/index.php\")
                          </script>";
                        }
                  
                        else{
                  
                          echo "<div class='alert alert-danger' role='alert'>
                          Invalid REGISTRATION NUMBER!
                          </div>";
                  
                        }
                  
                       }
                     ?>



                    <!-- <hr>
                    <a href="index.html" class="btn btn-google btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="index.html" class="btn btn-facebook btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a> -->

                
                  <div class="text-center">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Login Content -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

</html>