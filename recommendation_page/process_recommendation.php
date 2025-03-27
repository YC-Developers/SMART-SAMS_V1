<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use Twilio\Rest\Client;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Input validation and sanitization
    $billingOption = filter_input(INPUT_POST, 'billingOption', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);
    $schoolName = filter_input(INPUT_POST, 'schoolname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $paymentMethod = filter_input(INPUT_POST, 'paymentMethod', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validation checks
    $errors = [];
    if (!$email) $errors[] = "Valid email is required";
    if (!$name) $errors[] = "Name is required";
    if (!$phone) $errors[] = "Phone number is required";
    if (!$schoolName) $errors[] = "School name is required";
    if (!$billingOption) $errors[] = "Billing option is required";
    if (!$paymentMethod) $errors[] = "Payment method is required";

    if (!empty($errors)) {
        session_start();
        $_SESSION['order_error'] = implode(", ", $errors);
        header("Location: index.php#recommendation-section");
        exit();
    }

    // Create logs directory
    $logsDir = __DIR__ . '/logs';
    if (!is_dir($logsDir)) {
        mkdir($logsDir, 0755, true);
    }

    // Order details with unique identifier
    $orderDetails = [
        'order_id' => uniqid('SMART_'),
        'timestamp' => date('Y-m-d H:i:s'),
        'billing_plan' => $billingOption,
        'school_admin' => $name,
        'email' => $email,
        'phone' => "+250" . $phone,
        'school_name' => $schoolName,
        'payment_method' => $paymentMethod,
        'comments' => $comments,
        'status' => 'pending'
    ];

    // Save order details
    $logFile = $logsDir . '/orders_' . date('Y-m') . '.json';
    $currentLogs = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
    $currentLogs[] = $orderDetails;
    file_put_contents($logFile, json_encode($currentLogs, JSON_PRETTY_PRINT));

    // Configure shared SMTP settings
    $smtpSettings = [
        'host' => 'smtp.gmail.com',
        'username' => 'gisubizoaimeyannick@gmail.com',
        'password' => 'nhlb yyxd ntuf oreg',
        'port' => 587
    ];

    // Function to configure PHPMailer instance
    function configureMailer($mailer, $settings)
    {
        $mailer->SMTPDebug = SMTP::DEBUG_OFF;
        $mailer->isSMTP();
        $mailer->Host = $settings['host'];
        $mailer->SMTPAuth = true;
        $mailer->Username = $settings['username'];
        $mailer->Password = $settings['password'];
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Port = $settings['port'];
        $mailer->CharSet = 'UTF-8';
    }

    try {
        // Send client confirmation email
        $clientMail = new PHPMailer(true);
        configureMailer($clientMail, $smtpSettings);

        $clientMail->setFrom('gisubizoaimeyannick@gmail.com', 'SMART-SAMS System');
        $clientMail->addAddress($email, $name);
        $clientMail->addReplyTo('gisubizoaimeyannick@gmail.com', 'SMART-SAMS Support');

        $clientMail->isHTML(true);
        $clientMail->Subject = "SMART-SAMS Order Confirmation - {$orderDetails['order_id']}";

        // Client email template
        $emailContent = "
            <h2>Order Confirmation</h2>
            <p>Thank you for your order!</p>
            <p><strong>Order ID:</strong> {$orderDetails['order_id']}</p>
            <p><strong>Billing Plan:</strong> $billingOption</p>
            <p><strong>School Name:</strong> $schoolName</p>
            <p><strong>Payment Method:</strong> $paymentMethod</p>
            <p><strong>Contact Details:</strong><br>
            Name: $name<br>
            Email: $email<br>
            Phone: +250$phone</p>
            <p><strong>Additional Comments:</strong><br>$comments</p>
            <p>SMART-SAMS will process your order and get back to you shortly.</p>
        ";

        $clientMail->Body = $emailContent;
        $clientMail->AltBody = strip_tags($emailContent);
        $clientMail->send();

        // Send company notification
        $companyMail = new PHPMailer(true);
        configureMailer($companyMail, $smtpSettings);

        $companyMail->setFrom('gisubizoaimeyannick@gmail.com', 'SMART-SAMS System');
        $companyMail->addAddress('gisubizoaimeyannick@gmail.com', 'SMART-SAMS Admin');

        $companyMail->isHTML(true);
        $companyMail->Subject = "New SMART-SAMS Order Received - {$orderDetails['order_id']}";

        $companyEmailContent = "
            <h2>New Order Notification</h2>
            <p>A new order has been received with the following details:</p>
            <p><strong>Order ID:</strong> {$orderDetails['order_id']}</p>
            <p><strong>Timestamp:</strong> {$orderDetails['timestamp']}</p>
            <p><strong>Billing Plan:</strong> $billingOption</p>
            <p><strong>School Details:</strong><br>
            School Name: $schoolName</p>
            <p><strong>Client Details:</strong><br>
            Name: $name<br>
            Email: $email<br>
            Phone: +250$phone</p>
            <p><strong>Payment Method:</strong> $paymentMethod</p>
            <p><strong>Additional Comments:</strong><br>$comments</p>
            <p>Please process this order accordingly.</p>
        ";

        $companyMail->Body = $companyEmailContent;
        $companyMail->AltBody = strip_tags($companyEmailContent);
        $companyMail->send();

        // Twilio credentials
        $accountSid = 'AC97727609964c2bc2f766c673bae0e886';
        $authToken = '02a2480dc52ce0d33f3152d2952bee41';
        $twilioPhoneNumber = '+12185876968';

        // SMS content
        $smsMessage = "New Recommendation Received!\n\n" .
            "Order ID: {$orderDetails['order_id']}\n" .
            "Billing Plan: {$orderDetails['billing_plan']}\n" .
            "School Name: {$orderDetails['school_name']}\n" .
            "Admin: {$orderDetails['school_admin']}\n" .
            "Phone: {$orderDetails['phone']}\n" .
            "Payment Method: {$orderDetails['payment_method']}\n" .
            "Comments: {$orderDetails['comments']}";

        // Send SMS using Twilio
        $adminPhoneNumber = '+25079489793'; // Ensure this is a valid E.164 formatted number
        $client = new Client($accountSid, $authToken);
        try {
            $message = $client->messages->create(
                $adminPhoneNumber,
                [
                    'from' => $twilioPhoneNumber,
                    'body' => $smsMessage
                ]
            );

            // Log successful SMS sending
            $logFile = $logsDir . '/sms_logs_' . date('Y-m') . '.log';
            $logEntry = date('Y-m-d H:i:s') . " SMS sent to $adminPhoneNumber for Order ID: {$orderDetails['order_id']}\n";
            file_put_contents($logFile, $logEntry, FILE_APPEND);

            session_start();
            $_SESSION['order_success'] = true;
            $_SESSION['order_id'] = $orderDetails['order_id'];
            header("Location: index.php");
            exit();
        } catch (Twilio\Exceptions\RestException $e) {
            error_log(date('Y-m-d H:i:s') . " Order ID: {$orderDetails['order_id']} - Twilio Error: " . $e->getMessage() . "\n", 3, $logsDir . '/sms_errors.log');

            session_start();
            $_SESSION['order_error'] = "There was an error sending the SMS for your order (ID: {$orderDetails['order_id']}). Our team will contact you shortly.";
            header("location: index.php");
            exit();
        }
    } catch (Exception $e) {
        error_log(date('Y-m-d H:i:s') . " Order ID: {$orderDetails['order_id']} - Email Error: " . $e->getMessage() . "\n", 3, $logsDir . '/email_errors.log');

        session_start();
        $_SESSION['order_error'] = "Your order (ID: {$orderDetails['order_id']}) has been received. Our team will contact you shortly.";
        header("Location: index.php");
        exit();
    }
}
