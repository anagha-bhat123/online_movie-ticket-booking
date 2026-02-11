<?php
session_start();
include('../includes/db.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Adjust path if needed

if (!isset($_SESSION['reg_email'], $_SESSION['reg_name'], $_SESSION['reg_password'])) {
    header("Location: register.php");
    exit;
}

// Generate new OTP & expiry
$otp = rand(100000, 999999);
$_SESSION['reg_otp'] = $otp;
$_SESSION['otp_expires'] = time() + 300; // 5 minutes from now

// Send OTP email
$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';     // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com';  // SMTP username
    $mail->Password = 'your_email_password';     // SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    //Recipients
    $mail->setFrom('your_email@example.com', 'Your Website');
    $mail->addAddress($_SESSION['reg_email'], $_SESSION['reg_name']);

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code';
    $mail->Body    = "<p>Hello " . htmlspecialchars($_SESSION['reg_name']) . ",</p>
                      <p>Your new OTP code is: <b>$otp</b></p>
                      <p>This code is valid for 5 minutes.</p>";

    $mail->send();

    $_SESSION['otp_message'] = "A new OTP has been sent to your email.";
} catch (Exception $e) {
    $_SESSION['otp_message'] = "Could not send OTP email. Mailer Error: {$mail->ErrorInfo}";
}

header("Location: verify_otp.php");
exit;
