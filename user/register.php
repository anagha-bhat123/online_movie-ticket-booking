<?php
session_start();
include('../includes/db.php');
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOtpEmail($email, $name, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'chandanag0508@gmail.com';   // Your Gmail SMTP email
        $mail->Password = 'qhiz boxt rzat nwwy';       // Your Gmail app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('noreply@getmyticket.com', 'Get My Ticket');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Get My Ticket Registration';

        $mail->Body = "
            <p>Hi <strong>" . htmlspecialchars($name) . "</strong>,</p>
            <p>Your OTP code is: <strong>{$otp}</strong></p>
            <p>This OTP will expire in 5 minutes.</p>
            <p>If you did not request this, please ignore this email.</p>
            <br>
            <p>Regards,<br>Get My Ticket Team</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("OTP email error: " . $mail->ErrorInfo);
        return false;
    }
}

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $error = "Email is already registered.";
    } else {
        $stmt->close();

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Save user data & OTP in session (temporarily)
        $_SESSION['reg_name'] = $name;
        $_SESSION['reg_email'] = $email;
        $_SESSION['reg_password'] = password_hash($password, PASSWORD_BCRYPT);
        $_SESSION['reg_otp'] = $otp;
        $_SESSION['otp_expires'] = time() + 300; // 5 minutes expiry

        // Send OTP email
        if (sendOtpEmail($email, $name, $otp)) {
            // Redirect to OTP verification page
            header("Location: verify_otp.php");
            exit;
        } else {
            $error = "Failed to send OTP email. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #111;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: #1a1a1a;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 59, 59, 0.3);
            width: 320px;
            text-align: center;
        }

        h2 {
            color: #ff3b3b;
            margin-bottom: 25px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid #ff3b3b;
            background-color: #2a2a2a;
            color: #fff;
            border-radius: 5px;
        }

        input::placeholder {
            color: #aaa;
        }

        button {
            background-color: #ff3b3b;
            color: white;
            padding: 10px 20px;
            border: none;
            margin-top: 15px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #b30000;
        }

        p {
            margin-top: 20px;
        }

        a {
            color: #ff3b3b;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .error {
            background: #ff3b3b;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <form method="post">
        <h2>User Registration</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <input type="text" name="name" placeholder="Full Name" required value="<?= isset($name) ? htmlspecialchars($name) : '' ?>"><br>
        <input type="email" name="email" placeholder="Email" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="register">Register</button>

        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
</body>
</html>
