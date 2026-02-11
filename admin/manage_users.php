<?php
session_start();
include('../includes/db.php');

// Fetch all users from the database
$query = "SELECT id, name, email FROM users ORDER BY id ASC";
$result = $conn->query($query);

// Check for database query errors
if (!$result) {
    die("Error fetching users: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 20px; }
        h2 { text-align: center; color: #333; }
        table { width: 90%; margin: auto; border-collapse: collapse; background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: center; border-bottom: 1px solid #ddd; }
        th { background: #2c3e50; color: #fff; }
        tr:nth-child(even) { background: #f9f9f9; }
    </style>
</head>
<body>
    <h2>List of Users</h2>
    <table>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="3">No users found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>