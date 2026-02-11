<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$movie_id = $_GET['movie_id'] ?? null;
$theater_id = $_GET['theater'] ?? null;
$show_date = $_GET['date'] ?? null;
$show_time = $_GET['time'] ?? null;

if (!$movie_id || !$theater_id || !$show_date || !$show_time) {
    die("Missing parameters.");
}

// Debugging
// var_dump($movie_id, $theater_id, $show_date, $show_time);
// exit();

$stmt = $conn->prepare("SELECT id FROM shows WHERE movie_id = ? AND theater_id = ? AND show_date = ? AND show_time LIKE CONCAT(?, '%')");
$stmt->bind_param("iiss", $movie_id, $theater_id, $show_date, $show_time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Show not found for the selected parameters.");
}

$show = $result->fetch_assoc();
$show_id = $show['id'];

header("Location: select_seat.php?show_id=$show_id");
exit();
