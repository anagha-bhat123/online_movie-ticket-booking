<?php
session_start();
include('../includes/db.php');
require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['booking_id'])) {
    die("❌ Booking ID is required.");
}

$booking_id = intval($_GET['booking_id']);

// Fetch booking details
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) {
    die("❌ Booking not found.");
}

// Generate PDF receipt HTML
$html = "
<h2>🎟️ Booking Receipt</h2>
<p><strong>Booking ID:</strong> {$booking['id']}</p>
<p><strong>Movie:</strong> {$booking['movie_name']}</p>
<p><strong>Theater:</strong> {$booking['theater_name']}</p>
<p><strong>Date:</strong> {$booking['show_date']}</p>
<p><strong>Time:</strong> {$booking['show_time']}</p>
<p><strong>Seats:</strong> {$booking['seat_number']}</p>
<p><strong>Food:</strong> {$booking['food_items']}</p>
<p><strong>Total Paid:</strong> ₹{$booking['total_amount']}</p>
";

// Render PDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Stream the PDF as download
$dompdf->stream("receipt_booking_{$booking_id}.pdf", ["Attachment" => true]);
exit;
?>
