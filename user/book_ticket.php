<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['movie_id'])) {
    header("Location: index.php");
    exit();
}

$movie_id = intval($_GET['movie_id']);

// Handle AJAX requests for dates and times
if (isset($_GET['action'])) {
    header('Content-Type: application/json');

    if ($_GET['action'] === 'get_dates' && isset($_GET['theater_id'])) {
        $theater_id = intval($_GET['theater_id']);
        $today = date('Y-m-d');

        $res = $conn->query("SELECT DISTINCT show_date FROM shows WHERE movie_id = $movie_id AND theater_id = $theater_id AND show_date > '$today' ORDER BY show_date");
        $dates = [];
        while ($row = $res->fetch_assoc()) {
            $dates[] = $row['show_date'];
        }
        echo json_encode($dates);
        exit;
    }

    if ($_GET['action'] === 'get_times' && isset($_GET['theater_id']) && isset($_GET['date'])) {
        $theater_id = intval($_GET['theater_id']);
        $date = $conn->real_escape_string($_GET['date']);

        $res = $conn->query("SELECT DISTINCT show_time FROM shows WHERE movie_id = $movie_id AND theater_id = $theater_id AND show_date = '$date' ORDER BY show_time");
        $times = [];
        while ($row = $res->fetch_assoc()) {
            $times[] = $row['show_time'];
        }
        echo json_encode($times);
        exit;
    }
}

$movie_res = $conn->query("SELECT * FROM movies WHERE id = $movie_id");
if (!$movie_res || $movie_res->num_rows == 0) {
    die("Movie not found.");
}
$movie = $movie_res->fetch_assoc();

$theater_res = $conn->query("SELECT DISTINCT t.id, t.name FROM theaters t INNER JOIN shows s ON t.id = s.theater_id WHERE s.movie_id = $movie_id ORDER BY t.name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ticket - UI Revamp</title>
    <style>
        body {
            background: linear-gradient(to right,rgb(16, 16, 17),rgb(23, 24, 25));
            color: #f2f2f2;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: #121212;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.6);
        }
        h1 {
            color: #f39c12;
            text-align: center;
            margin-bottom: 30px;
        }
        .movie-poster {
            text-align: center;
            margin-bottom: 30px;
        }
        .movie-poster img {
            max-width: 300px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(243, 156, 18, 0.7);
        }
        .options-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }
        .option {
            background: #292929;
            padding: 15px 20px;
            border-radius: 10px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            color: #f2f2f2;
        }
        .option:hover,
        .option.active {
            background: #f39c12;
            color: #000;
            border-color: #fff;
        }
        .section {
            margin-bottom: 30px;
        }
        .hidden {
            display: none;
        }
        button {
            padding: 14px 28px;
            font-size: 1.1rem;
            background: #f39c12;
            color: black;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: block;
            margin: 30px auto 0;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #d68910;
        }
        button:disabled {
            background: #888;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Book Your Ticket</h1>
    <div class="movie-poster">
        <img src="../images/<?php echo htmlspecialchars($movie['poster']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
    </div>
    <form id="bookingForm" action="select_seat.php" method="GET">
        <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
        <input type="hidden" name="theater" id="theaterInput">
        <input type="hidden" name="date" id="dateInput">
        <input type="hidden" name="time" id="timeInput">

        <div class="section">
            <h3>Select Theater:</h3>
            <div class="options-grid" id="theaterOptions">
                <?php while ($theater = $theater_res->fetch_assoc()): ?>
                    <div class="option" data-id="<?php echo $theater['id']; ?>">
                        <?php echo htmlspecialchars($theater['name']); ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="section hidden" id="dateSection">
            <h3>Select Date:</h3>
            <div class="options-grid" id="dateOptions"></div>
        </div>

        <div class="section hidden" id="timeSection">
            <h3>Select Time:</h3>
            <div class="options-grid" id="timeOptions"></div>
        </div>

        <button type="submit" id="bookBtn" disabled>Book Ticket</button>
    </form>
</div>

<script>
const movieId = <?php echo $movie['id']; ?>;
const theaterInput = document.getElementById('theaterInput');
const dateInput = document.getElementById('dateInput');
const timeInput = document.getElementById('timeInput');
const bookBtn = document.getElementById('bookBtn');

let selectedTheater = null;
let selectedDate = null;
let selectedTime = null;

const updateButton = () => {
    if (selectedTheater && selectedDate && selectedTime) {
        bookBtn.disabled = false;
    } else {
        bookBtn.disabled = true;
    }
};

function handleOptionClick(container, value, inputElement, callback) {
    const options = container.querySelectorAll('.option');
    options.forEach(opt => opt.classList.remove('active'));
    const selected = [...options].find(opt => opt.dataset.id === value || opt.textContent === value);
    if (selected) selected.classList.add('active');
    inputElement.value = value;
    callback();
    updateButton();
}

const theaterOptions = document.getElementById('theaterOptions');
theaterOptions.addEventListener('click', e => {
    if (e.target.classList.contains('option')) {
        selectedTheater = e.target.dataset.id;
        handleOptionClick(theaterOptions, selectedTheater, theaterInput, () => {
            document.getElementById('dateSection').classList.remove('hidden');
            fetch(`?action=get_dates&movie_id=${movieId}&theater_id=${selectedTheater}`)
                .then(res => res.json())
                .then(dates => {
                    const dateOptions = document.getElementById('dateOptions');
                    dateOptions.innerHTML = dates.map(date => `<div class="option">${date}</div>`).join('');
                    document.getElementById('timeSection').classList.add('hidden');
                    dateInput.value = '';
                    timeInput.value = '';
                    selectedDate = null;
                    selectedTime = null;
                    updateButton();
                });
        });
    }
});

const dateOptions = document.getElementById('dateOptions');
dateOptions.addEventListener('click', e => {
    if (e.target.classList.contains('option')) {
        selectedDate = e.target.textContent;
        handleOptionClick(dateOptions, selectedDate, dateInput, () => {
            document.getElementById('timeSection').classList.remove('hidden');
            fetch(`?action=get_times&movie_id=${movieId}&theater_id=${selectedTheater}&date=${selectedDate}`)
                .then(res => res.json())
                .then(times => {
                    const timeOptions = document.getElementById('timeOptions');
                    timeOptions.innerHTML = times.map(time => `<div class="option">${time}</div>`).join('');
                    timeInput.value = '';
                    selectedTime = null;
                    updateButton();
                });
        });
    }
});

const timeOptions = document.getElementById('timeOptions');
timeOptions.addEventListener('click', e => {
    if (e.target.classList.contains('option')) {
        selectedTime = e.target.textContent;
        handleOptionClick(timeOptions, selectedTime, timeInput, () => {});
    }
});
</script>
</body>
</html>