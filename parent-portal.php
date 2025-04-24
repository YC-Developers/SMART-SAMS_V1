<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="images/icon.jpg" type="">

    <title>ST KIZITO SAVE | SMART-SAMS</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css1/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

    <!-- font awesome style -->
    <link href="css1/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="css1/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css1/responsive.css" rel="stylesheet" />
    <link rel="stylesheet" href="css1/scrollbar.css">
    <link rel="stylesheet" href="css1/subscribe.css">

</head>
<style>
    html {
        scroll-behavior: smooth;
    }

    .get-it-now-btn {
        border: none;
        background: transparent;
        transition: all 0.3s ease;
    }

    .get-it-now-btn:hover {
        transform: translateY(-3px);
    }

    .get-it-now-btn .btn-danger {
        background: linear-gradient(45deg, #dc3545, #ff4d5a);
        border: none;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .get-it-now-btn .btn-danger:hover {
        background: linear-gradient(45deg, #ff4d5a, #dc3545);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
    }

    .btn-primary {
        background: #004080;
        border: none;
        border-radius: 25px;
        padding: 12px 30px;
        box-shadow: 0 4px 15px rgba(0, 64, 128, 0.2);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 64, 128, 0.3);
    }

    .service_section .box {
        transition: all 0.3s ease;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .service_section .box:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .nav-item .nav-link {
        transition: all 0.3s ease;
    }

    .nav-item .nav-link:hover {
        color: #004080;
        transform: translateY(-2px);
    }
</style>
<style>
    /* Video container styles */
    .video-container {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        /* 16:9 Aspect Ratio */
        height: 0;
        overflow: hidden;
    }

    .responsive-video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 15px;
    }

    /* Media Queries */
    @media (max-width: 768px) {
        .parent_portal_content {
            padding: 30px 15px;
        }

        .heading_container h2 {
            font-size: 28px;
        }

        .detail-box {
            margin-top: 30px;
            padding: 0 15px;
        }

        .video-container {
            margin-bottom: 30px;
        }
    }

    @media (max-width: 576px) {
        .heading_container h2 {
            font-size: 24px;
        }

        .btn-primary {
            width: 100%;
            margin-top: 20px;
        }

        .detail-box ul {
            padding-left: 20px;
        }
    }

    @media (min-width: 992px) {
        .parent_portal_content .container {
            max-width: 960px;
        }
    }

    /* Enhance text readability */
    .text-muted {
        line-height: 1.6;
        font-size: 16px;
    }

    /* Improve list spacing */
    .detail-box ul li {
        margin-bottom: 10px;
        line-height: 1.5;
    }
</style>

<body class="sub_page">

    <div class="hero_area">

        <div class="hero_bg_box">
            <div class="bg_img_box">
                <img src="images/hero-bg.png" alt="">
            </div>
        </div>

        <!-- header section strats -->
        <header class="header_section">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container ">
                    <a class="navbar-brand" href="index.html">
                        <span class="d-inline-block me-2">
                            <img src="images/icon.jpg" alt="Icon" class="img-fluid"
                                style="max-width: 40px; max-height: 40px;">
                        </span>
                        <span class="navbar-brand">
                            SMART-SAMS
                        </span>
                    </a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class=""> </span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav  ">
                            <li class="nav-item ">
                                <a class="nav-link" href="index.php">Home </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="about.php"> About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="service.php">Services <span class="sr-only">(current)</span>
                                </a>
                            </li>
                            <li class="nav-item active">
                                <a href="#" class="nav-link btn btn-outline-primary text-white">Parent Portal <span
                                        class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php"> <i class="fa fa-user"
                                        aria-hidden="true"></i> LOGIN</a>
                            </li>
                            <button class="get-it-now-btn">
                                <a href="./recommendation_page/index.php"
                                    class="btn btn-danger text-white px-4 py-2 rounded-pill shadow-lg d-flex align-items-center">
                                    <i class="fa fa-rocket mr-2"></i>Get It Now!
                                </a>
                            </button>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!-- end header section -->
    </div>
    <!-- parent portal content -->
    <section class="parent_portal_content layout_padding py-5 bg-light">
        <div class="container">
            <div class="heading_container heading_center mb-4">
                <h2 class="text-primary">
                    Parent <span class="text-dark">Portal</span>
                    <hr class="my-4 border-top border-primary" style="width: 50%; height: 5px;">
                </h2>
                <p class="text-muted">
                    Access the Parent Portal to stay updated with your child's attendance and academic progress. Our
                    system is
                    designed to help students perform well in class by providing parents with timely insights and
                    resources.
                    Additionally, it serves as a security measure, ensuring that parents are informed about their
                    child's
                    whereabouts and school activities. The portal offers tools for tracking statistical reports,
                    monitoring class
                    performance of the student, and empowering parents to support their child's educational journey
                    effectively.
                    It also includes alerts for any unusual activities, enhancing the safety and security of students.
                </p>
            </div>
            <div class="row">
                <!-- Replace the existing video section with this optimized version -->
                <div class="col-md-6">
                    <div class="img-box video-container">
                        <video class="responsive-video" autoplay muted loop>
                            <source src="images/parent-portal-intro/parent-portal-intro.mp4" type="video/mp4">
                            <source src="images/parent-portal-intro/parent-portal-intro.webm" type="video/webm">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <h3 class="text-secondary">
                            Key Features
                        </h3>
                        <ul class="text-muted">
                            <li>Real-time Attendance tracking and updates.</li>
                            <li>Access to Attendance performance reports.</li>
                            <li>Communication tools for interacting with School Staff.</li>
                            <li>Alerts for unusual activities to ensure student safety.</li>
                            <li>Resources and insights to support student success.</li>
                        </ul>
                        <a href="parentlogin.php" class="btn btn-primary mt-3 rounded-pill">
                            Access Parent Portal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end parent portal content -->

    <!-- info section -->
    <section class="info_section">
        <div class="container">
            <div class="row ">
                <!-- Contact Information -->
                <div class="col-md-6 col-lg-3 info_col -ml-5">
                    <div class="info_contact">
                        <h4 class="section-title">Contact Us</h4>
                        <div class="contact_link_box">
                            <a href="#" class="contact-item">
                                <i class="fa fa-map-marker"></i>
                                <span>Southern Province - Rwanda</span>
                            </a>
                            <a href="tel:+250794897943" class="contact-item">
                                <i class="fa fa-phone"></i>
                                <span>+250 794-897-943</span>
                            </a>
                            <a href="tel:+250791285293" class="contact-item">
                                <i class="fa fa-phone"></i>
                                <span>+250 791-285-293</span>
                            </a>
                            <a href="mailto:ycsoft24@gmail.com" class="contact-item">
                                <i class="fa fa-envelope"></i>
                                <span>ycsoft24@gmail.com</span>
                            </a>
                        </div>
                        <div class="info_social">
                            <a href="#" class="social-icon">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <a href="#" class="social-icon">
                                <i class="fa fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon">
                                <i class="fa fa-linkedin"></i>
                            </a>
                            <a href="#" class="social-icon">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Available Schools -->
                <div class="col-md-6 col-lg-3 info_col">
                    <div class="info_contact">
                        <h4 class="section-title">Available Schools</h4>
                        <div class="schools-list">
                            <div class="school-item">
                                <i class="fa fa-graduation-cap"></i>
                                <span>Ecole Technique Saint KIZITO SAVE</span>
                                <small>Southern Province</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-md-6 col-lg-2 info_col">
                    <div class="info_contact">
                        <h4 class="section-title">Quick Links</h4>
                        <ul class="quick-links">
                            <li><a href="index.php"><i class="fa fa-chevron-right"></i> Home</a></li>
                            <li><a href="about.php"><i class="fa fa-chevron-right"></i> About Us</a></li>
                            <li><a href="team.php"><i class="fa fa-chevron-right"></i> Team</a></li>
                            <li><a href="service.php"><i class="fa fa-chevron-right"></i> Services</a></li>
                            <li><a href="why.php"><i class="fa fa-chevron-right"></i> Why Us</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Feedback Form -->
                <div class="col-md-6 col-lg-4 info_col">
                    <div class="info_contact">
                        <h4 class="section-title">Send Us Feedback</h4>
                        <form action="send_feedback.php" class="feedback-form" method="POST">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Your Name" name="name" required>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email Address" name="email" required>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="rating" required>
                                    <option value="" class="text-secondary">--Rate Your Experience--</option>
                                    <option value="5" class="text-secondary">⭐⭐⭐⭐⭐ Excellent</option>
                                    <option value="4" class="text-secondary">⭐⭐⭐⭐ Very Good</option>
                                    <option value="3" class="text-secondary">⭐⭐⭐ Good</option>
                                    <option value="2" class="text-secondary">⭐⭐ Fair</option>
                                    <option value="1" class="text-secondary">⭐ Poor</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Your Feedback Message" name="message" required rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary-1 p-1 rounded-pill">
                                <i class="fa fa-paper-plane"></i> Send Feedback
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .info_section {
            background: rgb(1, 2, 52);
            padding: 40px 0;
            color: #ffffff;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            position: relative;
            padding-bottom: 0.4rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: #ffffff;
        }

        .contact_link_box {
            margin-bottom: 1.2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.8rem;
            color: #ffffff;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .contact-item:hover {
            color: #e6f2ff;
            transform: translateX(5px);
        }

        .contact-item i {
            margin-right: 0.8rem;
            font-size: 1rem;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .info_social {
            display: flex;
            gap: 0.2rem;
            margin-top: 1.5rem;
        }

        .social-icon {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: #ffffff;
            transform: translateY(-3px);
        }

        .social-icon i {
            font-size: 1rem;
        }

        .schools-list {
            margin-top: 0.8rem;
        }

        .school-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 0.8rem;
        }

        .school-item i {
            font-size: 1.2rem;
            margin-bottom: 0.4rem;
            color: #ffffff;
        }

        .school-item span {
            display: block;
            font-weight: 500;
            margin-bottom: 0.2rem;
            font-size: 0.95rem;
        }

        .school-item small {
            color: #e6f2ff;
            font-size: 0.85rem;
        }

        .quick-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .quick-links li {
            margin-bottom: 0.6rem;
        }

        .quick-links a {
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .quick-links a:hover {
            color: #e6f2ff;
            transform: translateX(5px);
        }

        .quick-links i {
            margin-right: 0.4rem;
            font-size: 0.7rem;
        }

        .feedback-form {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.2rem;
            border-radius: 12px;
        }

        .form-group {
            margin-bottom: 0.8rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            padding: 0.6rem 0.8rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: none;
            color: #ffffff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .btn-primary-1 {
            background: #ffffff;
            color: #004080;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            margin-top: 0.4rem;
        }

        .btn-primary-1:hover {
            background: #e6f2ff;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .info_section {
                padding: 30px 0;
            }

            .section-title {
                font-size: 1.2rem;
            }

            .contact-item i,
            .social-icon {
                width: 32px;
                height: 32px;
            }

            .row {
                margin: 0 -10px;
            }

            .col-md-6,
            .col-lg-3 {
                padding: 0 10px;
                margin-bottom: 20px;
            }
        }

        /* Ensure consistent spacing between sections */
        .info_col {
            margin-bottom: 1.5rem;
        }

        /* Improve form select styling */
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.8rem center;
            padding-right: 2rem;
        }

        select.form-control:focus {
            border-color: #ffffff;
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.2);
        }
    </style>
    <!-- end info section -->

    <!-- footer section -->
    <section class="footer_section">
        <div class="container">
            <p>
                © <span id="displayYear"></span> All Rights Reserved By
                <a href="#">SMART-SAMS 2024</a>
            </p>
        </div>
    </section>
    <!-- footer section -->

    <!-- loader -->
    <div class="loader-container">
        <div class="loader-checkmark">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>
        <div class="loader-text text-center">
            <span class="welcome-text">SMART-SAMS</span>
            <span class="digital-text">Attendance System</span>
            <div class="digital-clock" id="digitalClock"></div>
            <div class="progress-bar">
                <div class="progress-bar-fill"></div>
            </div>
            <small class="digital-subtext">Tracking Student Success</small>
        </div>
    </div>
    <!-- end loader -->

    <!-- loader styles -->
    <style>
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #004080 0%, #0066cc 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loader-checkmark {
            width: 80px;
            height: 80px;
            margin-bottom: 1.5rem;
        }

        .checkmark__circle {
            stroke: rgba(255, 255, 255, 0.2);
            stroke-width: 2;
            stroke-miterlimit: 10;
            animation: circle 1.5s ease-in-out infinite;
        }

        .checkmark__check {
            stroke: #ffffff;
            stroke-width: 4;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: check 0.6s ease-in-out 0.3s forwards;
        }

        @keyframes circle {
            0% {
                stroke-dasharray: 0 100;
                stroke-dashoffset: 0;
            }
            50% {
                stroke-dasharray: 80 100;
                stroke-dashoffset: -20;
            }
            100% {
                stroke-dasharray: 0 100;
                stroke-dashoffset: -100;
            }
        }

        @keyframes check {
            100% {
                stroke-dashoffset: 0;
            }
        }

        .welcome-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            color: #ffffff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            letter-spacing: 1px;
        }

        .digital-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(45deg, #ffffff, #e6f2ff);
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
        }

        .digital-clock {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.2rem;
            color: #e6f2ff;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            margin: 0.5rem 0;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }

        .progress-bar {
            width: 200px;
            height: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            overflow: hidden;
            margin: 1rem 0;
        }

        .progress-bar-fill {
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, #ffffff, #e6f2ff);
            animation: progress 2s ease-in-out forwards;
        }

        @keyframes progress {
            0% {
                width: 0;
            }
            100% {
                width: 100%;
            }
        }

        .digital-subtext {
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            color: #e6f2ff;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }

        @media (max-width: 576px) {
            .welcome-text {
                font-size: 1.5rem;
            }
            .digital-text {
                font-size: 2rem;
            }
            .digital-clock {
                font-size: 1rem;
            }
            .progress-bar {
                width: 150px;
            }
            .loader-checkmark {
                width: 60px;
                height: 60px;
            }
        }
    </style>

    <!-- loader script -->
    <script>
        window.addEventListener('load', function() {
            const loader = document.querySelector('.loader-container');
            setTimeout(function() {
                loader.style.opacity = '0';
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 500);
            }, 2500);
        });

        // Digital clock
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('digitalClock').textContent = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    <!-- jQery -->
    <script type="text/javascript" src="js1/jquery-3.4.1.min.js"></script>
    <!-- popper js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <!-- bootstrap js -->
    <script type="text/javascript" src="js1/bootstrap.js"></script>
    <!-- owl slider -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
    </script>
    <!-- custom js -->
    <script type="text/javascript" src="js1/custom.js"></script>
    <!-- Google Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
    </script>
    <!-- End Google Map -->

</body>

</html>