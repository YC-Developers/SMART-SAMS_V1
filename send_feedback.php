<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $rating = $_POST['rating'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gisubizoaimeyannick@gmail.com'; // Replace with your email
        $mail->Password = 'nhlb yyxd ntuf oreg'; // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Recipients
        $mail->setFrom('gisubizoaimeyannick@gmail.com', 'Feedback System'); // Replace with your email
        $mail->addAddress('gisubizoaimeyannick@gmail.com'); // Replace with your email
        $mail->addReplyTo($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Feedback Received from smart-sams';
        $mail->Body = "
            <h2>Feedback Details</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Rating:</strong> {$rating}</p>
            <p><strong>Message:</strong> {$message}</p>
        ";

        $mail->send();
        echo "<script>alert('Thank you for your feedback!');</script>";
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
    }
}

echo "
<script>
    window.location.href = 'index.php';
</script>";
