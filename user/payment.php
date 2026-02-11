<?php
session_start();
include('../includes/db.php');
require '../vendor/autoload.php';

use Razorpay\Api\Api;

if (!isset($_SESSION['seats'], $_SESSION['movie_id'], $_SESSION['theater_id'], $_SESSION['show_date'], $_SESSION['show_time'], $_SESSION['user'])) {
    die("Missing booking info in session.");
}

$movie_id = $_SESSION['movie_id'];
$theater_id = $_SESSION['theater_id'];
$show_date = $_SESSION['show_date'];
$show_time = $_SESSION['show_time'];
$selected_seats = $_SESSION['seats'];
$food_items = $_SESSION['food'] ?? [];
$user_id = $_SESSION['user'];

// Calculate total price
$seat_total = count($selected_seats) * 150;
$food_total = 0;
if (!empty($food_items)) {
    $ids = implode(",", array_keys($food_items));
    $res = $conn->query("SELECT id, price FROM food WHERE id IN ($ids)");
    while ($row = $res->fetch_assoc()) {
        $food_total += $row['price'] * $food_items[$row['id']];
    }
}
$grand_total = $seat_total + $food_total;
$amount_in_paise = $grand_total * 100;

$_SESSION['grand_total'] = $grand_total;

// Razorpay API keys - replace with your keys
$keyId = 'rzp_test_746j3A9XYkAn5j';
$keySecret = 'dBZrhRDtikLVuvXpEgayravh';

$api = new Api($keyId, $keySecret);

$order = $api->order->create([
    'receipt' => uniqid('receipt_'),
    'amount' => $amount_in_paise,
    'currency' => 'INR'
]);

$_SESSION['razorpay_order_id'] = $order['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make Payment</title>
</head>
<body>
<style>
     * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #0f2027, #203a43);
      color: #fff;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .payment-container {
      background: #1c1c1c;
      padding: 40px 50px;
      border-radius: 20px;
      box-shadow: 0 15px 40px rgba(255, 76, 96, 0.6);
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    h2 {
      font-size: 2.4rem;
      margin-bottom: 24px;
      color: #ff4c60;
      text-shadow: 0 0 10px #ff4c60aa;
    }

    p {
      font-size: 1.3rem;
      margin-bottom: 36px;
      font-weight: 600;
      letter-spacing: 0.03em;
    }

    /* Razorpay button styling override */
    button.razorpay-payment-button {
      background: #ff4c60 !important;
      color: white !important;
      font-weight: 700 !important;
      font-size: 1.2rem !important;
      padding: 16px 0 !important;
      border-radius: 12px !important;
      border: none !important;
      width: 100% !important;
      cursor: pointer !important;
      box-shadow: 0 10px 30px rgba(255, 76, 96, 0.9) !important;
      transition: background-color 0.3s ease !important;
    }

    button.razorpay-payment-button:hover {
      background: #e04357 !important;
    }

  </style>
  <div class="payment-container">
    <h2>Confirm and Pay</h2>
    <p><strong>Total Amount: ₹<?= $grand_total ?></strong></p>

    <form action="payment_success.php" method="POST">
      <script
        src="https://checkout.razorpay.com/v1/checkout.js"
        data-key="<?= $keyId ?>"
        data-amount="<?= $amount_in_paise ?>"
        data-currency="INR"
        data-order_id="<?= $order['id'] ?>"
        data-buttontext="Pay ₹<?= $grand_total ?>"
        data-name="Get My Ticket"
        data-description="Movie Ticket Booking"
        data-theme.color="#ff4c60"
      ></script>
      <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" />
    </form>
  </div>
</body>

</body>
</html>
