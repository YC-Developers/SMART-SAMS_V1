<?php
// Admin's phone number
$adminPhoneNumber = '+250794897943'; // Replace with the actual admin phone number

// Textbelt API endpoint and key
$textbeltApiUrl = 'https://textbelt.com/text';
$textbeltApiKey = 'textbelt'; // Use 'textbelt' for the free key (limited messages per day)

// SMS content
$smsMessage = "New Recommendation Received!\n\n" .
              "Order ID: {$orderDetails['order_id']}\n" .
              "Billing Plan: {$orderDetails['billing_plan']}\n" .
              "School Name: {$orderDetails['school_name']}\n" .
              "Admin: {$orderDetails['school_admin']}\n" .
              "Phone: {$orderDetails['phone']}\n" .
              "Payment Method: {$orderDetails['payment_method']}\n" .
              "Comments: {$orderDetails['comments']}";

try {
    // Prepare SMS data
    $smsData = [
        'phone' => $adminPhoneNumber,
        'message' => $smsMessage,
        'key' => $textbeltApiKey
    ];

    // Send SMS using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $textbeltApiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($smsData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $responseDecoded = json_decode($response, true);

    // Check for success
    if ($responseDecoded['success']) {
        // Log successful SMS sending
        $logFile = $logsDir . '/sms_logs_' . date('Y-m') . '.log';
        $logEntry = date('Y-m-d H:i:s') . " SMS sent to $adminPhoneNumber for Order ID: {$orderDetails['order_id']}\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    } else {
        // Log failure response
        $errorLogFile = $logsDir . '/sms_errors.log';
        $errorLogEntry = date('Y-m-d H:i:s') . " SMS failed for Order ID: {$orderDetails['order_id']} - " . $responseDecoded['error'] . "\n";
        file_put_contents($errorLogFile, $errorLogEntry, FILE_APPEND);
    }
} catch (Exception $e) {
    // Log cURL error
    error_log(date('Y-m-d H:i:s') . " SMS Error for Order ID: {$orderDetails['order_id']} - " . $e->getMessage() . "\n", 3, $logsDir . '/sms_errors.log');
}