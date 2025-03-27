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
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include "Includes/sidebar.php"; ?>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include "Includes/topbar.php"; ?>
                <!-- Topbar -->
                
                <!-- QR Code Scanner -->
                <script src="https://unpkg.com/html5-qrcode"></script>
                <div id="video-container">
                <h3 style="margin-top: -150px;"></h3>
                    <!-- Video element for fallback -->
                    <video id="video" width="300" height="200" autoplay></video>
                </div>
                <h3 style="margin-left: 70px; color:#231a6f;">Scan Class Qr code</h3>

                <div id="reader" style="width: 350px; margin-left: 16px "></div>
                <div id="error"></div>

                <script>
                    // Fetch class names from PHP
                    const validClassNames = <?php 
                        $sql = "SELECT className FROM tblclass where school='$s'";
                        $result = $conn->query($sql);
                        $classes = [];
                        while ($row = $result->fetch_assoc()) {
                            $classes[] = $row['className'];
                        }
                        echo json_encode($classes);
                    ?>;

                    // Initialize the QR scanner
                    function startQRScanner() {
                        function onScanSuccess(decodedText, decodedResult) {
                            console.log(`Code scanned: ${decodedText}`);
                            if (validClassNames.includes(decodedText)) {
                                window.location.href = `classat.php?data=${encodeURIComponent(decodedText)}`;
                            } else {
                                document.getElementById("error").innerHTML = 
                                    "<div class='alert alert-danger' role='alert' width='300px'>Invalid Class QR Code</div>";
                            }
                        }

                        function onScanError(errorMessage) {
                            console.error("Scan error:", errorMessage);
                        }

                        const html5QrCode = new Html5Qrcode("reader");
                        html5QrCode.start(
                            { facingMode: "environment" },
                            { fps: 10, qrbox: 250 },
                            onScanSuccess,
                            onScanError
                        ).catch(err => {
                            console.error("Error initializing QR Code scanner:", err);
                            openCameraFallback();
                        });
                    }

                    // Fallback to manual camera access
                    function openCameraFallback() {
                        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                            navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                                .then(function (stream) {
                                    const video = document.getElementById("video");
                                    video.srcObject = stream;
                                    video.style.display = "block";
                                    video.play();
                                })
                                .catch(function (error) {
                                    console.error("Error accessing camera:", error);
                                    alert("Could not open the camera. Please check permissions and compatibility.");
                                });
                        } else {
                            alert("Camera access is not supported by your browser.");
                        }
                    }

                    // Start QR scanner on page load
                    window.onload = startQRScanner;
                </script>
                </div>

                <!-- Footer -->
                <?php include "Includes/footer.php"; ?>
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
        <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTable').DataTable(); // ID From dataTable
                $('#dataTableHover').DataTable(); // ID From dataTable with Hover
            });
        </script>
    </body>
</html>
