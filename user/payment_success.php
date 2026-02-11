<?php
session_start();
include('../includes/db.php');
require '../vendor/autoload.php';

use Razorpay\Api\Api;
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Razorpay API keys
$keyId = 'rzp_test_746j3A9XYkAn5j';
$keySecret = 'dBZrhRDtikLVuvXpEgayravh';

if (!isset($_POST['razorpay_payment_id']) || !isset($_SESSION['razorpay_order_id'])) {
    die("❌ Payment verification failed.");
}

$api = new Api($keyId, $keySecret);

try {
    // Verify payment
    $payment = $api->payment->fetch($_POST['razorpay_payment_id']);
    if ($payment['status'] !== 'captured') {
        die("❌ Payment not successful.");
    }

    // Booking details from session
    $movie_id = $_SESSION['movie_id'];
    $theater_id = $_SESSION['theater_id'];
    $show_date = $_SESSION['show_date'];
    $show_time = $_SESSION['show_time'];
    $selected_seats = $_SESSION['seats'];
    $food_items = $_SESSION['food'] ?? [];
    $user_id = $_SESSION['user'];

    // 🟢 Step 1: Deduct food stock
    foreach ($food_items as $food_id => $qty) {
        $food_id = intval($food_id);
        $qty = intval($qty);
        if ($qty > 0) {
            $res = $conn->query("SELECT stock FROM food WHERE id = $food_id");
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                if ($row['stock'] >= $qty) {
                    $conn->query("UPDATE food SET stock = stock - $qty WHERE id = $food_id");
                } else {
                    error_log("⚠️ Not enough stock for food ID $food_id. Needed $qty, available {$row['stock']}");
                }
            }
        }
    }

    // Fetch movie, theater, user info
    $movie = $conn->query("SELECT title FROM movies WHERE id = $movie_id")->fetch_assoc();
    $theater = $conn->query("SELECT name FROM theaters WHERE id = $theater_id")->fetch_assoc();
    $user = $conn->query("SELECT name, email FROM users WHERE id = $user_id")->fetch_assoc();

    $seat_numbers = implode(", ", $selected_seats);
    $seat_total = count($selected_seats) * 150;

    // Prepare food text & total
    $food_text = "";
    $food_total = 0;
    if (!empty($food_items)) {
        $ids = implode(",", array_keys($food_items));
        $res = $conn->query("SELECT * FROM food WHERE id IN ($ids)");
        while ($row = $res->fetch_assoc()) {
            $qty = $food_items[$row['id']];
            $food_text .= "{$row['name']} x{$qty}, ";
            $food_total += $row['price'] * $qty;
        }
        $food_text = rtrim($food_text, ', ');
    } else {
        $food_text = "None";
    }

    $grand_total = $seat_total + $food_total;

    // Insert booking into DB
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, movie_name, theater_name, seat_number, food_items, total_amount, show_date, show_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $user_id, $movie['title'], $theater['name'], $seat_numbers, $food_text, $grand_total, $show_date, $show_time);
    $stmt->execute();
    $booking_id = $stmt->insert_id;
    $stmt->close();

    $_SESSION['booking_id'] = $booking_id;

    // Generate PDF receipt
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    $html = "
    <h2> Booking Receipt</h2>
    <p><strong>Booking ID:</strong> {$booking_id}</p>
    <p><strong>Movie:</strong> {$movie['title']}</p>
    <p><strong>Theater:</strong> {$theater['name']}</p>
    <p><strong>Date:</strong> {$show_date}</p>
    <p><strong>Time:</strong> {$show_time}</p>
    <p><strong>Seats:</strong> {$seat_numbers}</p>
    <p><strong>Food:</strong> {$food_text}</p>
    <p><strong>Total Paid:</strong> ₹{$grand_total}</p>
    ";

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdf_path = __DIR__ . "/receipt_{$booking_id}.pdf";
    file_put_contents($pdf_path, $dompdf->output());

    // Send email with PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'chandanag0508@gmail.com';
    $mail->Password = 'qhiz boxt rzat nwwy';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('noreply@getmyticket.com', 'Get My Ticket');
    $mail->addAddress($user['email'], $user['name']);
    $mail->isHTML(true);
    $mail->Subject = " Booking Receipt - Get My Ticket";

    $cancel_link = "http://localhost/online_movie/user/cancel_booking.php?booking_id=$booking_id";
    $user_name = htmlspecialchars($user['name']);

    $mail->Body = "
        Hi <strong>{$user_name}</strong>,<br><br>
        Thank you for your booking! Here are your details:<br><br>
        <strong>Movie:</strong> {$movie['title']}<br>
        <strong>Theater:</strong> {$theater['name']}<br>
        <strong>Show:</strong> {$show_date} @ {$show_time}<br>
        <strong>Seats:</strong> {$seat_numbers}<br>
        <strong>Food:</strong> " . (!empty($food_text) ? $food_text : "None") . "<br>
        <strong>Total:</strong> ₹{$grand_total}<br><br>

        🔽 Your receipt is attached.<br>
        ❌ <a href=\"$cancel_link\" style=\"color: red; font-weight: bold;\">Click here to cancel your booking</a><br>
        <small>You can cancel only if the show is at least 2 days away.</small><br><br>

        Regards,<br>
        Get My Ticket Team
    ";

    $mail->addAttachment($pdf_path);
    $mail->send();
    unlink($pdf_path); // cleanup

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background:rgb(16, 16, 16);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: #ffffff;
            border: 2px solid #4CAF50;
            border-radius: 12px;
            padding: 30px 40px;
            max-width: 500px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }
        p {
            margin: 10px 0;
            font-size: 16px;
        }
        strong {
            color: #333;
        }
        a {
            display: inline-block;
            margin: 15px 10px 0 10px;
            background: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        a:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>✅ Payment Successful!</h2>
    <p>Thank you for your booking.</p>
    <p><strong>Booking ID:</strong> <?= htmlspecialchars($booking_id) ?></p>
    <p><strong>Movie:</strong> <?= htmlspecialchars($movie['title']) ?></p>
    <p><strong>Theater:</strong> <?= htmlspecialchars($theater['name']) ?></p>
    <p><strong>Seats:</strong> <?= htmlspecialchars($seat_numbers) ?></p>
    <p><strong>Food:</strong> <?= htmlspecialchars($food_text) ?></p>
    <p><strong>Total Paid:</strong> ₹<?= htmlspecialchars($grand_total) ?></p>

    <a href="receipt.php?booking_id=<?= urlencode($booking_id) ?>">📄 View Receipt</a>
    <a href="index.php">🎬 Book Another Ticket</a>
</div>
</body>
</html>
