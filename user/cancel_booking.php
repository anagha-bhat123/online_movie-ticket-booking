<?php
session_start();
require '../vendor/autoload.php'; // PHPMailer
include('../includes/db.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user'])) {
    die("Please login to cancel your booking.");
}

$user_id = $_SESSION['user'];

if (!isset($_GET['booking_id']) || !is_numeric($_GET['booking_id'])) {
    die("Invalid booking ID.");
}

$booking_id = intval($_GET['booking_id']);

// Fetch booking details and verify ownership
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ? AND status = 'confirmed'");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    die("Booking not found or already cancelled.");
}

// Check cancellation deadline: show date must be at least 2 days ahead
$show_date = $booking['show_date'];
$today = new DateTime();
$showDateObj = new DateTime($show_date);
$interval = $today->diff($showDateObj)->days;

if ($showDateObj <= $today || $interval < 2) {
    die("Sorry, cancellation is allowed only if the show is at least 2 days away.");
}

// Update booking status to cancelled
$update_stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
$update_stmt->bind_param("i", $booking_id);
if (!$update_stmt->execute()) {
    die("Failed to cancel booking: " . $conn->error);
}

// Send cancellation confirmation email
// Fetch user email
$user_res = $conn->query("SELECT email, name FROM users WHERE id = $user_id");
$user = $user_res->fetch_assoc();

if (!$user) {
    die("User not found.");
}

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@gmail.com'; // your gmail
    $mail->Password = 'your_app_password';    // your app password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('noreply@getmyticket.com', 'Get My Ticket');
    $mail->addAddress($user['email'], $user['name']);

    $mail->isHTML(true);
    $mail->Subject = "Booking Cancelled - Get My Ticket";

    $mail->Body = "
        Hi <strong>{$user['name']}</strong>,<br><br>
        Your booking (ID: {$booking_id}) for <strong>{$booking['movie_name']}</strong> at <strong>{$booking['theater_name']}</strong> on <strong>{$booking['show_date']}</strong> ({$booking['show_time']}) has been <span style='color:red; font-weight:bold;'>cancelled</span> successfully.<br><br>
        If this was a mistake, please book again on our site.<br><br>
        Regards,<br>
        Get My Ticket Team
    ";

    $mail->send();

} catch (Exception $e) {
    // Log email sending failure but do not block cancellation success
    error_log("Mailer Error (Cancellation email): " . $mail->ErrorInfo);
}

echo "<h2>Booking Cancelled</h2>";
echo "<p>Your booking has been cancelled successfully. A confirmation email has been sent to {$user['email']}.</p>";
echo '<p><a href="user_dashboard.php">Go to My Bookings</a></p>';

?>
