<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="SMART-SAMS, Attendance Management, Saint Kizito, Education Support" />
    <meta name="description"
        content="SMART-SAMS is an attendance management system developed by trainees at Ecole Technique Saint Kizito Save to enhance educational support through efficient attendance tracking and auditing." />
    <meta name="author" content="Saint Kizito Trainees" />
    <link rel="shortcut icon" href="images/icon.jpg" type="">

    <title>SMART-SAMS | Education System</title>
    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css1/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
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
<!-- scrollbar css -->
<link rel="stylesheet" href="css1/scrollbar.css">
<!-- subscribe section style  -->
<link rel="stylesheet" href="css1/subscribe.css">
<!-- recommendation styles -->
<link rel="stylesheet" href="css1/recommendation.css">

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

    /* parent portal section */

    .img-box video {
        width: 100%;
        height: auto;
        max-width: 500px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .img-box video {
            width: 100%;
            height: auto;
            margin: 0 auto;
            display: block;
        }
    }

    @media (max-width: 576px) {
        .img-box video {
            width: 95%;
            max-width: 350px;
        }
    }

    .header_section {
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        transition: all 0.3s ease-in-out;
    }

    .header_section.scrolled {
        background: rgba(1, 22, 95, 0.9);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
</style>

<body>

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
                    <a class="navbar-brand" href="#">
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
                            <li class="nav-item active">
                                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="about.php"> About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="service.php">Services</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="why.php">Why Us</a>
                            </li> -->
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="team.php">Team</a>
                            </li> -->
                            <li class="nav-item">
                                <a href="parent-portal.php" class="nav-link btn btn-outline-primary text-white">Parent
                                    Portal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php"> <i class="fa fa-user"
                                        aria-hidden="true"></i>&nbsp;LOGIN</a>
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
        <!-- slider section -->
        <section class="slider_section">
            <div id="customCarousel1" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" style="margin-top:50px;">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-box ml-0">
                                        <h1>
                                            Welcome to SMART-SAMS
                                        </h1>
                                        <p>
                                            The SMART-SAMS is designed to streamline attendance management at Ecole
                                            Technique Saint Kizito Save,
                                            providing educators with tools to efficiently track and audit student
                                            attendance, ensuring the
                                            best educational support.
                                        </p>
                                        <div class="btn-box">
                                            <a href="about.php" class="btn btn-primary">
                                                Learn More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <style>
                                    .rounded-custom {
                                        border-radius: 25px;
                                    }

                                    .rounded-custom-2 {
                                        border-top-right-radius: 25px;
                                        border-bottom-right-radius: 25px;

                                    }
                                </style>
                                <div class="col-md-6">
                                    <div class="img-box img-box-slider rounded-custom">
                                        <img src="images/dash.png" alt="SMART-SAMS System" class="img-fluid">
                                        <div class="text-overlay font-weight-bold display-2 w-100 bg-secondary">
                                            <span class="text-light span-1">SMART - SAMS</span><br>
                                            <span class="text-primary span-2">SCHOOL ATTENDANCE MANAGEMENT SYSTEM</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Slide for Dashboards -->
                    <div class="carousel-item" style="margin-top:60px;">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-box">
                                        <h1>
                                            SMART-SAMS Dashboard <br>
                                            Overview
                                        </h1>
                                        <p>
                                            Explore the comprehensive dashboards that provide insights into attendance
                                            patterns and trends,
                                            helping educators make informed decisions.
                                        </p>
                                        <div class="btn-box">
                                            <a href="#" class="btn btn-primary ">
                                                Dashboard Gallery
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="img-box img-box-slider rounded-custom-2">
                                        <img src="images/dash.png" alt="Dashboard Overview" class="img-fluid">
                                        <div class="text-overlay font-weight-bold display-2 w-100 bg-secondary">
                                            <span class="text-dark-blue span-1 ">INSIGHTFUL DASHBOARDS</span><br>
                                            <span class="text-primary span-2"> ADMINISTRATION PANEL</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <ol class="carousel-indicators">
                    <li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
                    <li data-target="#customCarousel1" data-slide-to="1"></li>
                </ol>
            </div>
        </section>
        <!-- end slider section -->
    </div>

    <!-- services section -->
    <section class="service_section layout_padding bg-white">
        <div class="service_container">
            <div class="container ">
                <div class="heading_container heading_center">
                    <h2>
                        Our <span>Services</span>
                        <hr class="my-4 border-top border-primary" style="width: 50%; height: 5px;">
                    </h2>
                    <p>
                        SMART-SAMS offers a range of services to enhance attendance management and educational support
                        at
                        Saint
                        Kizito and other schools.
                    </p>
                </div>
                <div class="row">
                    <div class="col-md-4 ">
                        <div class="box ">
                            <div class="img-box">
                                <img src="images/s1.png" alt="Attendance Tracking">
                            </div>
                            <div class="detail-box">
                                <h5>
                                    Attendance Tracking
                                </h5>
                                <p>
                                    Efficiently track student attendance with real-time updates and comprehensive
                                    reports.
                                </p>
                                <a href="">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="box ">
                            <div class="img-box">
                                <img src="images/s2.png" alt="Audit Reports">
                            </div>
                            <div class="detail-box">
                                <h5>
                                    Audit Reports
                                </h5>
                                <p>
                                    Generate detailed audit reports to ensure compliance and improve attendance
                                    policies.
                                </p>
                                <a href="">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="box ">
                            <div class="img-box">
                                <img src="images/s3.png" alt="Educational Support">
                            </div>
                            <div class="detail-box">
                                <h5>
                                    Educational Support
                                </h5>
                                <p>
                                    Enhance educational outcomes with tools designed to support teachers and students.
                                </p>
                                <a href="">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-box">
                    <a href="" class="rounded-pill">
                        View All
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- end services section -->

    <!-- about section -->
    <section class="about_section layout_padding">
        <div class="container  ">
            <div class="heading_container heading_center">
                <h2>
                    About <span>SMART-SAMS</span>
                    <hr class="my-4 border-top border-light" style="width: 50%; height: 5px;">
                </h2>
                <p>
                    SMART-SAMS is a project developed by the trainees at Ecole Technique Saint Kizito Save, aimed at
                    revolutionizing attendance management in educational institutions.
                </p>
            </div>
            <div class="row">
                <div class="col-md-6 ">
                    <div class="img-box">
                        <img src="images/about-img.png" alt="About SMART-SAMS">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <h3>
                            Our Mission
                        </h3>
                        <p>
                            Our mission is to provide a reliable and efficient system for managing student attendance,
                            ensuring that
                            educators have the tools they need to support student success.
                        </p>
                        <p>
                            By leveraging technology, we aim to simplify attendance processes and provide valuable
                            insights through
                            data analysis and reporting.
                        </p>
                        <a href="" class="rounded-pill">
                            Read More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- end about section -->

    <!-- why section -->
    <section class="why_section layout_padding bg-white">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>
                    Why Choose <span>SMART-SAMS</span>
                    <hr class="my-4 border-top border-primary" style="width: 50%; height: 5px;">
                </h2>
            </div>
            <div class="why_container">
                <div class="box">
                    <div class="img-box">
                        <img src="images/w1.png" alt="Efficient Management">
                    </div>
                    <div class="detail-box">
                        <h5>
                            Efficient Management
                        </h5>
                        <p>
                            Streamline attendance processes with our user-friendly interface and automated features.
                        </p>
                    </div>
                </div>
                <div class="box">
                    <div class="img-box">
                        <img src="images/w2.png" alt="Data Security">
                    </div>
                    <div class="detail-box">
                        <h5>
                            Data Security
                        </h5>
                        <p>
                            Protect sensitive student data with our secure and reliable system architecture.
                        </p>
                    </div>
                </div>
                <div class="box">
                    <div class="img-box">
                        <img src="images/w3.png" alt="Comprehensive Reports">
                    </div>
                    <div class="detail-box">
                        <h5>
                            Comprehensive Reports
                        </h5>
                        <p>
                            Access detailed reports to monitor attendance trends and make informed decisions.
                        </p>
                    </div>
                </div>
                <div class="box">
                    <div class="img-box">
                        <img src="images/w4.png" alt="User Satisfaction">
                    </div>
                    <div class="detail-box">
                        <h5>
                            User Satisfaction
                        </h5>
                        <p>
                            Our system is designed with user satisfaction in mind, ensuring a seamless experience for
                            all users.
                        </p>
                    </div>
                </div>
            </div>
            <div class="btn-box">
                <a href="" class="rounded-pill">
                    Read More
                </a>
            </div>
        </div>
    </section>
    <!-- end why section -->

    <!-- team section -->
    <section class="team_section layout_padding" style="background-color: #004080; color: white;">
        <div class="container-fluid">
            <div class="heading_container heading_center">
                <h2 class="">
                    Our <span> Team</span>
                    <hr class="my-4 border-top border-light" style="width: 50%; height: 5px;">
                </h2>
            </div>

            <div class="team_container">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="box">
                            <div class="img-box">
                                <img src="images/team-1.png" class="img1" alt="Developer">
                            </div>
                            <div class="detail-box">
                                <h5>
                                    Gisubizo A. Yannick
                                </h5>
                                <p>
                                    Software Developer
                                </p>
                            </div>
                            <div class="social_box">
                                <a href="#">
                                    <i class="fa fa-facebook" aria-hidden="true"></i>
                                </a>
                                <a href="#">
                                    <i class="fa fa-twitter" aria-hidden="true"></i>
                                </a>
                                <a href="#">
                                    <i class="fa fa-linkedin" aria-hidden="true"></i>
                                </a>
                                <a href="#">
                                    <i class="fa fa-instagram" aria-hidden="true"></i>
                                </a>
                                <a href="#">
                                    <i class="fa fa-youtube-play" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6">
                        <div class="box">
                            <div class="img-box">
                                <img src="images/team-2.png" class="img1" alt="Programmer">
                            </div>
                            <div class="detail-box">
                                <h5>
                                    Nizeyimana Placide
                                </h5>
                                <p>
                                    Software Developer
                                </p>
                            </div>
                            <div class="social_box">
                                <a href="#">
                                    <i class="fa fa-facebook" aria-hidden="true"></i>
                                </a>
                                <a href="#">
                                    <i class="fa fa-twitter" aria-hidden="true"></i>
                                </a>
                                <a href="#">
                                    <i class="fa fa-linkedin" aria-hidden="true"></i>
                                </a>
                                <a href="#">
                                    <i class="fa fa-instagram" aria-hidden="true"></i>
                                </a>
                                <a href="#">
                                    <i class="fa fa-youtube-play" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>

    </section>

    <!-- end team section -->

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
                        <a href="parentlogin.php" class="btn btn-primary mt-3">
                            Access Parent Portal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end parent portal content -->
    <!-- Recommendation section -->
    <section class="recommendation-section layout_padding"
        style="background: linear-gradient(130deg, #231a6f, #0f054c); color: white;">
        <div class="container">
            <div class="heading_container heading_center mb-5">
                <h2>
                    Software <span style="color: #fff;">Plan</span>
                    <hr class="my-4 border-top border-light"
                        style="width: 50%; height: 3px; background: rgba(255,255,255,0.2);">
                </h2>
            </div>

            <form id="recommendationForm" action="process_recommendation.php" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="billing-option mb-4 p-4 bg-white rounded-lg shadow-lg hover-effect">
                            <h3 style="color: #231a6f;" class="mb-3">Premium Plan</h3>
                            <div class="price-tag">
                                <h4 class="mb-3 text-dark">$500 <span class="text-muted">/year</span></h4>
                            </div>
                            <ul class="list-unstyled feature-list">
                                <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Full System Access
                                </li>
                                <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Priority Support
                                    24/7</li>
                                <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Unlimited Storage
                                </li>
                                <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Free Updates</li>
                            </ul>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="billing-option mb-4 p-4 bg-white rounded-lg shadow-lg hover-effect">
                            <h3 style="color: #231a6f;" class="mb-3">Standard Plan</h3>
                            <div class="price-tag">
                                <h4 class="mb-3 text-dark">$150 <span class="text-muted">/month</span></h4>
                            </div>
                            <ul class="list-unstyled feature-list">
                                <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Core Features</li>
                                <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Standard Support
                                </li>
                                <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> 2GB Storage</li>
                                <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Monthly Updates
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- info section -->
    <section class="info_section">
        <div class="container">
            <div class="row mt-3">
                <!-- Contact Information -->
                <div class="col-md-6 col-lg-3 info_col">
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
            background:rgb(1, 2, 52) ;
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
                &copy; <span id="displayYear"></span> All Rights Reserved By
                <a href="#">SMART-SAMS </a>
            </p>
        </div>
    </section>
    <!-- footer section -->
    <!-- loader -->
    <div class="loader-container">
        <div class="loader-3d">
            <div class="cube">
                <div class="face front"></div>
                <div class="face back"></div>
                <div class="face left"></div>
                <div class="face right"></div>
                <div class="face top"></div>
                <div class="face bottom"></div>
            </div>
        </div>
        <div class="loader-text text-center">
            <span class="welcome-text">WELCOME TO</span>
            <span class="digital-text">SMART-SAMS</span><br>
            <small class="digital-subtext">School Attendance Management System</small>
            <div class="loading-dots">
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </div>
    <!-- end loader -->
    <!-- loader script -->
    <script>
        window.addEventListener('load', function() {
            const loader = document.querySelector('.loader-container');
            setTimeout(function() {
                loader.style.opacity = '0';
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 500);
            }, 2000);
        });
    </script>

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

        .loader-3d {
            perspective: 1000px;
            margin-bottom: 2rem;
        }

        .cube {
            width: 100px;
            height: 100px;
            position: relative;
            transform-style: preserve-3d;
            animation: rotate 3s infinite linear;
        }

        .face {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .front {
            transform: translateZ(50px);
        }

        .back {
            transform: translateZ(-50px) rotateY(180deg);
        }

        .left {
            transform: translateX(-50px) rotateY(-90deg);
        }

        .right {
            transform: translateX(50px) rotateY(90deg);
        }

        .top {
            transform: translateY(-50px) rotateX(90deg);
        }

        .bottom {
            transform: translateY(50px) rotateX(-90deg);
        }

        @keyframes rotate {
            0% {
                transform: rotateX(0) rotateY(0) rotateZ(0);
            }

            100% {
                transform: rotateX(360deg) rotateY(360deg) rotateZ(360deg);
            }
        }

        .welcome-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            color: #ffffff;
            margin-bottom: 0.5rem;
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

        .digital-subtext {
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            color: #e6f2ff;
            margin-top: 0.5rem;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }

        .loading-dots {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .dot {
            width: 10px;
            height: 10px;
            background: #ffffff;
            border-radius: 50%;
            margin: 0 5px;
            animation: bounce 1.4s infinite ease-in-out;
        }

        .dot:nth-child(1) {
            animation-delay: -0.32s;
        }

        .dot:nth-child(2) {
            animation-delay: -0.16s;
        }

        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: scale(0);
            }

            40% {
                transform: scale(1);
            }
        }

        @keyframes glitch {
            0% {
                transform: translate(0);
            }

            20% {
                transform: translate(-2px, 2px);
            }

            40% {
                transform: translate(-2px, -2px);
            }

            60% {
                transform: translate(2px, 2px);
            }

            80% {
                transform: translate(2px, -2px);
            }

            100% {
                transform: translate(0);
            }
        }

        .digital-text:hover {
            animation: glitch 0.3s infinite;
        }
    </style>
    <!-- end loader script -->
    <!-- end loader script -->
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

    <!-- nav bar on scroll -->
    <script>
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header_section');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        let prevScrollpos = window.pageYOffset;
        let timeoutId;

        window.onscroll = function() {
            const header = document.querySelector('.header_section');
            let currentScrollPos = window.pageYOffset;

            // Clear the previous timeout
            clearTimeout(timeoutId);

            // Show/hide based on scroll direction
            if (prevScrollpos > currentScrollPos) {
                header.style.top = "0";
            } else {
                header.style.top = "-100px";
            }

            prevScrollpos = currentScrollPos;

            // Set timeout to hide navbar after stopping scroll
            timeoutId = setTimeout(() => {
                header.style.top = "-100px";
            }, 5000);
        }
    </script>

    <!-- end of nav scroll -->
</body>

</html>
