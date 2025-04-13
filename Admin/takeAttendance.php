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
    <title>QR Code Scanner | Dashboard</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    
    <style>
        .scanner-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 20px auto;
            max-width: 800px;
        }
        .scanner-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        #reader {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            border: 2px dashed #4e73df;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }
        #reader::after {
            content: "Align QR code within frame";
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            color: #fff;
            font-weight: bold;
            text-shadow: 0 0 5px rgba(0,0,0,0.5);
        }
        .scanner-instructions {
            background-color: #f8f9fc;
            border-left: 4px solid #4e73df;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .scanner-footer {
            margin-top: 20px;
            text-align: center;
            color: #6c757d;
        }
        #error {
            transition: all 0.3s ease;
        }
        .manual-entry-btn {
            margin-top: 15px;
        }
        .scanner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            border-radius: 8px;
        }
        .scanner-overlay-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
    </style>
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
                
                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">QR Code Scanner</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Scan Class QR Code</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="scanner-container">
                                <div class="scanner-header">
                                    <h4 class="text-primary font-weight-bold">
                                        <i class="fas fa-qrcode mr-2"></i>Class Attendance Scanner
                                    </h4>
                                </div>
                                
                                <div class="scanner-instructions">
                                    <h6><i class="fas fa-info-circle mr-2 text-primary"></i>How to scan:</h6>
                                    <ol>
                                        <li>Point your camera at the class QR code</li>
                                        <li>Hold steady until the code is recognized</li>
                                        <li>You'll be automatically redirected to the attendance page</li>
                                    </ol>
                                </div>
                                
                                <!-- QR Code Scanner -->
                                <script src="https://unpkg.com/html5-qrcode"></script>
                                <div id="reader"></div>
                                <div id="error" class="text-center mt-3"></div>
                                
                                <div class="text-center mt-4">
                                    <button id="toggleCameraBtn" class="btn btn-secondary btn-sm mr-2">
                                        <i class="fas fa-sync-alt"></i> Switch Camera
                                    </button>
                                    <button id="manualEntryBtn" class="btn btn-outline-primary btn-sm manual-entry-btn">
                                        <i class="fas fa-keyboard"></i> Manual Entry
                                    </button>
                                </div>
                                
                                <div class="scanner-footer">
                                    <small>Ensure proper lighting and hold the QR code within the frame</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container Fluid Ends -->
                
                <!-- Footer -->
                <?php include "Includes/footer.php"; ?>
                <!-- Footer -->
            </div>
        </div>

        <!-- Scroll to top -->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Manual Entry Modal -->
        <div class="modal fade" id="manualEntryModal" tabindex="-1" role="dialog" aria-labelledby="manualEntryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="manualEntryModalLabel">Manual Class Entry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="manualEntryForm">
                            <div class="form-group">
                                <label for="classSelect">Select Class</label>
                                <select class="form-control" id="classSelect">
                                    <?php
                                    $a = "SELECT * FROM schools WHERE schoolName='$s'";
                                    $r = $conn->query($a);
                                    $ro = $r->fetch_assoc();
                                    $schoolCode = $ro['code'];
                                    
                                    $sql = "SELECT className FROM tblclass where school='$s'";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='{$schoolCode}{$row['className']}'>{$row['className']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmManualEntry">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="js/ruang-admin.min.js"></script>
        <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
        
        <script>
            $(document).ready(function () {
                $('#dataTable').DataTable();
                $('#dataTableHover').DataTable();
                
                // Fetch class names from PHP
                const validClassNames = <?php 
                    $a = "SELECT * FROM schools WHERE schoolName='$s'";
                    $r = $conn->query($a);
                    $ro = $r->fetch_assoc();
                    $schoolCode = $ro['code'];
                    
                    $sql = "SELECT className FROM tblclass where school='$s'";
                    $result = $conn->query($sql);
                    $classes = [];
                    while ($row = $result->fetch_assoc()) {
                        $classes[] = $schoolCode.$row['className'];
                    }
                    echo json_encode($classes);
                ?>;
                
                let currentFacingMode = "environment";
                let html5QrCode;
                
                // Initialize the QR scanner
                function startQRScanner(facingMode = "environment") {
                    function onScanSuccess(decodedText, decodedResult) {
                        console.log(`Code scanned: ${decodedText}`);
                        if (validClassNames.includes(decodedText)) {
                            // Show success overlay
                            const reader = document.getElementById("reader");
                            const overlay = document.createElement("div");
                            overlay.className = "scanner-overlay";
                            overlay.innerHTML = `
                                <div class="scanner-overlay-content">
                                    <h4 class="text-success"><i class="fas fa-check-circle"></i> Success!</h4>
                                    <p>Redirecting to class attendance...</p>
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            `;
                            reader.appendChild(overlay);
                            
                            // Redirect after short delay
                            setTimeout(() => {
                                window.location.href = `classat.php?data=${encodeURIComponent(decodedText)}`;
                            }, 1500);
                        } else {
                            document.getElementById("error").innerHTML = `
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Invalid QR Code!</strong> This is not a valid class QR code.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            `;
                        }
                    }

                    function onScanError(errorMessage) {
                        console.error("Scan error:", errorMessage);
                        // Don't show error message to user unless it's critical
                    }

                    // If scanner already exists, stop it first
                    if (html5QrCode) {
                        html5QrCode.stop();
                    }
                    
                    html5QrCode = new Html5Qrcode("reader");
                    html5QrCode.start(
                        { facingMode: facingMode },
                        { fps: 10, qrbox: 250 },
                        onScanSuccess,
                        onScanError
                    ).catch(err => {
                        console.error("Error initializing QR Code scanner:", err);
                        document.getElementById("error").innerHTML = `
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Camera access required!</strong> Please allow camera permissions to scan QR codes.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                    });
                }

                // Toggle between front and back camera
                $("#toggleCameraBtn").click(function() {
                    currentFacingMode = currentFacingMode === "environment" ? "user" : "environment";
                    startQRScanner(currentFacingMode);
                });
                
                // Manual entry button
                $("#manualEntryBtn").click(function() {
                    $("#manualEntryModal").modal("show");
                });
                
                // Confirm manual entry
                $("#confirmManualEntry").click(function() {
                    const selectedClass = $("#classSelect").val();
                    if (validClassNames.includes(selectedClass)) {
                        window.location.href = `classat.php?data=${encodeURIComponent(selectedClass)}`;
                    }
                });
                
                // Start QR scanner on page load
                startQRScanner();
            });
        </script>
    </body>
</html>