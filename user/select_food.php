
<?php
session_start();
include('../includes/db.php');

// Redirect to login if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if seats are selected
    if (!isset($_POST['selected_seats']) || !is_array($_POST['selected_seats']) || count($_POST['selected_seats']) === 0) {
        die("No seats selected.");
    }

    // Store selected values into session
    $_SESSION['seats'] = $_POST['selected_seats'];
    $_SESSION['movie_id'] = $_POST['movie_id'] ?? '';
    $_SESSION['theater_id'] = $_POST['theater_id'] ?? '';
    $_SESSION['show_date'] = $_POST['show_date'] ?? '';
    $_SESSION['show_time'] = $_POST['show_time'] ?? '';
} else {
    die("Invalid access method.");

    // Clean URL redirect
    header("Location: select_food.php");
    exit();
}

// Get booking data from session
$movie_id = $_SESSION['movie_id'] ?? null;
$theater_id = $_SESSION['theater_id'] ?? null;
$show_date = $_SESSION['show_date'] ?? null;
$show_time = $_SESSION['show_time'] ?? null;

// Get food items from DB
$food_items = [];
$res = $conn->query("SELECT * FROM food");
while ($row = $res->fetch_assoc()) {
    $food_items[] = $row;
}
?>
<h3>Selected Seats:</h3>
<p>
<?php
if (isset($_SESSION['seats']) && is_array($_SESSION['seats'])) {
    echo implode(', ', $_SESSION['seats']);
} else {
    echo "No seats selected.";
}
?>
</p>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Food</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color:rgb(23, 22, 22);
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h3 {
            text-align: center;
            color: #ff4747;
        }

        .food-section {
            max-width: 1200px;
            margin: 0 auto;
        }

        .food-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .food-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .food-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .food-card img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .food-card p {
            font-size: 16px;
            color: #666;
        }

        .food-card input {
            width: 60px;
            padding: 5px;
            font-size: 16px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }

        .cart-section {
            max-width: 800px;
            margin: 30px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-section h4 {
            color: #ff4747;
            margin-bottom: 15px;
        }

        .cart-section p {
            font-size: 16px;
            line-height: 1.5;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }

        button {
            padding: 12px 25px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="food-section">
        <h3>Select Food Items</h3>
        <div class="food-cards">
            <?php foreach ($food_items as $food): ?>
                <div class="food-card">
                    <img src="../images/<?php echo htmlspecialchars($food['image'] ?? 'default_image.jpg'); ?>" alt="<?php echo htmlspecialchars($food['name']); ?>">
                    <p><?php echo htmlspecialchars($food['name']); ?></p>
                    <p>Price: ₹<?php echo htmlspecialchars($food['price']); ?></p>
                    <input type="number" min="0" value="0" onchange="updateCart(this, <?php echo $food['id']; ?>, '<?php echo htmlspecialchars($food['name']); ?>', <?php echo $food['price']; ?>)">
                </div>
            <?php endforeach; ?>
        </div>
    </div>

   <form method="post" action="summary.php">
    <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movie_id); ?>">
    <input type="hidden" name="theater_id" value="<?php echo htmlspecialchars($theater_id); ?>">
    <input type="hidden" name="show_date" value="<?php echo htmlspecialchars($show_date); ?>">
    <input type="hidden" name="show_time" value="<?php echo htmlspecialchars($show_time); ?>">

    <?php if (isset($_SESSION['selected_seats']) && is_array($_SESSION['selected_seats'])): ?>
        <?php foreach ($_SESSION['selected_seats'] as $seat): ?>
            <input type="hidden" name="selected_seats[]" value="<?php echo htmlspecialchars($seat); ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <div id="food-items"></div>
    <button type="submit">Proceed to Summary</button>
</form>


    <div class="cart-section" id="cart">
        <h4>Your Cart</h4>
        <p id="cart-items">No items added yet.</p>
        <p id="cart-total">Total: ₹0</p>
    </div>

    <script>
        let cart = {};
        let total = 0;

        function updateCart(input, id, name, price) {
            const quantity = parseInt(input.value);
            if (quantity > 0) {
                cart[id] = { name, price, quantity };
            } else {
                delete cart[id];
            }

            // Update hidden inputs for food items
            const foodItemsDiv = document.getElementById('food-items');
            foodItemsDiv.innerHTML = '';
            for (const key in cart) {
                const item = cart[key];
                foodItemsDiv.innerHTML += `<input type="hidden" name="food[${key}]" value="${item.quantity}">`;
            }

            // Update cart display
            const cartItems = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total');
            let cartContent = '';
            total = 0;

            for (const key in cart) {
                const item = cart[key];
                cartContent += `${item.name} x ${item.quantity} = ₹${item.price * item.quantity}<br>`;
                total += item.price * item.quantity;
            }

            cartItems.innerHTML = cartContent || 'No items added yet.';
            cartTotal.innerHTML = `Total: ₹${total}`;
        }
    </script>
</body>
</html>
