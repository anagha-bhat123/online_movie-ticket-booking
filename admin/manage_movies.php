<?php
session_start();
include('../includes/db.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Add movie
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $language = $_POST['language'];
    $description = $_POST['description'];
    $poster = $_FILES['poster']['name'];
    $tmp = $_FILES['poster']['tmp_name'];
    move_uploaded_file($tmp, "../images/$poster");

    $release_date = $_POST['release_date'];
$conn->query("INSERT INTO movies (title, genre, language, description, poster, release_date) 
              VALUES ('$title', '$genre', '$language', '$description', '$poster', '$release_date')");

    header("Location: manage_movies.php");
    exit();
}

// Update movie
// Update movie
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $language = $_POST['language'];
    $description = $_POST['description'];
    $release_date = $_POST['release_date']; // ✅ You were missing this

    if (!empty($_FILES['poster']['name'])) {
        $poster = $_FILES['poster']['name'];
        $tmp = $_FILES['poster']['tmp_name'];
        move_uploaded_file($tmp, "../images/$poster");
        $conn->query("UPDATE movies SET title='$title', genre='$genre', language='$language', description='$description', release_date='$release_date', poster='$poster' WHERE id=$id");
    } else {
        $conn->query("UPDATE movies SET title='$title', genre='$genre', language='$language', description='$description', release_date='$release_date' WHERE id=$id");
    }
    header("Location: manage_movies.php");
    exit();
}


// Delete movie
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM shows WHERE movie_id=$id");
    $conn->query("DELETE FROM movies WHERE id=$id");
    header("Location: manage_movies.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Movies</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        /* Headings */
        h2, h3 {
            color:  #2c3e50;
            text-align: center;
        }

        /* Form Styling */
        form {
            max-width: 500px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
        }

        form input[type="text"],
        form input[type="file"],
        form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333;
            font-size: 15px;
        }

        form textarea {
            height: 100px;
            resize: none;
        }

        form button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color:  #2c3e50;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }

        /* Table Styling */
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            border: 1px solid #ddd;
            color: #333;
        }

        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color:  #2c3e50;
            color: white;
            font-weight: bold;
        }

        td img {
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        /* Action links */
        a.action-link {
            color:  #2c3e50;
            text-decoration: none;
            font-weight: bold;
            margin: 0 5px;
            padding: 4px 10px;
            border-radius: 4px;
            transition: 0.3s ease;
        }

        a.action-link:hover {
            background-color:  #2c3e50;
            color: white;
        }

        /* Back button */
        p a.back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color:  #2c3e50;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s ease;
        }

        p a.back-btn:hover {
            background-color:  #2c3e50;
        }
    </style>
</head>
<body>
<h2>Manage Movies</h2>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
    <input type="text" name="title" placeholder="Movie Title" value="<?= isset($editRow['title']) ? $editRow['title'] : '' ?>" required><br>
    <input type="text" name="genre" placeholder="Genre" value="<?= isset($editRow['genre']) ? $editRow['genre'] : '' ?>" required><br>
    <input type="text" name="language" placeholder="Language" value="<?= isset($editRow['language']) ? $editRow['language'] : '' ?>" required><br>
    <textarea name="description" placeholder="Description" required><?= isset($editRow['description']) ? $editRow['description'] : '' ?></textarea><br>
<input type="date" name="release_date" value="<?= isset($editRow['release_date']) ? date('Y-m-d', strtotime($editRow['release_date'])) : '' ?>" required><br>

    <input type="file" name="poster"><br>
    <button type="submit" name="<?= isset($_GET['edit']) ? 'update' : 'add' ?>">
        <?= isset($_GET['edit']) ? 'Update Movie' : 'Add Movie' ?>
    </button>
</form>

<hr>

<h3>Movie List</h3>
<table border="1">
    <tr><th>Title</th><th>Genre</th><th>Lang</th><th>Poster</th><th>Release</th><th>Actions</th></tr>
    <?php
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $editResult = $conn->query("SELECT * FROM movies WHERE id=$id");
        $editRow = $editResult->fetch_assoc();
    }

    $result = $conn->query("SELECT * FROM movies");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['genre']}</td>
            <td>{$row['language']}</td>
            
            <td><img src='../images/{$row['poster']}' width='60'></td>
            <td>{$row['release_date']}</td>
            <td>
                <a href='?edit={$row['id']}' class='action-link'>Edit</a>
                <a href='?delete={$row['id']}' class='action-link' onclick='return confirm(\"Delete this movie?\")'>Delete</a>
            </td>
        </tr>";
    }
    ?>
</table>

<p><a href="dashboard.php" class="back-btn">Back to Dashboard</a></p>
</body>
</html>