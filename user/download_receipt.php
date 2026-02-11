<?php
session_start();
include('../includes/db.php');

if (!isset($_GET['booking_id'])) {
    die("No booking specified");
}

$booking_id = intval($_GET['booking_id']);

$booking = $conn->query("SELECT * FROM bookings WHERE id = $booking_id")->fetch_assoc();
if (!$booking) die("Booking not found");

require '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$receipt_html = "
<h2>🎟️ Booking Receipt</h2>
<p><strong>Booking ID:</strong> {$booking['id']}</p>
<p><strong>Movie:</strong> {$booking['movie_name']}</p>
<p><strong>Theater:</strong> {$booking['theater_name']}</p>
<p><strong>Date:</strong> {$booking['show_date']}</p>
<p><strong>Time:</strong> {$booking['show_time']}</p>
<p><strong>Seats:</strong> {$booking['seat_number']}</p>
<p><strong>Food:</strong> " . (!empty($booking['food_items']) ? $booking['food_items'] : "None") . "</p>
<p><strong>Total Paid:</strong> ₹{$booking['total_amount']}</p>
";

$dompdf->loadHtml($receipt_html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("BookingReceipt_{$booking_id}.pdf", ['Attachment' => true]);
