<?php
session_start();
include('../includes/db.php');

// Optional: Admin login check
// if (!isset($_SESSION['admin'])) {
//     die("Access denied. Please login as admin.");
// }

$sql = "
    SELECT 
        b.id, 
        u.name AS user_name, 
        u.email, 
        b.movie_name, 
        b.theater_name, 
        b.show_date, 
        b.show_time, 
        b.seat_number, 
        b.food_items, 
        b.total_amount, 
        b.status 
    FROM bookings b
    LEFT JOIN users u ON b.user_id = u.id
    ORDER BY b.id DESC
";
$result = $conn->query($sql);
$bookings = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Report - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-3xl font-bold text-center mb-6 text-red-600">🎟️ Booking Report</h1>

        <input type="text" id="searchInput" placeholder="Search by movie, user, or theater..." class="mb-4 p-2 border border-gray-300 rounded w-full">

        <div class="overflow-auto">
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">User</th>
                        <th class="p-2 border">Email</th>
                        <th class="p-2 border">Movie</th>
                        <th class="p-2 border">Theater</th>
                        <th class="p-2 border">Date</th>
                        <th class="p-2 border">Time</th>
                        <th class="p-2 border">Seats</th>
                        <th class="p-2 border">Food</th>
                        <th class="p-2 border">Amount</th>
                        <th class="p-2 border">Status</th>
                    </tr>
                </thead>
                <tbody id="reportTable" class="text-sm">
                    <?php foreach ($bookings as $row): ?>
                        <tr class="border hover:bg-gray-50">
                            <td class="p-2 border"><?= $row['id'] ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($row['user_name'] ?? 'Guest') ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($row['email'] ?? '-') ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($row['movie_name']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($row['theater_name']) ?></td>
                            <td class="p-2 border"><?= $row['show_date'] ?></td>
                            <td class="p-2 border"><?= $row['show_time'] ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($row['seat_number']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($row['food_items']) ?></td>
                            <td class="p-2 border text-right">₹<?= number_format($row['total_amount'], 2) ?></td>
                            <td class="p-2 border">
                                <span class="<?= $row['status'] == 'success' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('#reportTable tr');

        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            tableRows.forEach(row => {
                const rowText = row.innerText.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
