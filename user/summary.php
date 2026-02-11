<?php
session_start();
include('../includes/db.php');

// Step 1: Validate SESSION data
// Step 1: Validate SESSION data
if (!isset($_SESSION['movie_id'], $_SESSION['theater_id'], $_SESSION['show_date'], $_SESSION['show_time'], $_SESSION['seats'])) {
    die("❌ Invalid access. Required data missing.");
}

$movie_id = $_SESSION['movie_id'];
$theater_id = $_SESSION['theater_id'];
$show_date = $_SESSION['show_date'];
$show_time = $_SESSION['show_time'];
$selected_seats = $_SESSION['seats'];  // ✅ FIXED

$food_items = $_POST['food'] ?? [];
$_SESSION['food'] = $food_items;  // array like [food_id => qty]
// Step 2: Fetch movie and theater names
$movie = $conn->query("SELECT title FROM movies WHERE id = $movie_id")->fetch_assoc();
$theater = $conn->query("SELECT name FROM theaters WHERE id = $theater_id")->fetch_assoc();

// Step 3: Calculate totals
$seat_price = 150;
$total_seat_price = count($selected_seats) * $seat_price;

$food_total = 0;
$food_details = [];
if (!empty($food_items)) {
    $food_ids = implode(',', array_keys($food_items));
    $res = $conn->query("SELECT * FROM food WHERE id IN ($food_ids)");
    while ($row = $res->fetch_assoc()) {
        $fid = $row['id'];
        $qty = intval($food_items[$fid]);
        if ($qty > 0) {
            $subtotal = $qty * $row['price'];
            $food_total += $subtotal;
            $food_details[] = [
                'name' => $row['name'],
                'price' => $row['price'],
                'qty' => $qty,
                'total' => $subtotal
            ];
        }
    }
}

$grand_total = $total_seat_price + $food_total;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Summary</title>
    <style>
        body { font-family: Arial; background:rgb(23, 23, 23); padding: 20px; }
        .summary-box { background: #fff; padding: 20px; border-radius: 10px; max-width: 800px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #ff4747; text-align: center; }
        .section { margin-bottom: 20px; }
        .food-table, .food-table td, .food-table th {
            border: 1px solid #ccc; border-collapse: collapse; padding: 10px;
        }
        .food-table th { background: #eee; }
        .total { font-weight: bold; font-size: 18px; text-align: right; margin-top: 15px; }
        .pay-btn {
            background: #28a745; color: white; padding: 12px 25px; border: none;
            border-radius: 5px; cursor: pointer; font-size: 16px; display: block; margin: 30px auto 0;
        }
    </style>
</head>
<body>
    <div class="summary-box">
        <h2>Booking Summary</h2>

        <div class="section">
            <h3>Movie & Show Info</h3>
            <p><strong>Movie:</strong> <?= htmlspecialchars($movie['title']) ?></p>
            <p><strong>Theater:</strong> <?= htmlspecialchars($theater['name']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($show_date) ?></p>
            <p><strong>Time:</strong> <?= htmlspecialchars($show_time) ?></p>
        </div>

        <div class="section">
            <h3>Selected Seats</h3>
            <p><?= implode(', ', array_map('htmlspecialchars', $selected_seats)) ?> (₹<?= $seat_price ?> x <?= count($selected_seats) ?> = ₹<?= $total_seat_price ?>)</p>
        </div>

        <div class="section">
            <h3>Food Items</h3>
            <?php if (count($food_details)): ?>
                <table class="food-table" width="100%">
                    <tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr>
                    <?php foreach ($food_details as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td>₹<?= $item['price'] ?></td>
                            <td>₹<?= $item['total'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No food items selected.</p>
            <?php endif; ?>
        </div>

        <div class="total">Grand Total: ₹<?= $grand_total ?></div>

        <form action="payment.php" method="POST">
            <input type="hidden" name="grand_total" value="<?= $grand_total ?>">
            <button class="pay-btn" type="submit">Pay Now</button>
        </form>
    </div>
</body>
</html>
