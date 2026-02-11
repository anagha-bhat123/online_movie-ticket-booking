<?php
session_start();
include('../includes/db.php');

$error = '';

if (!isset($_SESSION['reg_name'], $_SESSION['reg_email'], $_SESSION['reg_password'], $_SESSION['reg_otp'], $_SESSION['otp_expires'])) {
    // No registration data in session, redirect to register
    header("Location: register.php");
    exit;
}

if (time() > $_SESSION['otp_expires']) {
    // OTP expired, clear session data and ask user to register again
    session_unset();
    $error = "OTP expired. Please register again.";
}

if (isset($_POST['verify'])) {
    $userOtp = trim($_POST['otp']);

    if ($userOtp === '') {
        $error = "Please enter the OTP.";
    } elseif ($userOtp != $_SESSION['reg_otp']) {
        $error = "Invalid OTP. Please try again.";
    } elseif (time() > $_SESSION['otp_expires']) {
        $error = "OTP expired. Please register again.";
    } else {
        // OTP valid, insert user into DB
        $name = $_SESSION['reg_name'];
        $email = $_SESSION['reg_email'];
        $password = $_SESSION['reg_password'];

        // Check again email doesn't exist (optional safety)
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            session_unset();
            $error = "Email already registered. Please login.";
        } else {
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);
            if ($stmt->execute()) {
                $stmt->close();
                session_unset(); // Clear registration session data
                header("Location: login.php?registered=1");
                exit;
            } else {
                $error = "Database error. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
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
        input[type="text"] {
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
        .error {
            background: #ff3b3b;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
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
        .success {
    background: #4CAF50;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    color: #fff;
}
    </style>
</head>
<body>
    <form method="post">
        <h2>Verify OTP</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <p>Please enter the 6-digit OTP sent to <strong><?= htmlspecialchars($_SESSION['reg_email']) ?></strong></p>
        <input type="text" name="otp" maxlength="6" placeholder="Enter OTP" required autofocus><br>

        <button type="submit" name="verify">Verify</button>

        <p>Didn't receive OTP? <a href="resend_otp.php">Resend OTP</a></p>
        <p><a href="register.php">Cancel and Register again</a></p>
    </form>
    <?php if (isset($_SESSION['otp_message'])): ?>
    <div class="success" style="background:#4CAF50; padding:10px; border-radius:5px; margin-bottom:15px; color:#fff;">
        <?= htmlspecialchars($_SESSION['otp_message']); ?>
    </div>
    <?php unset($_SESSION['otp_message']); ?>
<?php endif; ?>

</body>
</html>
