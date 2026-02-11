<?php
session_start();
include('../includes/db.php');

// Check if movie_id is passed
if (isset($_GET['movie_id'])) {
    $movie_id = $_GET['movie_id'];

    // Fetch movie details
    $movie_res = $conn->query("SELECT * FROM movies WHERE id = $movie_id");
    $movie = $movie_res->fetch_assoc();
} else {
    // Redirect if no movie_id is passed
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($movie['title']); ?> - Movie Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212;
            color: #f0f0f0;
        }

        a {
            text-decoration: none;
        }

        /* Header */
        .header {
            background-color: #1a1a1a;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
        }

        .logo {
            color: #ff9800;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .main-nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 10px;
            gap: 20px;
        }

        .main-nav ul li a {
            color: white;
            font-size: 16px;
            padding: 10px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .main-nav ul li a:hover {
            background-color: #ff9800;
            color: #121212;
        }

        .sign-in-btn {
            background-color: #e53935;
            padding: 10px 20px;
            border-radius: 6px;
        }

        .sign-in-btn:hover {
            background-color: #f44336;
        }

        /* Movie Section */
        .movie-details-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
            padding: 50px 20px;
            max-width: 1100px;
            margin: auto;
        }

        .movie-details-img img {
            width: 320px;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(255, 152, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .movie-details-img img:hover {
            transform: scale(1.05);
        }

        .movie-details-info {
            max-width: 600px;
        }

        .movie-details-info h1 {
            font-size: 36px;
            color: #ff9800;
            margin-bottom: 20px;
        }

        .movie-details-info p {
            font-size: 18px;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .movie-details-info strong {
            color: #ffd54f;
        }

        .book-ticket-button {
            display: inline-block;
            background-color: #e53935;
            color: #fff;
            font-size: 18px;
            padding: 12px 28px;
            border-radius: 8px;
            margin-top: 30px;
            transition: all 0.3s ease;
        }

        .book-ticket-button:hover {
            background-color: #f44336;
            transform: scale(1.05);
        }

        /* Footer */
        .footer {
            background-color: #1a1a1a;
            color: #ccc;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .movie-details-container {
                flex-direction: column;
                align-items: center;
            }

            .movie-details-img img {
                width: 90%;
            }

            .movie-details-info h1 {
                font-size: 28px;
                text-align: center;
            }

            .movie-details-info p {
                text-align: center;
            }

            .book-ticket-button {
                display: block;
                margin: 20px auto 0;
            }

            .main-nav ul {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="logo">Get My Ticket</div>
    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (!isset($_SESSION['user'])): ?>
                <li><a href="login.php" class="sign-in-btn">Sign In</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<section class="movie-details">
    <div class="movie-details-container">
        <div class="movie-details-img">
            <img src="../images/<?php echo htmlspecialchars($movie['poster']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
        </div>
        <div class="movie-details-info">
            <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
            <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>

            <?php if (isset($_SESSION['user'])): ?>
                <a href="book_ticket.php?movie_id=<?php echo $movie['id']; ?>" class="book-ticket-button">Book Ticket</a>
            <?php else: ?>
                <a href="#" class="book-ticket-button" onclick="alert('Please login to book tickets.'); return false;">Book Ticket</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<footer class="footer">
    <p>&copy; 2025 Get My Ticket. All rights reserved.</p>
</footer>

</body>
</html>
