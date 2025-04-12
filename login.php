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
  <title>SMART-SAMS | Login</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #0f054c 0%, #231a6f 100%);
      min-height: 100vh;
      overflow: scroll;
    }

    .bg-gradient-login {
      position: relative;
    }

    .floating-bg {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      z-index: 0;
    }

    .floating-element {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 15s infinite ease-in-out;
    }

    @keyframes float {
      0%, 100% { transform: translate(0, 0) rotate(0deg); }
      25% { transform: translate(10px, 10px) rotate(90deg); }
      50% { transform: translate(0, 20px) rotate(180deg); }
      75% { transform: translate(-10px, 10px) rotate(270deg); }
    }

    .container-login {
      position: relative;
      z-index: 1;
      padding: 20px;
    }

    .card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: none;
      border-radius: 15px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      transform-style: preserve-3d;
      transition: transform 0.3s ease;
      max-width: 400px;
      margin: 0 auto;
    }

    .card:hover {
      transform: translateY(-5px) rotateX(2deg);
    }

    .login-form {
      padding: 1.5rem;
    }

    .login-form h5 {
      font-size: 1.8rem;
      font-weight: 700;
      background: linear-gradient(45deg, #231a6f, #0f054c);
      --webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.5rem;
      animation: fadeInDown 1s ease;
    }

    .login-form h6 {
      font-size: 0.75rem;
      color: #666;
      margin-bottom: 1.5rem;
      animation: fadeInUp 1s ease;
    }

    .login-form img {
      width: 100px;
      height: 100px;
      transition: transform 0.3s ease;
    }

    .login-form img:hover {
      transform: scale(1.05);
    }

    .form-control {
      border-radius: 8px;
      border: 1px solid rgba(0, 0, 0, 0.1);
      padding: 10px 15px;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      height: auto;
      background-color: rgba(255, 255, 255, 0.9);
    }

    .form-control:focus {
      box-shadow: 0 0 0 3px rgba(35, 26, 111, 0.2);
      transform: translateY(-2px);
      background-color: #fff;
    }

    .form-group {
      margin-bottom: 1rem;
      position: relative;
    }

    .btn.asa {
      background: linear-gradient(45deg, #231a6f, #0f054c);
      border: none;
      padding: 10px 25px;
      border-radius: 8px;
      font-weight: 600;
      letter-spacing: 1px;
      transition: all 0.3s ease;
      font-size: 0.9rem;
      position: relative;
      overflow: hidden;
    }

    .btn.asa:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(35, 26, 111, 0.3);
    }

    .btn.asa:active {
      transform: translateY(-1px);
    }

    .btn.aso {
      background: white;
      border: 2px solid #231a6f;
      color: #231a6f;
      padding: 10px 25px;
      border-radius: 8px;
      font-weight: 600;
      letter-spacing: 1px;
      transition: all 0.3s ease;
      font-size: 0.9rem;
    }

    .btn.aso:hover {
      background: linear-gradient(45deg, #231a6f, #0f054c);
      color: white;
      transform: translateY(-3px);
    }

    .btn.aso:active {
      transform: translateY(-1px);
    }

    .custom-control {
      margin-top: 0.5rem;
      padding-left: 1.5rem;
    }

    .custom-control-label {
      font-size: 0.8rem;
      padding-top: 2px;
      cursor: pointer;
    }

    .custom-control-input:focus ~ .custom-control-label::before {
      box-shadow: 0 0 0 3px rgba(35, 26, 111, 0.2);
    }

    .custom-checkbox .custom-control-label::before {
      border-radius: 4px;
      border: 2px solid #231a6f;
    }

    .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
      background-color: #231a6f;
      border-color: #231a6f;
    }

    .alert {
      border-radius: 8px;
      padding: 0.75rem 1.25rem;
      margin-bottom: 1rem;
      border: none;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .alert-danger {
      background-color: #fff3f3;
      color: #dc3545;
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Custom Select Styling */
    select.form-control {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23231a6f' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 15px center;
      background-size: 12px;
      padding-right: 35px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    select.form-control:focus {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%230f054c' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    }

    select.form-control option {
      padding: 10px 15px;
      background-color: white;
      color: #231a6f;
    }

    select.form-control option:checked {
      background-color: rgba(35, 26, 111, 0.1);
      font-weight: 600;
    }

    select.form-control option:hover {
      background-color: rgba(35, 26, 111, 0.05);
    }

    /* Remove default select styling in Firefox */
    select.form-control::-moz-focus-inner {
      border: 0;
    }

    /* Remove default select styling in IE */
    select.form-control::-ms-expand {
      display: none;
    }

    /* Custom scrollbar for select dropdown */
    select.form-control option::-webkit-scrollbar {
      width: 8px;
    }

    select.form-control option::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
    }

    select.form-control option::-webkit-scrollbar-thumb {
      background: #231a6f;
      border-radius: 4px;
    }

    select.form-control option::-webkit-scrollbar-thumb:hover {
      background: #0f054c;
    }
  </style>

</head>

<body class="bg-gradient-login">
  <div class="floating-bg">
    <div class="floating-element" style="width: 200px; height: 200px; top: 10%; left: 10%;"></div>
    <div class="floating-element" style="width: 150px; height: 150px; top: 60%; left: 80%;"></div>
    <div class="floating-element" style="width: 100px; height: 100px; top: 30%; left: 70%;"></div>
  </div>
  <!-- Login Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                <h5 align="center">SMART-SAMS</h5>
                <h6 style="font-size:8px; margin-left: 130px; margin-top: -10px;">Product Of YC Soft</h6>
                  <div class="text-center">
                    <img src="img/logo/attnlg.jpg">
                    <br><br>
                    <h1 class="h4 text-gray-900 mb-4">Login Panel</h1>
                  </div>
                  <form class="user" method="Post" action="">
                  <div class="form-group">
                  <select required name="userType" class="form-control mb-3">
                          <option value="">--Select User Roles--</option>
                          <option value="dod">DOD(Dean Of Displine)</option>
                          <option value="ClassTeacher">AnimationPanel</option>
                          <option value="dos">DOS(Dean Of Study)</option>
                          <option value="teacher">Teacher</option>

                        </select>
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" name="username" required name="username" id="exampleInputEmail" placeholder="Enter Username">
                    </div>
                    <div class="form-group">
                      <input type="password" name = "password" required class="form-control" id="exampleInputPassword" placeholder="Enter Password">
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">
                        <input type="checkbox" class="custom-control-input" id="customCheck" required>
                        <label class="custom-control-label" for="customCheck">Remember
                          Me</label>
                      </div>
                    </div>
                    <style>
  .asc{
    background: linear-gradient(130deg, #231a6f, #0f054c);
  }
  .asa{
    color: white;
    background: linear-gradient(130deg, #231a6f, #0f054c);
  }
  .asa:hover{
    color: white;
    background: linear-gradient(210deg, #231a6f, #0f054c);
  }
  .aso{
    color: black;
    background-color: #ffff;
    border: 1px solid black;
  }
.aso:hover{
  color: #ffff;
  background: linear-gradient(210deg, #231a6f, #0f054c);

}
</style>

                    <div class="form-group">
                        <input type="submit"  class="btn btn btn-block asa" value="Login" name="login" />
                    </div>
                     </form>

                     <a href="./" class="btn btn btn-block aso">Back Home</a>

<?php

  if(isset($_POST['login'])){

    $userType = $_POST['userType'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($userType == "dod"){

      $query = "SELECT * FROM tbladmin WHERE username = '$username' AND password = '$password' AND type = 'displine'";
      $rs = $conn->query($query);
      $num = $rs->num_rows;
      $rows = $rs->fetch_assoc();

      if($num > 0){

        $_SESSION['userId'] = $rows['Id'];

        echo "<script type = \"text/javascript\">
        window.location = (\"Admin/index.php\")
        </script>";
      }


      else{

        echo "<div class='alert alert-danger' role='alert'>
        Invalid Username/Password!
        </div>";

      }
    }

    else if($userType == "dos"){

      $query = "SELECT * FROM tbladmin WHERE username = '$username' AND password = '$password' AND type = 'study'";
      $rs = $conn->query($query);
      $num = $rs->num_rows;
      $rows = $rs->fetch_assoc();

      if($num > 0){

        $_SESSION['userId'] = $rows['Id'];

        echo "<script type = \"text/javascript\">
        window.location = (\"Admin2/index.php\")
        </script>";
      }


      else{

        echo "<div class='alert alert-danger' role='alert'>
        Invalid Username/Password!
        </div>";

      }
    }
    else if($userType == "ClassTeacher"){

      $query = "SELECT * FROM tblclassteacher WHERE username = '$username' AND password = '$password'";
      $rs = $conn->query($query);
      $num = $rs->num_rows;
      $rows = $rs->fetch_assoc();

      if($num > 0){

        $_SESSION['userId'] = $rows['Id'];

        echo "<script type = \"text/javascript\">
        window.location = (\"ClassTeacher/index.php\")
        </script>";
      }

      else{

        echo "<div class='alert alert-danger' role='alert'>
        Invalid Username/Password!
        </div>";

      }
    }
    else if($userType == "teacher"){

      $query = "SELECT * FROM tblteachers WHERE username = '$username' AND password = '$password'";
      $rs = $conn->query($query);
      $num = $rs->num_rows;
      $rows = $rs->fetch_assoc();

      if($num > 0){

        $_SESSION['userId'] = $rows['id'];
        echo "<script type = \"text/javascript\">
        window.location = (\"teacher/index.php\")
        </script>";
      }

      else{

        echo "<div class='alert alert-danger' role='alert'>
        Invalid Username/Password!
        </div>";

      }
    }

    else{

        echo "<div class='alert alert-danger' role='alert'>
        Invalid Username/Password!
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
  <script>
    // Add floating animation to form elements
    document.addEventListener('DOMContentLoaded', function() {
      const formElements = document.querySelectorAll('.form-group');
      formElements.forEach((element, index) => {
        element.style.animation = `fadeInUp 0.5s ease ${index * 0.2}s forwards`;
        element.style.opacity = '0';
      });

      // Add hover effect to card
      const card = document.querySelector('.card');
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const rotateX = (y - centerY) / 20;
        const rotateY = (centerX - x) / 20;

        card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
      });

      card.addEventListener('mouseleave', () => {
        card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
      });
    });
  </script>
</body>

</html>
