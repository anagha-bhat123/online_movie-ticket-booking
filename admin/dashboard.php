<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f0f2f5;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard-container {
            width: 80%;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .dashboard-container h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 32px;
            font-weight: bold;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .dashboard-card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-align: center;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .dashboard-card a {
            text-decoration: none;
            color: #3498db;
            font-size: 18px;
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        .dashboard-card a:hover {
            color: #2980b9;
        }

        .dashboard-footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['admin']); ?></h2>
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Manage Movies</h3>
                <a href="manage_movies.php">Go to Movies</a>
            </div>
            <div class="dashboard-card">
                <h3>Manage Theaters</h3>
                <a href="manage_theaters.php">Go to Theaters</a>
            </div>
            <div class="dashboard-card">
                <h3>Manage Shows</h3>
                <a href="manage_shows.php">Go to Shows</a>
            </div>
            <div class="dashboard-card">
                <h3>Manage Upcoming Shows</h3>
                <a href="coming_soon.php">Go to comming up Shows</a>
            </div>
            <div class="dashboard-card">
                <h3>Manage Food</h3>
                <a href="manage_food.php">Go to Food</a>
            </div>
            <div class="dashboard-card">
                <h3>View Users</h3>
                <a href="manage_users.php">Go to Users</a>
            </div>
            <div class="dashboard-card">
                <h3>Booking Reports</h3>
                <a href="report_booking.php">View Reports</a>
            </div>
            <div class="dashboard-card">
                <h3>View Feedback</h3>
                <a href="view_feedback.php">Feedback</a>
            </div>
            <div class="dashboard-card">
                <h3>Logout</h3>
                <a href="logout.php">Logout</a>
            </div>
        </div>
        <div class="dashboard-footer">
            Admin Panel - Get My Ticket!!
        </div>
    </div>
</body>
</html>