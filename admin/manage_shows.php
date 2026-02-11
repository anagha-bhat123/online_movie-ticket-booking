<?php
session_start();
include('../includes/db.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add'])) {
    $movie_id = $_POST['movie_id'];
    $theater_id = $_POST['theater_id'];
    $show_date = $_POST['show_date'];
    $hour = $_POST['hour'];
$minute = $_POST['minute'];
$ampm = $_POST['ampm'];
$show_time = "$hour:$minute $ampm"; // Final time string like 07:30 PM


    $conn->query("INSERT INTO shows (movie_id, theater_id, show_date, show_time) 
                  VALUES ('$movie_id', '$theater_id', '$show_date', '$show_time')");
    header("Location: manage_shows.php");
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM shows WHERE show_id=$id");
    header("Location: manage_shows.php");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Shows</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            padding: 20px;
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        /* Headings */
        h2, h3 {
            color: #2c3e50;
            text-align: center;
        }

        /* Form Styling */
        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(44, 62, 80, 0.3);
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #2c3e50;
        }

        form select,
        form input[type="date"],
        form input[type="time"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333;
            font-size: 15px;
        }

        form button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        form button:hover {
            background-color: #1a2b40;
        }

        /* Table Styling */
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            border: 1px solid #ddd;
            color: #333;
            box-shadow: 0 0 10px rgba(44, 62, 80, 0.2);
        }

        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }

        /* Links */
        a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Back to Dashboard */
        p a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #2c3e50;
            color: white;
            border-radius: 5px;
            transition: 0.3s ease;
        }

        p a:hover {
            background-color: #1a2b40;
        }
    </style>
</head>
<body>
<h2>Manage Shows</h2>
<form method="post">
    <label>Movie:</label>
    <select name="movie_id" required>
        <option value="">Select Movie</option>
        <?php
        $movies = $conn->query("SELECT * FROM movies");
        while ($m = $movies->fetch_assoc()) {
            echo "<option value='{$m['id']}'>{$m['title']}</option>";
        }
        ?>
    </select><br>

    <label>Theater:</label>
    <select name="theater_id" required>
        <option value="">Select Theater</option>
        <?php
        $theaters = $conn->query("SELECT * FROM theaters");
        while ($t = $theaters->fetch_assoc()) {
            echo "<option value='{$t['id']}'>{$t['name']}</option>";
        }
        ?>
    </select><br>

    <label>Date:</label>
    <input type="date" name="show_date" required><br>
<label>Time:</label>
<div style="display: flex; gap: 10px;">
    <select name="hour" required>
        <option value="">HH</option>
        <?php
        for ($i = 1; $i <= 12; $i++) {
            $h = str_pad($i, 2, '0', STR_PAD_LEFT);
            echo "<option value='$h'>$h</option>";
        }
        ?>
    </select>

    <select name="minute" required>
        <option value="">MM</option>
        <?php
        for ($i = 0; $i < 60; $i += 5) {
            $m = str_pad($i, 2, '0', STR_PAD_LEFT);
            echo "<option value='$m'>$m</option>";
        }
        ?>
    </select>

    <select name="ampm" required>
        <option value="AM">AM</option>
        <option value="PM">PM</option>
    </select>
</div>
    <button type="submit" name="add">Add Show</button>
</form>

<hr>
<h3>Show List</h3>
<table border="1">
    <tr><th>Movie</th><th>Theater</th><th>Date</th><th>Time</th><th>Action</th></tr>
    <?php
    $shows = $conn->query("
    SELECT shows.show_id, movies.title AS movie, theaters.name AS theater, shows.show_date, shows.show_time
    FROM shows 
    JOIN movies ON shows.movie_id = movies.id 
    JOIN theaters ON shows.theater_id = theaters.id
");

while ($s = $shows->fetch_assoc()) {
    echo "<tr>
        <td>{$s['movie']}</td>
        <td>{$s['theater']}</td>
        <td>{$s['show_date']}</td>
        <td>{$s['show_time']}</td>
        <td><a href='?delete={$s['show_id']}' onclick='return confirm(\"Delete?\")'>Delete</a></td>
    </tr>";
}



    ?>
</table>
<p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>