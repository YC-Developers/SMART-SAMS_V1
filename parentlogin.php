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
        position: relative;
        overflow: hidden;
    }

    .bg-gradient-login::before {
        content: '';
        position: absolute;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
        z-index: 0;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        transform-style: preserve-3d;
        transition: transform 0.5s ease;
        position: relative;
        z-index: 1;
    }

    .card:hover {
        transform: translateY(-10px) rotateX(5deg);
    }

    .login-form {
        padding: 2rem;
        transform-style: preserve-3d;
    }

    .btn-login {
        background: linear-gradient(130deg, #231a6f, #0f054c);
        color: white;
        padding: 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        transform: translateZ(20px);
    }

    .btn-login:hover {
        background: linear-gradient(210deg, #231a6f, #0f054c);
        transform: translateY(-2px) translateZ(30px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .form-control {
        border-radius: 8px;
        padding: 0.8rem;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
        transform: translateZ(10px);
    }

    .form-control:focus {
        border-color: #231a6f;
        box-shadow: 0 0 0 0.2rem rgba(35, 26, 111, 0.25);
        transform: translateZ(20px);
    }

    .logo-container {
        text-align: center;
        margin-bottom: 2rem;
        transform-style: preserve-3d;
    }

    .logo-container img {
        width: 120px;
        height: 120px;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.5s ease;
        transform: translateZ(30px);
    }

    .logo-container img:hover {
        transform: translateZ(50px) rotateY(10deg);
    }

    .brand-text {
        font-size: 2.5rem;
        font-weight: 700;
        color: #231a6f;
        margin: 1rem 0;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        transform: translateZ(20px);
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0% { transform: translateZ(20px) translateY(0px); }
        50% { transform: translateZ(20px) translateY(-10px); }
        100% { transform: translateZ(20px) translateY(0px); }
    }

    .subtitle {
        color: #666;
        margin-bottom: 2rem;
        transform: translateZ(15px);
        animation: fadeIn 1s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateZ(15px) translateY(20px); }
        to { opacity: 1; transform: translateZ(15px) translateY(0px); }
    }

    .input-group {
        transform-style: preserve-3d;
    }

    .input-group-text {
        transform: translateZ(10px);
    }
    </style>
</head>

<body class="bg-gradient-login">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8 col-md-10">
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
    <script>
        // Add mouse move effect for 3D perspective
        document.addEventListener('mousemove', function(e) {
            const card = document.querySelector('.card');
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
            card.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });

        // Reset card position when mouse leaves
        document.querySelector('.card').addEventListener('mouseleave', function() {
            this.style.transform = 'rotateY(0deg) rotateX(0deg)';
        });

        // Add typing animation for subtitle
        const subtitle = document.querySelector('.subtitle');
        const text = subtitle.textContent;
        subtitle.textContent = '';
        let i = 0;
        const typing = setInterval(() => {
            if (i < text.length) {
                subtitle.textContent += text.charAt(i);
                i++;
            } else {
                clearInterval(typing);
            }
        }, 100);
    </script>
</body>

</html>
