<?php
session_start();
include('../includes/db.php');

// Check for required POST parameters
if (!isset($_POST['movie_id'], $_POST['theater_id'], $_POST['show_date'], $_POST['show_time'])) {
    echo "Error: Missing booking parameters.";
    exit();
}

// Sanitize input
$movie_id = $conn->real_escape_string($_POST['movie_id']);
$theater_id = $conn->real_escape_string($_POST['theater_id']);
$show_date = $conn->real_escape_string($_POST['show_date']);
$show_time = $conn->real_escape_string($_POST['show_time']);
$selected_food = isset($_POST['food']) ? $_POST['food'] : [];

// Fetch movie title
$movie_stmt = $conn->prepare("SELECT title FROM movies WHERE id = ?");
$movie_stmt->bind_param("i", $movie_id);
$movie_stmt->execute();
$movie_result = $movie_stmt->get_result();
if ($movie_result->num_rows === 0) {
    echo "Error: Movie not found.";
    exit();
}
$movie_title = $movie_result->fetch_assoc()['title'];

// Fetch theater name
$theater_stmt = $conn->prepare("SELECT name FROM theaters WHERE id = ?");
$theater_stmt->bind_param("i", $theater_id);
$theater_stmt->execute();
$theater_result = $theater_stmt->get_result();
if ($theater_result->num_rows === 0) {
    echo "Error: Theater not found.";
    exit();
}
$theater_name = $theater_result->fetch_assoc()['name'];

// Fetch food details
$total_food_price = 0;
$food_details = [];

foreach ($selected_food as $food_id => $quantity) {
    $quantity = (int)$quantity;
    if ($quantity > 0) {
        $food_stmt = $conn->prepare("SELECT name, price FROM food WHERE id = ?");
        $food_stmt->bind_param("i", $food_id);
        $food_stmt->execute();
        $food_result = $food_stmt->get_result();

        if ($food_result->num_rows > 0) {
            $food = $food_result->fetch_assoc();
            $subtotal = $food['price'] * $quantity;
            $total_food_price += $subtotal;
            $food_details[] = [
                'name' => $food['name'],
                'price' => $food['price'],
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }
        .confirmation-details, .food-details {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #ff4747;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #ff4747;
            color: white;
        }
        .total {
            font-weight: bold;
            text-align: right;
        }
        .confirm-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .confirm-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="confirmation-details">
    <h2>Booking Confirmation</h2>
    <p><strong>Movie:</strong> <?php echo htmlspecialchars($movie_title); ?></p>
    <p><strong>Theater:</strong> <?php echo htmlspecialchars($theater_name); ?></p>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($show_date); ?></p>
    <p><strong>Time:</strong> <?php echo htmlspecialchars($show_time); ?></p>
</div>

<div class="food-details">
    <h2>Food Order Details</h2>
    <table>
        <thead>
            <tr>
                <th>Food Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($food_details)): ?>
                <?php foreach ($food_details as $food): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($food['name']); ?></td>
                        <td><?php echo $food['quantity']; ?></td>
                        <td>₹<?php echo $food['price']; ?></td>
                        <td>₹<?php echo $food['subtotal']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="total">Total Food Price</td>
                    <td class="total">₹<?php echo $total_food_price; ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No food selected.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<form method="post" action="finalize_booking.php">
    <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movie_id); ?>">
    <input type="hidden" name="theater_id" value="<?php echo htmlspecialchars($theater_id); ?>">
    <input type="hidden" name="show_date" value="<?php echo htmlspecialchars($show_date); ?>">
    <input type="hidden" name="show_time" value="<?php echo htmlspecialchars($show_time); ?>">
    <input type="hidden" name="food_order" value="<?php echo htmlspecialchars(json_encode($food_details)); ?>">
    <button type="submit" class="confirm-button">Confirm Booking</button>
</form>

</body>
</html>
