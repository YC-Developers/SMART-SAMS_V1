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
                        <a href="parentlogin.php" class="btn btn-primary mt-3">
                            Access Parent Portal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end parent portal content -->
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
                <a href="#">SMART-SAMS 2024</a>
            </p>
        </div>
    </section>
    <!-- footer section -->
    <!-- loader -->
    <div class="loader-container">
        <img src="images/icon.jpg" alt="SMART-SAMS Logo" class="loader-logo">
        <div class="loader-text">
            SMART-SAMS<br>
            <small> School Attendance Management System</small>
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

</body>

</html>