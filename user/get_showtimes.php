<?php
include('../includes/db.php');

if (isset($_GET['movie_id']) && isset($_GET['date'])) {
    $movie_id = $_GET['movie_id'];
    $date = $_GET['date'];

    // Query showtimes for the selected movie and date
    $times_res = $conn->query("SELECT DISTINCT show_time FROM `shows` WHERE movie_id = $movie_id AND show_date = '$date' ORDER BY show_time");

    if ($times_res->num_rows > 0) {
        while ($time = $times_res->fetch_assoc()) {
            echo "<option value='{$time['show_time']}'>{$time['show_time']}</option>";
        }
    } else {
        echo "<option value=''>No showtimes available</option>";
    }
}
?>
