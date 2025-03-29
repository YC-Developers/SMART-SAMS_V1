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
    <link rel="shortcut icon" href="../images/icon.jpg" type="">

    <title>ST KIZITO SAVE | SMART-SAMS</title>
    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="../css1/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

<!--owl slider stylesheet -->
<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

<!-- font awesome style -->
<link href="../css1/font-awesome.min.css" rel="stylesheet" />

<!-- Custom styles for this template -->
<link href="../css1/style.css" rel="stylesheet" />
<!-- responsive style -->
<link href="../css1/responsive.css" rel="stylesheet" />
<!-- scrollbar css -->
<link rel="stylesheet" href="../css1/scrollbar.css">
<!-- subscribe section style  -->
<link rel="stylesheet" href="../css1/subscribe.css">
<!-- recommendation styles -->
<link rel="stylesheet" href="../css1/recommendation.css">

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
        background: rgba(0, 64, 128, 0.9);
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
                            <img src="../images/icon.jpg" alt="Icon" class="img-fluid"
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
                                <a class="nav-link" href="../index.php">Home <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../about.php"> About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../service.php">Services</a>
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
                            <!-- <button class="get-it-now-btn">
                                <a href="./recommendation_page/software_recommendation.php"
                                    class="btn btn-danger text-white px-4 py-2 rounded-pill shadow-lg d-flex align-items-center">
                                    <i class="fa fa-rocket mr-2"></i>Get It Now!
                                </a>
                            </button> -->



                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <section class="recommendation-section layout_padding"
            style="background: linear-gradient(130deg, #231a6f, #0f054c); color: white;">
            <div class="container">
                <div class="heading_container heading_center mb-5">
                    <h2>
                        Software <span style="color: #fff;">Recommendation</span>
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
                                    <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Full System
                                        Access
                                    </li>
                                    <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Priority
                                        Support
                                        24/7</li>
                                    <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Unlimited
                                        Storage
                                    </li>
                                    <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Free Updates
                                    </li>
                                </ul>
                                <div class="form-check mt-3">
                                    <input type="radio" id="yearly" name="billingOption" value="yearly"
                                        class="form-check-input custom-radio" required>
                                    <label for="yearly" class="form-check-label text-dark">Select Premium Plan</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="billing-option mb-4 p-4 bg-white rounded-lg shadow-lg hover-effect">
                                <h3 style="color: #231a6f;" class="mb-3">Standard Plan</h3>
                                <div class="price-tag">
                                    <h4 class="mb-3 text-dark">$150 <span class="text-muted">/month</span></h4>
                                </div>
                                <ul class="list-unstyled feature-list">
                                    <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Core Features
                                    </li>
                                    <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Standard
                                        Support
                                    </li>
                                    <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> 2GB Storage
                                    </li>
                                    <li class="mb-2"><i class="fa fa-check-circle text-success mr-2"></i> Monthly
                                        Updates
                                    </li>
                                </ul>
                                <div class="form-check mt-3">
                                    <input type="radio" id="monthly" name="billingOption" value="monthly"
                                        class="form-check-input custom-radio">
                                    <label for="monthly" class="form-check-label text-dark">Select Standard Plan</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Form -->
                    <div class="contact-form bg-white rounded-lg shadow-lg p-4 mt-4">
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
                                        <input type="tel" class="form-control custom-input" name="phone"
                                            pattern="[0-9]{9}" placeholder="Enter phone number " maxlength="9" required>
                                    </div>
                                    <small class="form-text text-danger">*Enter 9 digits without the country
                                        code</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control custom-input" name="schoolname"
                                        placeholder="Enter School Name" required>
                                </div>
                            </div>
                            <!-- Add this inside the contact-form div, before the submit button -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h4 style="color: #231a6f; padding-left: 20px;" class="mb-4">Payment Method</h4>
                                    <div class="payment-methods">
                                        <div class="row">
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="payment-option">
                                                    <input type="radio" name="paymentMethod" id="momo" value="momo"
                                                        required>
                                                    <label for="momo" class="payment-label">
                                                        <img src="../images/momo.png" alt="MTN Mobile Money"
                                                            class="payment-icon">
                                                        <span>MTN MoMo</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="payment-option">
                                                    <input type="radio" name="paymentMethod" id="airtel" value="airtel">
                                                    <label for="airtel" class="payment-label">
                                                        <img src="../images/airtel.png" alt="Airtel Money"
                                                            class="payment-icon">
                                                        <span>Airtel Money</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="payment-option">
                                                    <input type="radio" name="paymentMethod" id="visa" value="visa">
                                                    <label for="visa" class="payment-label">
                                                        <img src="../images/visa.png" alt="Visa Card" class="payment-icon">
                                                        <span>Visa Card</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="payment-option">
                                                    <input type="radio" name="paymentMethod" id="mastercard"
                                                        value="mastercard">
                                                    <label for="mastercard" class="payment-label">
                                                        <img src="../images/mastercard.png" alt="Mastercard"
                                                            class="payment-icon">
                                                        <span>Mastercard</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
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
                        </div>
                    </div>
                </form>
            </div>

        </section>
        <section class="footer_section">
            <div class="container">
                <p>
                    &copy; All Rights Reserved By
                    <a href="#">SMART-SAMS 2024</a> 
                </p>
            </div>
        </section>
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