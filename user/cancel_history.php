<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user'];

// Fetch canceled bookings
$sql = "SELECT cb.*, m.title AS movie_title, t.name AS theater_name 
        FROM cancelled_bookings cb
        JOIN movies m ON cb.movie_id = m.id
        JOIN theaters t ON cb.theater_id = t.id
        WHERE cb.user_id = $user_id
        ORDER BY cb.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cancelled Bookings</title>
    <style>
        body {
            font-family: Arial;
            background: #f0f0f0;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: red;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #ffdddd;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>❌ Cancelled Bookings</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Movie</th>
                <th>Theater</th>
                <th>Date</th>
                <th>Time</th>
                <th>Seats</th>
                <th>Food</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['booking_id'] ?></td>
                    <td><?= htmlspecialchars($row['movie_title']) ?></td>
                    <td><?= htmlspecialchars($row['theater_name']) ?></td>
                    <td><?= $row['show_date'] ?></td>
                    <td><?= $row['show_time'] ?></td>
                    <td><?= $row['seats'] ?></td>
                    <td><?= $row['food'] ?: 'None' ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">You have no cancelled bookings.</p>
    <?php endif; ?>
</div>
</body>
</html>
