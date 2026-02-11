
<!-- filepath: c:\xampp\htdocs\online_movie\user\contact.php -->
<?php
session_start();
$isLoggedIn = isset($_SESSION['user']); // Check if the user is logged in
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        /* Header Styling */
        .header {
            background-color: black;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #ff9800; /* Orange logo */
        }

        .main-nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        .main-nav ul li {
            display: inline;
        }

        .main-nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .main-nav ul li a:hover {
            color: #ff6600; /* Orange hover effect */
        }

        /* Contact Content Styling */
        .contact-content {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .contact-content h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
            font-size: 32px;
        }

        .contact-content p {
            font-size: 18px;
            line-height: 1.8;
            color: #555;
            text-align: center;
        }

        .contact-content ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
            text-align: center;
        }

        .contact-content ul li {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }

        /* Feedback Form Styling */
        .feedback-form {
            margin-top: 30px;
        }

        .feedback-form h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px;
        }

        .feedback-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }

        .feedback-form input,
        .feedback-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            font-size: 16px;
        }

        .feedback-form button {
            background-color: #2c3e50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .feedback-form button:hover {
            background-color: #1a2b40;
        }

        /* Footer Styling */
        .footer {
            text-align: center;
            padding: 15px;
            background-color:black;
            color: white;
            margin-top: 30px;
        }
    </style>
    <script>
        // JavaScript to handle login popup
        function checkLogin(event) {
            const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
            if (!isLoggedIn) {
                event.preventDefault(); // Prevent form submission or interaction
                alert("Please log in to provide feedback.");
                window.location.href = "login.php"; // Redirect to login page
            }
        }
    </script>
</head>
<body>
<header class="header">
    <div class="header-container">
        <div class="logo">Get My Ticket!!</div>
        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (!$isLoggedIn): ?>
                    <li><a href="login.php">Sign In</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<section class="contact-content">
    <h1>Contact Us</h1>
    <p>If you have any questions or need assistance, feel free to reach out to us:</p>
    <ul>
        <li>Email: getmyticket@gmail.com</li>
        <li>Phone: 948114579</li>
    </ul>

    <div class="feedback-form">
        <h2>Give Us Your Feedback</h2>
        <form action="submit_feedback.php" method="POST" onsubmit="checkLogin(event)">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required onclick="checkLogin(event)">

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required onclick="checkLogin(event)">

            <label for="message">Your Feedback:</label>
            <textarea id="message" name="message" rows="5" required onclick="checkLogin(event)"></textarea>

            <button type="submit">Submit Feedback</button>
        </form>
    </div>
</section>

<footer class="footer">
    <p>&copy; 2025 Get My Ticket!! All rights reserved.</p>
</footer>
</body>
</html>