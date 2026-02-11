<?php
session_start();
include('../includes/db.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Handle add coming soon movie
if (isset($_POST['add'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $language = $conn->real_escape_string($_POST['language']);
    $description = $conn->real_escape_string($_POST['description']);
    $release_date = $_POST['release_date'];

    // Handle poster upload
    if (!empty($_FILES['poster']['name'])) {
        $poster = time() . '_' . basename($_FILES['poster']['name']); // unique filename
        $tmp = $_FILES['poster']['tmp_name'];
        move_uploaded_file($tmp, "../images/$poster");
    } else {
        $poster = '';
    }

    $conn->query("INSERT INTO coming_soon (title, genre, language, description, poster, release_date) VALUES ('$title', '$genre', '$language', '$description', '$poster', '$release_date')");
    header("Location: manage_coming_soon.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM coming_soon WHERE id=$id");
    header("Location: manage_coming_soon.php");
    exit();
}

// Fetch all coming soon movies
$result = $conn->query("SELECT * FROM coming_soon ORDER BY release_date ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Coming Soon Movies</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
        form, table { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; max-width: 700px; margin: 20px auto; }
        input[type=text], input[type=date], textarea { width: 100%; padding: 8px; margin-top: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #2c3e50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #1a2736; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: center; border-bottom: 1px solid #ddd; }
        th { background: #2c3e50; color: white; }
        img { max-width: 60px; border-radius: 4px; }
        a.delete-link { color: red; text-decoration: none; font-weight: bold; }
        a.delete-link:hover { text-decoration: underline; }
        h2 { text-align: center; color: #2c3e50; }
    </style>
</head>
<body>

<h2>Manage Coming Soon Movies</h2>

<!-- Add Coming Soon Movie Form -->
<form method="post" enctype="multipart/form-data">
    <h3>Add New Coming Soon Movie</h3>
    <input type="text" name="title" placeholder="Movie Title" required>
    <input type="text" name="genre" placeholder="Genre" required>
    <input type="text" name="language" placeholder="Language" required>
    <textarea name="description" placeholder="Description" rows="4" required></textarea>
    <input type="date" name="release_date" required>
    <input type="file" name="poster" accept="image/*" required>
    <button type="submit" name="add">Add Movie</button>
</form>

<!-- List of Coming Soon Movies -->
<table>
    <tr>
        <th>Title</th>
        <th>Genre</th>
        <th>Language</th>
        <th>Description</th>
        <th>Release Date</th>
        <th>Poster</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['genre']) ?></td>
            <td><?= htmlspecialchars($row['language']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['release_date']) ?></td>
            <td><img src="../images/<?= htmlspecialchars($row['poster']) ?>" alt="Poster"></td>
            <td>
                <a href="?delete=<?= $row['id'] ?>" class="delete-link" onclick="return confirm('Delete this movie?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<p style="text-align:center;"><a href="dashboard.php">← Back to Dashboard</a></p>

</body>
</html>
