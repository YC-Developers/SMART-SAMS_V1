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

    <title>SMART-SAMS| Education System</title>
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
                                        <img src="images/slider-image.jpg" alt="SMART-SAMS System" class="img-fluid">
                                        <div class="text-overlay font-weight-bold display-2 w-100">
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
                                        <div class="text-overlay font-weight-bold display-2 w-100">
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

                <!-- Contact Information Form -->
                <!-- <div class="contact-form bg-white rounded-lg shadow-lg p-4 mt-4">
                    <h3 style="color: #231a6f;" class="mb-4">Contact Information</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control custom-input" name="name"
                                    placeholder="Enter School Administrator Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" class="form-control custom-input" name="email"
                                    placeholder="Enter your Email Address" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+250</span>
                                    </div>
                                    <input type="tel" class="form-control custom-input" name="phone" pattern="[0-9]{9}"
                                        placeholder="Enter phone number " maxlength="9" required>
                                </div>
                                <small class="form-text text-danger">*Enter 9 digits without the country code</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control custom-input" name="schoolname"
                                    placeholder="Enter School Name" required>
                            </div>
                        </div> -->
                <!-- Add this inside the contact-form div, before the submit button -->
                <!-- <div class="row mt-4">
                            <div class="col-12">
                                <h4 style="color: #231a6f; padding-left: 20px;" class="mb-4">Payment Method</h4>
                                <div class="payment-methods">
                                    <div class="row">
                                        <div class="col-md-3 col-6 mb-3">
                                            <div class="payment-option">
                                                <input type="radio" name="paymentMethod" id="momo" value="momo"
                                                    required>
                                                <label for="momo" class="payment-label">
                                                    <img src="images/momo.png" alt="MTN Mobile Money"
                                                        class="payment-icon">
                                                    <span>MTN MoMo</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6 mb-3">
                                            <div class="payment-option">
                                                <input type="radio" name="paymentMethod" id="airtel" value="airtel">
                                                <label for="airtel" class="payment-label">
                                                    <img src="images/airtel.png" alt="Airtel Money"
                                                        class="payment-icon">
                                                    <span>Airtel Money</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6 mb-3">
                                            <div class="payment-option">
                                                <input type="radio" name="paymentMethod" id="visa" value="visa">
                                                <label for="visa" class="payment-label">
                                                    <img src="images/visa.png" alt="Visa Card" class="payment-icon">
                                                    <span>Visa Card</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6 mb-3">
                                            <div class="payment-option">
                                                <input type="radio" name="paymentMethod" id="mastercard"
                                                    value="mastercard">
                                                <label for="mastercard" class="payment-label">
                                                    <img src="images/mastercard.png" alt="Mastercard"
                                                        class="payment-icon">
                                                    <span>Mastercard</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                <!-- <div class="col-12">
                            <div class="form-group">
                                <textarea class="form-control custom-input" name="comments" rows="2"
                                    placeholder="Additional Comments"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 hover-effect">
                            <i class="fa fa-paper-plane mr-2"></i>Submit Order
                        </button>
                    </div> -->
                <!-- </div> -->
            </form>
        </div>

    </section>

    <!-- info section -->

    <section class="info_section layout_padding2">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-3 info_col">
                    <div class="info_contact">
                        <h4>
                            Contact Us
                        </h4>
                        <div class="contact_link_box">
                            <a href="">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <span>
                                    Southern Province - Rwanda
                                </span>
                            </a>
                            <a href="">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span>
                                    Call +250 794-897-943
                                </span><br>
                                <span>
                                    Call +250 791-285-293
                                </span>
                            </a>
                            <a href="">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span>
                                    ycsoft24@gmail.com
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="info_social">
                        <a href="" class="social-icon">
                            <i class="fa fa-facebook"
                                style="color: #4267B2; background: white; padding: 10px; border-radius: 50%; transition: all 0.3s ease;"></i>
                        </a>
                        <a href="" class="social-icon">
                            <i class="fa fa-twitter"
                                style="color: #1DA1F2; background: white; padding: 10px; border-radius: 50%; transition: all 0.3s ease;"></i>
                        </a>
                        <a href="" class="social-icon">
                            <i class="fa fa-linkedin"
                                style="color: #0077B5; background: white; padding: 10px; border-radius: 50%; transition: all 0.3s ease;"></i>
                        </a>
                        <a href="" class="social-icon">
                            <i class="fa fa-instagram"
                                style="color: #E1306C; background: white; padding: 10px; border-radius: 50%; transition: all 0.3s ease;"></i>
                        </a>
                        <a href="" class="social-icon">
                            <i class="fa fa-youtube"
                                style="color: #FF0000; background: white; padding: 10px; border-radius: 50%; transition: all 0.3s ease;"></i>
                        </a>
                    </div>

                    <style>
                        .info_social {
                            display: flex;
                            gap: 15px;
                            margin-top: 20px;
                        }

                        .social-icon {
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            transition: transform 0.3s ease;
                        }

                        .social-icon:hover {
                            transform: translateY(-5px);
                        }

                        .social-icon i {
                            width: 35px;
                            height: 35px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                        }

                        .social-icon i:hover {
                            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                        }
                    </style>


                </div>
                <div class="col-md-6 col-lg-3 info_col">
                    <div class="info_contact">
                        <h4 class="mb-4 text-white">
                            Available Schools
                        </h4>
                        <ul class="list-group list-group-flush bg-transparent">
                            <li class="list-group-item bg-transparent text-white border-0">
                                Ecole Technique Saint KIZITO SAVE - Southern Province
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 info_col">
                    <div class="info_contact">
                        <h4 class="mb-4 text-white">
                            Quick Links
                        </h4>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent border-0">
                                <a href="#" class="text-white text-decoration-none hover-effect">
                                    <i class="fa fa-chevron-right mr-2"></i> Home
                                </a>
                            </li>
                            <li class="list-group-item bg-transparent border-0">
                                <a href="about.php" class="text-white text-decoration-none hover-effect">
                                    <i class="fa fa-chevron-right mr-2"></i> About Us
                                </a>
                            </li>
                            <li class="list-group-item bg-transparent border-0">
                                <a href="team.php" class="text-white text-decoration-none hover-effect">
                                    <i class="fa fa-chevron-right mr-2"></i> Team
                                </a>
                            </li>
                            <li class="list-group-item bg-transparent border-0">
                                <a href="service.php" class="text-white text-decoration-none hover-effect">
                                    <i class="fa fa-chevron-right mr-2"></i> Services
                                </a>
                            </li>
                            <li class="list-group-item bg-transparent border-0">
                                <a href="why.php" class="text-white text-decoration-none hover-effect">
                                    <i class="fa fa-chevron-right mr-2"></i> Why Us
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- feedback section -->

                <div class="col-md-6 col-lg-3 info_col">
                    <div class="info_contact">
                        <h4 class="mb-4" style="color: #ffffff;">
                            Send Us Feedback
                        </h4>
                        <form action="send_feedback.php" class="feedback-form" method="POST"
                            style="background: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 15px;">

                            <div class="form-group mt-1">
                                <input type="text" class="form-control bg-transparent text-white border-0 border-bottom"
                                    placeholder="Your Name" name="name" required
                                    style="border-radius: 0; border-bottom: 1px solid rgba(255,255,255,0.5) !important;">
                            </div>

                            <div class="form-group mt-1">
                                <input type="email"
                                    class="form-control bg-transparent text-white border-0 border-bottom"
                                    placeholder="Email Address" name="email" required
                                    style="border-radius: 0; border-bottom: 1px solid rgba(255,255,255,0.5) !important;">
                            </div>

                            <div class="form-group mt-1">
                                <select
                                    class="form-control custom-select bg-transparent text-white border-0 border-bottom"
                                    name="rating" required
                                    style="border-radius: 4px; border-bottom: 2px solid rgba(255,255,255,0.3) !important;">
                                    <option value="" class="text-dark bg-light">--Rate Your Experience--</option>
                                    <option value="5" class="text-dark bg-light">⭐⭐⭐⭐⭐ Excellent</option>
                                    <option value="4" class="text-dark bg-light">⭐⭐⭐⭐ Very Good</option>
                                    <option value="3" class="text-dark bg-light">⭐⭐⭐ Good</option>
                                    <option value="2" class="text-dark bg-light">⭐⭐ Fair</option>
                                    <option value="1" class="text-dark bg-light">⭐ Poor</option>
                                </select>
                            </div>

                            <div class="form-group mt-1">
                                <textarea class="form-control bg-transparent text-white border-0 border-bottom"
                                    placeholder="Your Feedback Message" name="message" required rows="3"
                                    style="border-radius: 0; border-bottom: 1px solid rgba(255,255,255,0.5) !important;"></textarea>
                            </div>

                            <button type="submit"
                                class="btn btn-outline-light btn-block mt-4 rounded-pill d-flex align-items-center justify-content-center">
                                <i class="fa fa-paper-plane mr-2"></i>
                                <span>Send Feedback</span>
                            </button>

                        </form>
                    </div>
                </div>



            </div>
        </div>
        <!-- slider section for the appreciation
    <div class="sliding-appreciation" style="text-align: center; color: #fff; overflow: hidden; padding: 10px 0;">
      <div class="slide-text" style="animation: slide 20s linear infinite;">
        Special thanks to our School ECOLE TECHNIQUE SAINT KIZITO-SAVE and Trainers assistance making SMART-SAMS a success - We grant the best experience as far as the system expands it branches - Leave an order by filling the above form if you are interested.
      </div>
      <style>
        @keyframes slide {
          0% {
            transform: translateX(100%);
          }

          100% {
            transform: translateX(-100%);
          }
        }

        .sliding-appreciation {
          background: transparent;
          /* border-radius: 5px; */
          /* margin: 10px 0; */
        }

        .slide-text {
          white-space: nowrap;
          display: inline-block;
        }
      </style> -->
    </section>
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
    <!-- <div class="loader-container">
        <img src="images/icon.jpg" alt="SMART-SAMS Logo" class="loader-logo">
        <div class="loader-text">
            SMART-SAMS<br>
            <small> Attendance Management System</small>
        </div>
    </div> -->
    <!-- end loader -->

    <!-- loader script -->
    <!-- <script>
    window.addEventListener('load', function() {
        const loader = document.querySelector('.loader-container');
        setTimeout(function() {
            loader.style.opacity = '0';
            setTimeout(function() {
                loader.style.display = 'none';
            }, 500);
        }, 2000);
    });
    </script> -->

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