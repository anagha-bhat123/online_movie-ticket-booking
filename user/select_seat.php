<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['movie_id'], $_GET['theater'], $_GET['date'], $_GET['time'])) {
    $movie_id = (int) $_GET['movie_id'];
    $theater_id = (int) $_GET['theater'];
    $show_date = date('Y-m-d', strtotime($_GET['date']));
    $show_time = date('H:i:s', strtotime($_GET['time']));

    $_SESSION['movie_id'] = $movie_id;
    $_SESSION['theater_id'] = $theater_id;
    $_SESSION['show_date'] = $show_date;
    $_SESSION['show_time'] = $show_time;
} else {
    die("Required booking details not provided.");
}

// Fetch theater name for querying bookings
$theater_name = $conn->query("SELECT name FROM theaters WHERE id = $theater_id")->fetch_assoc()['name'] ?? '';
if (!$theater_name) {
    die("Invalid theater selected.");
}

// Fetch already booked seats for this show and theater
$booked_seats = [];
$stmt = $conn->prepare("SELECT seat_number FROM bookings WHERE show_date = ? AND show_time = ? AND theater_name = ? AND status = 'active'");
$stmt->bind_param("sss", $show_date, $show_time, $theater_name);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $seats = explode(',', $row['seat_number']);
    foreach ($seats as $seat) {
        $booked_seats[] = strtoupper(trim($seat)); // Normalize seat string
    }
}
$stmt->close();

// Fetch movie and theater titles for display
$movie_title = $conn->query("SELECT title FROM movies WHERE id = $movie_id")->fetch_assoc()['title'] ?? 'Unknown Movie';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Select Seats - <?= htmlspecialchars($movie_title) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #111;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .screen {
            margin: 20px auto;
            background: #ccc;
            width: 60%;
            height: 30px;
            line-height: 30px;
            border-radius: 5px;
            color: black;
        }
        .seats {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 10px;
            width: 60%;
            margin: 20px auto;
        }
        .seat {
            position: relative;
            padding-top: 100%;
            border-radius: 5px;
        }
        .seat input[type="checkbox"] {
            display: none;
        }
        .seat label {
            position: absolute;
            top: 0; bottom: 0; left: 0; right: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #666;
            border-radius: 5px;
            background: #333;
            color: white;
            cursor: pointer;
            user-select: none;
        }
        .seat input:checked + label {
            background: green;
        }
        .seat.booked label {
            background: red !important;
            border: 2px solid yellow !important;
            pointer-events: none;
            cursor: not-allowed;
        }
        button {
            padding: 10px 25px;
            background: #f44336;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 1rem;
        }
        button:hover {
            background: #d32f2f;
        }
    </style>
</head>
<body>
    <h2>Select Your Seats</h2>
    <p><strong>Movie:</strong> <?= htmlspecialchars($movie_title) ?></p>
    <p><strong>Theater:</strong> <?= htmlspecialchars($theater_name) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($show_date) ?></p>
    <p><strong>Time:</strong> <?= htmlspecialchars($show_time) ?></p>

    <div class="screen">Screen</div>

    <form method="POST" action="select_food.php">
        <input type="hidden" name="movie_id" value="<?= $movie_id ?>">
        <input type="hidden" name="theater_id" value="<?= $theater_id ?>">
        <input type="hidden" name="show_date" value="<?= $show_date ?>">
        <input type="hidden" name="show_time" value="<?= $show_time ?>">

        <div class="seats">
            <?php
            $rows = range('A', 'J');
            for ($r = 0; $r < 10; $r++) {
                for ($c = 1; $c <= 10; $c++) {
                    $seat = $rows[$r] . $c;
                    $isBooked = in_array($seat, $booked_seats);
                    echo '<div class="seat' . ($isBooked ? ' booked' : '') . '">';
                    if ($isBooked) {
                        // Booked seat, label only
                        echo "<label>$seat</label>";
                    } else {
                        // Available seat, checkbox input + label
                        echo "<input type='checkbox' id='seat$seat' name='selected_seats[]' value='$seat'>";
                        echo "<label for='seat$seat'>$seat</label>";
                    }
                    echo '</div>';
                }
            }
            ?>
        </div>

        <button type="submit">Proceed to Food Selection</button>
    </form>
</body>
</html>
