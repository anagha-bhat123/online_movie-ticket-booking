<?php
session_start();
include('../includes/db.php');

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $query = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();

            if ($password === $admin['password']) {
                session_regenerate_id(true);
                $_SESSION['admin'] = $username;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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

        input[type="text"],
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

        input[type="text"]:focus,
        input[type="password"]:focus {
            background-color: #3b4252;
            box-shadow: 0 0 5px #ff4747;
        }

        button[type="submit"] {
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
        }

        button[type="submit"]:hover {
            background-color: #e63939;
        }

        p {
            margin-top: 15px;
            font-size: 14px;
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
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>