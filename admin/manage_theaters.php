<?php
session_start();
include('../includes/db.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $conn->query("INSERT INTO theaters (name, location) VALUES ('$name', '$location')");
    header("Location: manage_theaters.php");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM theaters WHERE id=$id");
    header("Location: manage_theaters.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Theaters</title>
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

        form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
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
<h2>Manage Theaters</h2>
<form method="post">
    <input type="text" name="name" placeholder="Theater Name" required><br>
    <input type="text" name="location" placeholder="Location" required><br>
    <button type="submit" name="add">Add Theater</button>
</form>

<hr>

<h3>Theater List</h3>
<table border="1">
    <tr><th>Name</th><th>Location</th><th>Action</th></tr>
    <?php
    $result = $conn->query("SELECT * FROM theaters");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['location']}</td>
            <td><a href='?delete={$row['id']}' onclick='return confirm(\"Delete?\")'>Delete</a></td>
        </tr>";
    }
    ?>
</table>

<p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>