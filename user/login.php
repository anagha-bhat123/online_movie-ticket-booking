<!-- filepath: c:\xampp\htdocs\online_movie\user\login.php -->
<?php
session_start();
include('../includes/db.php');
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user'] = $row['id'];
             $_SESSION['user_id'] = $row['id']; 
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        /* General Styling */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #141e30, #243b55);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        /* Login Box */
        form {
            background-color: #1e293b;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 350px;
        }

        h2 {
            color: #ff4747;
            margin-bottom: 20px;
            font-size: 28px;
        }

        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            outline: none;
            background-color: #2d3748;
            color: #fff;
            font-size: 16px;
            transition: 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            background-color: #3b4252;
            box-shadow: 0 0 5px #ff4747;
        }

        button {
            padding: 12px 25px;
            background-color: #ff4747;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            width: 90%;
        }

        button:hover {
            background-color: #e63939;
        }

        p {
            margin-top: 15px;
            font-size: 14px;
        }

        a {
            color: #ff4747;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Error Message Styling */
        .error {
            color: #ff4747;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <form method="post">
        <h2>User Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
        <p>No account? <a href="register.php">Register here</a></p>
    </form>
</body>
</html>