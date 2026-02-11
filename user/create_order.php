<?php
session_start();
require '../vendor/autoload.php'; // For Razorpay SDK via composer
include('../includes/db.php');

use Razorpay\Api\Api;

if (!isset($_SESSION['user'], $_SESSION['movie_id'], $_SESSION['theater_id'], $_SESSION['show_date'], $_SESSION['show_time'], $_SESSION['seats'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing booking data']);
    exit;
}

$apiKey = 'YOUR_RAZORPAY_KEY';      // Replace with your Razorpay Key ID
$apiSecret = 'YOUR_RAZORPAY_SECRET';// Replace with your Razorpay Secret
$api = new Api($apiKey, $apiSecret);

$grand_total = $_POST['amount'] ?? 0;
if ($grand_total <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

$amount_paise = $grand_total * 100; // Convert to paise

$orderData = [
    'receipt'         => 'rcpt_' . time(),
    'amount'          => $amount_paise, // amount in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

try {
    $razorpayOrder = $api->order->create($orderData);
    $_SESSION['razorpay_order_id'] = $razorpayOrder['id'];
    echo json_encode(['order_id' => $razorpayOrder['id'], 'amount' => $amount_paise, 'currency' => 'INR']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
