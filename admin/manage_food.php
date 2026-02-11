<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Add food item with stock
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, "../images/$image");

    $conn->query("INSERT INTO food (name, price, image, stock) VALUES ('$name', '$price', '$image', '$stock')");
    header("Location: manage_food.php");
}

// Delete food item
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM food WHERE id=$id");
    header("Location: manage_food.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Food</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        h2, h3 {
            color: #2c3e50;
            text-align: center;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
            max-width: 500px;
            margin: 0 auto 40px auto;
            text-align: center;
        }

        form input[type="text"],
        form input[type="number"],
        form input[type="file"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333;
            font-size: 15px;
        }

        form button {
            padding: 10px 25px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
        }

        form button:hover {
            background-color: #1a252f;
        }

        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #ffffff;
            border: 1px solid #ddd;
            color: #333;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        td img {
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        p a {
            display: inline-block;
            margin-top: 20px;
            color: #fff;
            background-color: #2c3e50;
            padding: 10px 20px;
            border-radius: 5px;
            transition: 0.3s ease;
        }

        p a:hover {
            background-color: #1a252f;
        }
    </style>
</head>
<body>

<h2>Manage Food Items</h2>

<!-- Food Adding Form -->
<form method="post" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Food Name" required><br>
    <input type="number" name="price" step="0.01" placeholder="Price (₹)" required><br>
    <input type="number" name="stock" placeholder="Initial Stock Quantity" required><br>
    <input type="file" name="image" required><br>
    <button type="submit" name="add">Add Food</button>
</form>

<hr>

<h3>Food List</h3>
<table border="1">
    <tr>
        <th>Name</th>
        <th>Price (₹)</th>
        <th>Stock</th>
        <th>Image</th>
        <th>Action</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM food");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['name']}</td>
            <td>₹{$row['price']}</td>
            <td>{$row['stock']}</td>
            <td><img src='../images/{$row['image']}' width='60'></td>
            <td><a href='?delete={$row['id']}' onclick='return confirm(\"Delete this item?\")'>Delete</a></td>
        </tr>";
    }
    ?>
</table>

<p><a href="dashboard.php">Back to Dashboard</a></p>

</body>
</html>
