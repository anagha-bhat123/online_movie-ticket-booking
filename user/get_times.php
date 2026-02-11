<?php
include('../includes/db.php');

if (isset($_GET['movie_id']) && isset($_GET['date'])) {
    $movie_id = $_GET['movie_id'];
    $date = $_GET['date'];

    $times_res = $conn->query("SELECT DISTINCT show_time FROM `shows` WHERE movie_id = $movie_id AND show_date = '$date' ORDER BY show_time");

    $times = [];
    while ($row = $times_res->fetch_assoc()) {
        $times[] = $row['show_time'];
    }

    echo json_encode($times);
}
?>
