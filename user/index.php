<?php


session_start();
include('../includes/db.php');
$coming_soon = [];

$sql = "SELECT * FROM coming_soon ORDER BY release_date ASC";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $coming_soon[] = $row;
    }
}
$user_details = [];
$bookings = [];

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_details = $stmt->get_result()->fetch_assoc();

    $stmt = $conn->prepare("SELECT movie_name, theater_name, show_date, show_time, seat_number, food_items, total_amount, status FROM bookings WHERE user_id = ? ORDER BY show_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}
}
if (isset($stmt)) {
    $stmt->close();
}

// Get all movies (latest first)
$movies = $conn->query("SELECT * FROM movies ORDER BY release_date DESC");

// For trending badge (top 2 most recent)
$trending = [];
$trendRes = $conn->query("SELECT id FROM movies ORDER BY release_date DESC LIMIT 2");
while ($row = $trendRes->fetch_assoc()) $trending[] = $row['id'];

// Separate movies into now showing and coming soon
$now_showing = [];
$coming_soon = [];
$today = date('Y-m-d');
$all_movies = [];
if ($movies->num_rows > 0) {
    foreach ($movies as $row) {
        $all_movies[] = $row;
        if ($row['release_date'] <= $today) {
            $now_showing[] = $row;
        } else {
            $coming_soon[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Movie Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        html, body { height: 100%; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(120deg, #181818 0%, #232323 100%);
            animation: bgmove 10s infinite alternate;
            display: flex;
            flex-direction: column;
        }
        .filter-bar {
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: center;
}
.filter-bar label {
    margin-right: 5px;
}
.filter-bar select {
    padding: 5px;
    font-size: 14px;
}

        @keyframes bgmove {
            0% {background-position: 0 0;}
            100% {background-position: 100% 100%;}
        }
        .header {
            background: #000;
            padding: 20px 0 10px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        }
        .header-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: orange;
            letter-spacing: 2px;
            text-shadow: 1px 2px 8px #000a;
        }
        .main-nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 30px;
        }
        .main-nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.3s;
        }
        .main-nav ul li a:hover {
            color: orange;
        }
        .welcome-msg {
            text-align: center;
            margin: 10px 0 0 0;
            font-size: 18px;
            color: #ff9800;
        }
        /* Hero Section Styles */
        .hero-section {
            background:rgb(248, 247, 244);
            color:black;
            padding: 60px 20px 50px 20px;
            text-align: center;
            box-shadow: 0 4px 24px #0003;
            animation: fadeInHero 1.2s;
        }
        @keyframes fadeInHero {
            from { opacity: 0; transform: translateY(-40px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .hero-content h1 {
            font-size: 2.8rem;
            margin-bottom: 18px;
            letter-spacing: 2px;
            font-weight: bold;
        }
        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 28px;
            color:rgb(42, 41, 37);
        }
        .hero-btn {
            background: #232323;
            color: #ff9800;
            border: none;
            border-radius: 30px;
            padding: 14px 36px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 8px #0005;
            transition: background 0.2s, color 0.2s;
        }
        .hero-btn:hover {
            background: #fff;
            color: #ff5722;
        }
        .search-bar {
            text-align: center;
            margin: 30px 0 0 0;
        }
        .search-bar input {
            padding: 8px 16px;
            border-radius: 6px;
            border: 1px solid #bbb;
            font-size: 16px;
            width: 260px;
            max-width: 90vw;
        }
        .genre-filter {
            text-align:center;
            margin: 18px 0 0 0;
        }
        .genre-filter label {
            color: orange;
            font-weight: bold;
        }
        .genre-filter select {
            padding:6px 12px;
            border-radius:6px;
        }
        .movie-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 24px;
            padding: 40px 20px 20px 20px;
            flex: 1 0 auto;
        }
        .movie {
            background-color: #232323;
            padding: 15px;
            width: 220px;
            border-radius: 14px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s, opacity 0.7s;
            box-shadow: 0 4px 10px rgba(124, 119, 119, 0.4);
            position: relative;
            overflow: hidden;
            opacity: 0;
            animation: fadeIn 1s forwards;
        }
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        .movie img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-radius: 10px;
        }
        .movie:hover {
            transform: scale(1.07) translateY(-7px);
            box-shadow: 0 12px 32px rgba(255, 140, 0, 0.25);
            z-index: 2;
        }
        .movie h3 {
            margin-top: 10px;
            font-size: 18px;
        }
        .movie .genre {
            color: orange;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .movie .trending-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(90deg, #ff9800 60%, #ff5722 100%);
            color: #fff;
            font-size: 13px;
            padding: 3px 10px;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 2px 8px #0005;
            letter-spacing: 1px;
        }
        .movie .new-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #00c853;
            color: #fff;
            font-size: 13px;
            padding: 3px 10px;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 2px 8px #0005;
            letter-spacing: 1px;
        }
        .movie .coming-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ff9800;
            color: #fff;
            font-size: 13px;
            padding: 3px 10px;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 2px 8px #0005;
            letter-spacing: 1px;
        }
        .movie .rating-badge {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: #222;
            color: #ffd600;
            font-size: 13px;
            padding: 3px 10px;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 2px 8px #0005;
        }
        .movie .top-badge {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffd600;
            color: #232323;
            font-size: 13px;
            padding: 3px 10px;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 2px 8px #0005;
            letter-spacing: 1px;
        }
        .details-btn, .book-btn {
            display: inline-block;
            margin: 8px 4px 0 4px;
            padding: 8px 14px;
            background: #fff;
            color: #ff9800;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s, color 0.2s;
        }
        .details-btn:hover, .book-btn:hover {
            background: #ff8800;
            color: #fff;
        }
        .footer {
            background-color: #000;
            color: #bbbbbb;
            padding: 15px 0;
            text-align: center;
            margin-top: auto;
        }
        .dashboard-icon {
            cursor: pointer;
            font-size: 24px;
            position: absolute;
            top: 20px;
            right: 20px;
            color: #fff;
        }
        .user-dropdown {
            position: absolute;
            top: 70px;
            right: 30px;
            background-color: #fff;
            color: #000;
            border-radius: 10px;
            padding: 15px;
            width: 280px;
            display: none;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            z-index: 1000;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s, transform 0.3s;
        }
        .user-dropdown.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        .user-dropdown a {
            display: block;
            margin-top: 8px;
            text-decoration: none;
            color: #e50914;
            font-weight: bold;
        }
        .booking-entry {
            margin-top: 10px;
            font-size: 14px;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }
        .now-showing h2, .coming-soon h2 {
            text-align: center;
            margin: 40px 0 20px;
            font-size: 28px;
            color: orange;
        }
        .no-movies {
            text-align: center;
            margin: 40px 0;
            color: #fff;
            font-size: 1.2rem;
        }
        .no-movies img {
            width: 120px;
            opacity: 0.7;
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        #backToTop {
            display:none;
            position:fixed;
            bottom:30px;
            right:30px;
            padding:12px 18px;
            font-size:18px;
            background:orange;
            color:#fff;
            border:none;
            border-radius:50%;
            box-shadow:0 2px 8px #0007;
            cursor:pointer;
            z-index:999;
        }
        .floating-book-btn {
            display: none;
            position: fixed;
            bottom: 90px;
            right: 30px;
            background: linear-gradient(90deg, #ff9800 60%, #ff5722 100%);
            color: #fff;
            border: none;
            border-radius: 30px;
            padding: 16px 36px;
            font-size: 1.1rem;
            font-weight: bold;
            box-shadow: 0 2px 8px #0007;
            cursor: pointer;
            z-index: 999;
            transition: background 0.2s, color 0.2s;
        }
        .floating-book-btn:hover {
            background: #fff;
            color: #ff5722;
        }
        @media (max-width: 900px) {
            .movie-list {
                gap: 10px;
            }
            .movie {
                width: 45vw;
                min-width: 160px;
                max-width: 220px;
            }
        }
        @media (max-width: 600px) {
            .movie-list {
                flex-direction: column;
                align-items: center;
            }
            .movie {
                width: 90vw;
                min-width: 120px;
                max-width: 320px;
            }
            .floating-book-btn {
                right: 10px;
                left: 10px;
                width: auto;
                padding: 14px 0;
                font-size: 1rem;
            }
        }
        .typewriter {
    display: inline-block;
    overflow: hidden;
    border-right: .15em solid orange;
    white-space: nowrap;
    letter-spacing: .1em;
    animation: typing 3s steps(30, end), blink-caret .75s step-end infinite;
    width: 29ch; /* Matches character count */
}

@keyframes typing {
    from { width: 0; }
    to { width: 29ch; }
}
/* Coming Soon Section */
.coming-soon {
    background: #1c1c1c;
    padding: 40px 20px;
    color: #fff;
}

.coming-soon h2 {
    font-size: 2rem;
    text-align: center;
    margin-bottom: 30px;
    color: #ffa500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.movie-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.movie {
    position: relative;
    background: #2b2b2b;
    border-radius: 12px;
    overflow: hidden;
    width: 200px; /* increased width */
    transition: transform 0.2s ease-in-out;
    text-align: center;
    padding-bottom: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
}

.movie:hover {
    transform: scale(1.05);
}

.movie img {
    width: 100%;
    height: 240px;
    object-fit: cover;
    border-bottom: 2px solid #444;
}

.movie h3 {
    margin: 10px 5px 5px;
    font-size: 1rem;
    color: #ffffff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.movie .genre {
    font-size: 0.85rem;
    color: #aaaaaa;
    margin-bottom: 8px;
}

/* Details Button - White with red hover */
.details-btn {
    display: inline-block;
    font-size: 0.75rem;
    background: #fff;
    color: #2b2b2b;
    padding: 6px 12px;
    border-radius: 15px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s, color 0.3s;
}

.details-btn:hover {
    background: #ff4b2b;
    color: #fff;
}

/* Badges */
.coming-badge,
.top-badge,
.rating-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background-color: #ff0066;
    color: #fff;
    padding: 3px 7px;
    font-size: 0.65rem;
    font-weight: bold;
    border-radius: 3px;
    text-transform: uppercase;
    z-index: 1;
}

.top-badge {
    background-color: #ffa500;
    left: auto;
    right: 8px;
}

.rating-badge {
    top: auto;
    bottom: 8px;
    right: 8px;
    background-color: #28a745;
}

/* No movies */
.no-movies {
    text-align: center;
    color: #ccc;
    margin-top: 30px;
    font-size: 1rem;
}

.no-movies img {
    width: 80px;
    margin-bottom: 10px;
    opacity: 0.7;
}
.genre-filter, .language-filter {
    margin: 20px 0;
    font-size: 16px;
}

select {
    padding: 6px 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ccc;
}
.language-filter-container {
        margin-bottom: 10px;
        position: relative;
    }

    .filter-toggle {
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .language-filter-container {
        margin: 20px 0;
    }

    .language-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .language-button {
        padding: 8px 14px;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        border-radius: 6px;
        cursor: pointer;
    }

    .language-button.active {
        background-color: rgb(245, 180, 15);
        color: white;
        border-color:rgb(245, 180, 15);
    }
    .filter-section {
        margin: 20px;
        text-align: center;
    }

    .filter-toggle {
        padding: 10px 18px;
        margin: 5px;
        font-size: 16px;
        background-color:rgb(245, 180, 15);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    .filter-buttons {
        display: none;
        margin-top: 10px;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }

    .filter-button {
        padding: 8px 14px;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        border-radius: 6px;
        cursor: pointer;
    }

    .filter-button.active {
        background-color: rgb(245, 180, 15);
        color: white;
        border-color: rgb(245, 180, 15);
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
        <?php if (isset($_SESSION['user'])): ?>
            <div class="welcome-msg">Welcome, <?= htmlspecialchars($user_details['name']) ?>!</div>
        <?php endif; ?>
    </div>
    <?php if (isset($_SESSION['user'])): ?>
        <div class="dashboard-icon" onclick="toggleDashboard()">☰</div>
        <div class="user-dropdown" id="userDropdown">
            <p><strong><?= htmlspecialchars($user_details['name']) ?></strong></p>
            <p><?= htmlspecialchars($user_details['email']) ?></p>
            
            <a href="cancel_history.php"> Cancelled Bookings</a>

            <a href="logout.php">Logout</a>
            <hr>
            <h2>Your Bookings</h2>

<?php if (count($bookings) > 0): ?>
    <?php foreach ($bookings as $b): ?>
        <div class="booking-card" style="border: 1px solid #ccc; padding: 12px; margin-bottom: 15px; border-radius: 6px; background: #f9f9f9;">
            <p><strong>Movie:</strong> <?= htmlspecialchars($b['movie_name']) ?></p>
            <p><strong>Theater:</strong> <?= htmlspecialchars($b['theater_name']) ?></p>
            <p><strong>Date & Time:</strong> <?= htmlspecialchars($b['show_date']) ?> @ <?= htmlspecialchars($b['show_time']) ?></p>
            <p><strong>Seats:</strong> <?= htmlspecialchars($b['seat_number']) ?></p>
            <p><strong>Food:</strong> <?= htmlspecialchars($b['food_items']) ?: 'None' ?></p>
            <p><strong>Total Paid:</strong> ₹ <?= number_format($b['total_amount'], 2) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($b['status']) ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>You have no bookings yet.</p>
<?php endif; ?>


        </div>
    <?php endif; ?>
</header>

<!-- Hero Section -->
<!-- HTML -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="typewriter">Welcome to Get My Ticket!!</h1>
        <p class="fade-in-text">Book your favorite movies instantly. Discover what's now showing and what's coming soon!</p>
        <button class="hero-btn" onclick="document.getElementById('nowShowingList').scrollIntoView({behavior:'smooth'});">
            🎬 View Now Showing
        </button>
    </div>
</section>


<!-- Search Bar 
<div class="search-bar">
    <input type="text" id="movieSearch" placeholder="Search movies by title...">
</div>-->

<!-- Genre Filter -->
<!-- Filter Section -->
<div class="filter-section">
    <!-- Toggle Buttons -->
    <button class="filter-toggle" onclick="toggleFilter('language')">Filter by Language</button>
    <button class="filter-toggle" onclick="toggleFilter('genre')">Filter by Genre</button>

    <!-- Language Filter Buttons -->
    <div id="languageFilterButtons" class="filter-buttons">
        <button class="filter-button active" onclick="filterMovies('all', 'language')">All</button>
        <?php
        $all_languages = [];
        foreach ($now_showing as $row) {
            $lang = strtolower(trim($row['language']));
            if (!in_array($lang, $all_languages)) $all_languages[] = $lang;
        }
        foreach ($coming_soon as $row) {
            $lang = strtolower(trim($row['language']));
            if (!in_array($lang, $all_languages)) $all_languages[] = $lang;
        }
        foreach ($all_languages as $lang) {
            echo '<button class="filter-button" onclick="filterMovies(\'' . $lang . '\', \'language\')">' . ucfirst(htmlspecialchars($lang)) . '</button>';
        }
        ?>
    </div>

    <!-- Genre Filter Buttons -->
    <div id="genreFilterButtons" class="filter-buttons">
        <button class="filter-button active" onclick="filterMovies('all', 'genre')">All</button>
        <?php
        $all_genres = [];
        foreach ($now_showing as $row) {
            $genre = strtolower(trim($row['genre']));
            if (!in_array($genre, $all_genres)) $all_genres[] = $genre;
        }
        foreach ($coming_soon as $row) {
            $genre = strtolower(trim($row['genre']));
            if (!in_array($genre, $all_genres)) $all_genres[] = $genre;
        }
        foreach ($all_genres as $genre) {
            echo '<button class="filter-button" onclick="filterMovies(\'' . $genre . '\', \'genre\')">' . ucfirst(htmlspecialchars($genre)) . '</button>';
        }
        ?>
    </div>
</div>


<section class="now-showing">
    <h2>Now Showing</h2>
    <div class="movie-list" id="nowShowingList">
    <?php
    if (count($now_showing) > 0) {
        foreach ($now_showing as $row) {
            $is_new = (strtotime($row['release_date']) >= strtotime('-30 days'));
            $is_top = (!empty($row['rating']) && floatval($row['rating']) >= 8);

            echo '<div class="movie" 
                    data-genre="' . htmlspecialchars(strtolower($row['genre'])) . '" 
                    data-language="' . htmlspecialchars(strtolower($row['language'])) . '" 
                    data-title="' . htmlspecialchars(strtolower($row['title'])) . '">';

            if (in_array($row['id'], $trending)) {
                echo '<div class="trending-badge">Trending</div>';
            }
            if ($is_new) {
                echo '<div class="new-badge">New</div>';
            }
            if ($is_top) {
                echo '<div class="top-badge">Top Rated</div>';
            } elseif (!empty($row['rating'])) {
                echo '<div class="rating-badge">★ ' . htmlspecialchars($row['rating']) . '</div>';
            }

            echo '<img src="../images/' . htmlspecialchars($row['poster']) . '" alt="' . htmlspecialchars($row['title']) . '">';
            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
            echo '<div class="genre">' . htmlspecialchars($row['genre']) . '</div>';
            echo '<div class="language">' . htmlspecialchars($row['language']) . '</div>';
            echo '<a class="book-btn" href="movie_details.php?movie_id=' . $row['id'] . '">Details</a>';
            echo '<a class="book-btn" href="book_ticket.php?movie_id=' . $row['id'] . '">Book Now</a>';
            echo '</div>';
        }
    } else {
        echo '<div class="no-movies">';
        echo '<img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Movies">';
        echo '<div>No movies available at the moment.</div>';
        echo '</div>';
    }
    ?>
    </div>
</section>

<section class="coming-soon">
    <h2>Coming Soon</h2>
    <div class="movie-list" id="comingSoonList">
    <?php
    $coming_soon = [];
    $result = mysqli_query($conn, "SELECT * FROM coming_soon ORDER BY release_date ASC");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $coming_soon[] = $row;
        }
    }

    if (count($coming_soon) > 0) {
        foreach ($coming_soon as $row) {
            $is_top = (!empty($row['rating']) && floatval($row['rating']) >= 8);

            echo '<div class="movie" 
                    data-genre="' . htmlspecialchars(strtolower($row['genre'])) . '" 
                    data-language="' . htmlspecialchars(strtolower($row['language'])) . '" 
                    data-title="' . htmlspecialchars(strtolower($row['title'])) . '">';

            echo '<div class="coming-badge">Coming Soon</div>';

            if ($is_top) {
                echo '<div class="top-badge">Top Rated</div>';
            } elseif (!empty($row['rating'])) {
                echo '<div class="rating-badge">★ ' . htmlspecialchars($row['rating']) . '</div>';
            }

            echo '<img src="../images/' . htmlspecialchars($row['poster']) . '" alt="' . htmlspecialchars($row['title']) . '">';
            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
            echo '<div class="genre">' . htmlspecialchars($row['genre']) . '</div>';
            echo '<div class="language">' . htmlspecialchars($row['language']) . '</div>';
            echo '<a class="book-btn" href="movie_detail.php?coming_id=' . $row['id'] . '">Details</a>';
            echo '</div>';
        }
    } else {
        echo '<div class="no-movies">';
        echo '<img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Movies">';
        echo '<div>No upcoming movies.</div>';
        echo '</div>';
    }
    ?>
    </div>
</section>





<footer class="footer">
    <p>&copy; 2025 Get My Ticket!!. All rights reserved.</p>
</footer>

<button id="backToTop" title="Back to Top">↑</button>
<button class="floating-book-btn" id="floatingBookBtn" onclick="document.getElementById('nowShowingList').scrollIntoView({behavior:'smooth'});">
    🎟️ Book Now
</button>
<script>
function toggleDashboard() {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
    } else {
        dropdown.classList.add('show');
    }
}
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userDropdown');
    const icon = document.querySelector('.dashboard-icon');
    if (dropdown && !dropdown.contains(event.target) && icon && !icon.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

// Genre filter
document.getElementById('genreFilter').addEventListener('change', function() {
    let genre = this.value;
    document.querySelectorAll('.movie-list .movie').forEach(function(card) {
        let genres = card.getAttribute('data-genre').split(',');
        let show = (genre === 'all' || genres.map(g=>g.trim()).includes(genre));
        card.style.display = show ? '' : 'none';
    });
});

// Movie search filter
document.getElementById('movieSearch').addEventListener('input', function() {
    let search = this.value.trim().toLowerCase();
    document.querySelectorAll('.movie-list .movie').forEach(function(card) {
        let title = card.getAttribute('data-title');
        card.style.display = (title.includes(search)) ? '' : 'none';
    });
});

// Back to top button
const backToTop = document.getElementById('backToTop');
window.addEventListener('scroll', function() {
    backToTop.style.display = window.scrollY > 200 ? 'block' : 'none';
    document.getElementById('floatingBookBtn').style.display = window.scrollY > 350 ? 'block' : 'none';
});
backToTop.onclick = () => window.scrollTo({top:0,behavior:'smooth'});
</script>
<script>
document.getElementById('genreFilter').addEventListener('change', filterMovies);
document.getElementById('languageFilter').addEventListener('change', filterMovies);

function filterMovies() {
    const selectedGenre = document.getElementById('genreFilter').value.toLowerCase();
    const selectedLang = document.getElementById('languageFilter').value.toLowerCase();
    
    const movies = document.querySelectorAll('.movie-card'); // assuming .movie-card class on each movie

    movies.forEach(movie => {
        const movieGenres = movie.getAttribute('data-genre').toLowerCase();
        const movieLangs = movie.getAttribute('data-language').toLowerCase();
        
        const showByGenre = (selectedGenre === 'all' || movieGenres.includes(selectedGenre));
        const showByLang = (selectedLang === 'all' || movieLangs.includes(selectedLang));

        movie.style.display = (showByGenre && showByLang) ? 'block' : 'none';
    });
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const genreFilter = document.getElementById('genreFilter');
    const languageFilter = document.getElementById('languageFilter');
    const allMovies = document.querySelectorAll('.movie');

    function filterMovies() {
        const selectedGenre = genreFilter.value;
        const selectedLanguage = languageFilter.value;

        allMovies.forEach(movie => {
            const genre = movie.getAttribute('data-genre').toLowerCase();
            const language = movie.getAttribute('data-language').toLowerCase();

            const matchesGenre = (selectedGenre === 'all' || genre.includes(selectedGenre));
            const matchesLanguage = (selectedLanguage === 'all' || language.includes(selectedLanguage));

            if (matchesGenre && matchesLanguage) {
                movie.style.display = 'block';
            } else {
                movie.style.display = 'none';
            }
        });
    }

    genreFilter.addEventListener('change', filterMovies);
    languageFilter.addEventListener('change', filterMovies);
});
</script>
<script>
    function toggleLanguageButtons() {
        const box = document.getElementById('languageButtons');
        box.style.display = box.style.display === 'flex' ? 'none' : 'flex';
    }

    function filterByLanguage(language) {
        const buttons = document.querySelectorAll('.language-button');
        buttons.forEach(btn => btn.classList.remove('active'));

        const activeBtn = Array.from(buttons).find(btn => btn.textContent.toLowerCase() === language);
        if (activeBtn) activeBtn.classList.add('active');

        const allMovies = document.querySelectorAll('.movie-card'); // Change this based on your movie container class
        allMovies.forEach(card => {
            const lang = card.getAttribute('data-language')?.toLowerCase();
            if (language === 'all' || lang === language) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

<script>
    function toggleFilter(type) {
        // Hide all filter panels
        document.getElementById('languageFilterButtons').style.display = 'none';
        document.getElementById('genreFilterButtons').style.display = 'none';

        // Show the selected one
        if (type === 'language') {
            document.getElementById('languageFilterButtons').style.display = 'flex';
        } else if (type === 'genre') {
            document.getElementById('genreFilterButtons').style.display = 'flex';
        }

        // Reset active states
        document.querySelectorAll('.filter-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll(`#${type}FilterButtons .filter-button:first-child`).forEach(btn => btn.classList.add('active'));

        // Reset movie visibility
        document.querySelectorAll('.movie').forEach(card => card.style.display = 'inline-block');
    }

    function filterMovies(value, type) {
        // Update active button
        const btnGroup = type === 'language' ? '#languageFilterButtons' : '#genreFilterButtons';
        document.querySelectorAll(`${btnGroup} .filter-button`).forEach(btn => {
            btn.classList.remove('active');
            if (btn.textContent.toLowerCase() === value) btn.classList.add('active');
        });

        // Filter movies
        document.querySelectorAll('.movie').forEach(movie => {
            const attr = movie.getAttribute(`data-${type}`);
            const matches = value === 'all' || attr === value;
            movie.style.display = matches ? 'inline-block' : 'none';
        });
    }
</script>


</body>
</html>