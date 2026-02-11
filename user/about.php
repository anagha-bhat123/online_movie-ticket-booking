
<!-- filepath: c:\xampp\htdocs\online_movie\user\about.php -->
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
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
            background-color:black;
            color: white;
            padding: 20px 30px;
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
            color:orange; /* Orange logo */
        }

        .main-nav ul {
            list-style: none;
            display: flex;
            gap: 25px;
            margin: 0;
            padding: 0;
        }

        .main-nav ul li {
            display: inline;
        }

        .main-nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 18px; /* Increased font size */
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .main-nav ul li a:hover {
            color: #ff6600; /* Orange hover effect */
        }

        /* About Content Styling */
        .about-content {
            max-width: 900px;
            margin: 50px auto;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .about-content h1 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 36px; /* Larger heading size */
            font-weight: bold;
        }

        .about-content p {
            font-size: 20px; /* Increased font size */
            line-height: 1.8;
            color: #555;
            margin-bottom: 25px;
        }

        .about-content .highlight {
            color: #ff6600; /* Highlighted text in orange */
            font-weight: bold;
        }

        /* Footer Styling */
        .footer {
            text-align: center;
            padding: 20px;
            background-color:black;
            color: white;
            margin-top: 40px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: center;
            }

            .main-nav ul {
                flex-direction: column;
                gap: 15px;
            }

            .about-content {
                margin: 20px;
                padding: 20px;
            }

            .about-content h1 {
                font-size: 28px;
            }

            .about-content p {
                font-size: 16px;
            }
        }
    </style>
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
                <?php if (!isset($_SESSION['user'])): ?>
                    <li><a href="login.php">Sign In</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<section class="about-content">
    <h1>About Us</h1>
    <p>Welcome to <span class="highlight">Get My Ticket!!</span> We are dedicated to providing a seamless and enjoyable experience for booking movie tickets online.</p>
    <p>Our platform offers a wide range of <span class="highlight">movies</span>, <span class="highlight">theaters</span>, and <span class="highlight">showtimes</span> to choose from, ensuring you never miss your favorite films.</p>
    <p>Our mission is to make movie ticket booking <span class="highlight">convenient</span> and <span class="highlight">hassle-free</span> for everyone. Thank you for choosing us!</p>
</section>

<footer class="footer">
    <p>&copy; 2025 Get My Ticket!! All rights reserved.</p>
</footer>
</body>
</html>