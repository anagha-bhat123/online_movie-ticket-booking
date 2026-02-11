<?php
include '../includes/db.php';  // Adjust path if needed

// Check for movie ID from either now_showing or coming_soon
if (isset($_GET['movie_id'])) {
    $id = intval($_GET['movie_id']);
    $sql = "SELECT * FROM movies WHERE id = $id";
} elseif (isset($_GET['coming_id'])) {
    $id = intval($_GET['coming_id']);
    $sql = "SELECT * FROM coming_soon WHERE id = $id";
} else {
    echo "No movie specified.";
    exit;
}

$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    echo "Movie not found.";
    exit;
}

$movie = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($movie['title']); ?> - Movie Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #1f1c2c, #928dab);
            margin: 0;
            padding: 0;
            color: #fff;
        }

        .container {
            max-width: 800px;
            margin: 60px auto;
            padding: 30px;
            background: rgba(0, 0, 0, 0.85);
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 25px;
            color: #f1c40f;
        }

        img {
            display: block;
            margin: 0 auto 30px auto;
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }

        .details {
            line-height: 1.7;
        }

        .details p {
            font-size: 1.1em;
            margin-bottom: 15px;
            border-left: 4px solid #f1c40f;
            padding-left: 10px;
        }

        .details strong {
            color: #f39c12;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin: 40px auto 10px;
            background: #f1c40f;
            color: #000;
            font-weight: bold;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(241, 196, 15, 0.4);
            transition: all 0.3s ease;
            width: fit-content;
        }

        .back-btn:hover {
            background: #d4ac0d;
            color: #fff;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 2em;
            }

            .details p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
    <img src="../images/<?php echo htmlspecialchars($movie['poster']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">

    <div class="details">
        <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
        <p><strong>Language:</strong> <?php echo htmlspecialchars($movie['language']); ?></p>
        <p><strong>Description:</strong><br> <?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
        <?php if (!empty($movie['release_date'])): ?>
            <p><strong>Release Date:</strong> <?php echo date('F j, Y', strtotime($movie['release_date'])); ?></p>
        <?php endif; ?>
    </div>

    <a href="index.php" class="back-btn">Back to Home</a>
        </div>
</body>
</html>
