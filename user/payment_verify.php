<?php
session_start();
require '../vendor/autoload.php'; // PHPMailer + Dompdf
include('../includes/db.php');

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['payment_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$payment_id = $_POST['payment_id'];
$user_id = $_SESSION['user'];

// Fetch session booking data
if (!isset($_SESSION['movie_id'], $_SESSION['theater_id'], $_SESSION['show_date'], $_SESSION['show_time'], $_SESSION['seats'])) {
    echo json_encode(['status' => 'error', 'message' => 'Booking data missing']);
    exit;
}

$movie_id = $_SESSION['movie_id'];
$theater_id = $_SESSION['theater_id'];
$show_date = $_SESSION['show_date'];
$show_time = $_SESSION['show_time'];
$selected_seats = $_SESSION['seats'];
$food_items = $_SESSION['food'] ?? [];

// Fetch movie, theater info
$movie = $conn->query("SELECT title FROM movies WHERE id = $movie_id")->fetch_assoc();
$theater = $conn->query("SELECT name FROM theaters WHERE id = $theater_id")->fetch_assoc();

if (!$movie || !$theater) {
    echo json_encode(['status' => 'error', 'message' => 'Movie or theater not found']);
    exit;
}

// Calculate totals
$seat_price = 150;
$seat_total = count($selected_seats) * $seat_price;

$food_total = 0;
$food_text = "";
if (!empty($food_items)) {
    $food_ids = implode(',', array_keys($food_items));
    $res = $conn->query("SELECT * FROM food WHERE id IN ($food_ids)");
    while ($row = $res->fetch_assoc()) {
        $qty = intval($food_items[$row['id']]);
        if ($qty > 0) {
            $subtotal = $qty * $row['price'];
            $food_total += $subtotal;
            $food_text .= "{$row['name']} x{$qty}, ";
        }
    }
    $food_text = rtrim($food_text, ", ");
}
$seat_numbers = implode(", ", $selected_seats);
$grand_total = $seat_total + $food_total;

// Insert booking into database
$stmt = $conn->prepare("INSERT INTO bookings (user_id, movie_name, theater_name, seat_number, food_items, total_amount, show_date, show_time, payment_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "isssssiss",
    $user_id,
    $movie['title'],
    $theater['name'],
    $seat_numbers,
    $food_text,
    $grand_total,
    $show_date,
    $show_time,
    $payment_id
);
if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Database insert error: ' . $stmt->error]);
    exit;
}

$booking_id = $stmt->insert_id;

// Generate PDF receipt
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$receipt_html = "
<h2>Booking Receipt</h2>
<p><strong>Booking ID:</strong> {$booking_id}</p>
<p><strong>Movie:</strong> {$movie['title']}</p>
<p><strong>Theater:</strong> {$theater['name']}</p>
<p><strong>Date:</strong> {$show_date}</p>
<p><strong>Time:</strong> {$show_time}</p>
<p><strong>Seats:</strong> {$seat_numbers}</p>
<p><strong>Food:</strong> " . (!empty($food_text) ? $food_text : "None") . "</p>
<p><strong>Total Paid:</strong> ₹{$grand_total}</p>
";

$dompdf->loadHtml($receipt_html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$pdf_path = __DIR__ . "/receipt_{$booking_id}.pdf";
file_put_contents($pdf_path, $dompdf->output());

// Send email
$user = $conn->query("SELECT name, email FROM users WHERE id = $user_id")->fetch_assoc();
if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    unlink($pdf_path);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'YOUR_EMAIL@gmail.com';  // Your Gmail
    $mail->Password = 'YOUR_APP_PASSWORD';     // Gmail App password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('noreply@getmyticket.com', 'Get My Ticket');
    $mail->addAddress($user['email'], $user['name']);

    $cancel_link = "http://yourdomain.com/cancel_booking.php?booking_id={$booking_id}";

    $mail->isHTML(true);
    $mail->Subject = 'Booking Receipt - Get My Ticket';
    $mail->Body = "
        <p>Hi {$user['name']},</p>
        <p>Thank you for your booking. Your payment was successful.</p>
        {$receipt_html}
        <p><a href='{$cancel_link}'>Click here to cancel your booking (if allowed).</a></p>
        <p>Best regards,<br>Get My Ticket Team</p>
    ";
    $mail->addAttachment($pdf_path);

    $mail->send();
} catch (Exception $e) {
    // Log error but don't block success
}

// Cleanup
unlink($pdf_path);

// Clear session booking data
unset($_SESSION['movie_id'], $_SESSION['theater_id'], $_SESSION['show_date'], $_SESSION['show_time'], $_SESSION['seats'], $_SESSION['food']);

echo json_encode(['status' => 'success']);
