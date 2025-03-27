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
    <title>SMART-SAMS | Parent Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <style>
    .bg-gradient-login {
        background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .login-form {
        padding: 2rem;
    }

    .btn-login {
        background: linear-gradient(130deg, #231a6f, #0f054c);
        color: white;
        padding: 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        background: linear-gradient(210deg, #231a6f, #0f054c);
        transform: translateY(-2px);
        color: white;
    }

    .form-control {
        border-radius: 8px;
        padding: 0.8rem;
        border: 1px solid #e0e0e0;
    }

    .form-control:focus {
        border-color: #231a6f;
        box-shadow: 0 0 0 0.2rem rgba(35, 26, 111, 0.25);
    }

    .logo-container {
        text-align: center;
        margin-bottom: 2rem;
    }

    .logo-container img {
        width: 120px;
        height: 120px;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .brand-text {
        font-size: 2.5rem;
        font-weight: 700;
        color: #231a6f;
        margin: 1rem 0;
    }

    .subtitle {
        color: #666;
        margin-bottom: 2rem;
    }
    </style>
</head>

<body class="bg-gradient-login">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="login-form">
                            <div class="logo-container">
                                <img src="img/logo/attnlg.jpg" alt="AMS Logo">
                                <div class="brand-text">SMART - SAMS</div>
                                <div class="subtitle">Parent Portal Login</div>
                            </div>

                            <form method="Post" action="">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="reg" required class="form-control"
                                            placeholder="Enter Student Registration Number">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="login" class="btn btn-block btn-login">
                                        Login to Parent Portal
                                    </button>
                                </div>
                            </form>

                            <?php
                            if(isset($_POST['login'])){
                                $regNumber = $_POST['reg'];
                                $query = "SELECT * FROM tblstudents WHERE admissionNumber = '$regNumber'";
                                $rs = $conn->query($query);
                                $num = $rs->num_rows;
                                $rows = $rs->fetch_assoc();
                                
                                if($num > 0){
                                    $_SESSION['regNumber'] = $rows['admissionNumber'];
                                    echo "<script type = \"text/javascript\">
                                    window.location = (\"parent/index.php\")
                                    </script>";
                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>
                                    Invalid Registration Number!
                                    </div>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
</body>

</html>