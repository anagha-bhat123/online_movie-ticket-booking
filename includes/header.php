<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Movie Booking</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/main.js" defer></script>
</head>
<body>
<header>
    <div class="header-container">
        <div class="logo">Movie Ticket</div>
        <nav class="main-nav">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="login.php" class="sign-in-btn">Sign In</a></li>
            </ul>
        </nav>
        <div class="hamburger" onclick="toggleDashboard()">
            &#9776;
        </div>
    </div>
</header>
<style>
/* Header Styling */
header {
    background-color: #1c1c1c;
    color: #fff;
    padding: 15px 0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
}

.header-container {
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}

/* Logo (left) */
.logo {
    font-size: 26px;
    font-weight: bold;
    color: #ff3333;
    flex: 1;
}

/* Navigation (center) */
.main-nav {
    flex: 2;
    text-align: center;
}

.main-nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: inline-flex;
    gap: 25px;
}

.main-nav ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 17px;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.main-nav ul li a:hover {
    background-color: #ff3333;
    color: #fff;
}

/* Sign In Button */
.sign-in-btn {
    background-color: #ff3333;
    padding: 6px 15px;
    border-radius: 5px;
    font-weight: 600;
    text-transform: uppercase;
}

/* Dashboard Icon (right) */
.dashboard-icon {
    font-size: 26px;
    cursor: pointer;
    color: #fff;
    flex: 1;
    text-align: right;
    transition: color 0.3s ease;
}

.dashboard-icon:hover {
    color: #ff3333;
}
</style>
    <!-- Sign In Modal -->
    <div id="signInModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Sign In</h2>
            <div class="modal-options">
                <button id="loginOption">Login</button>
                <button id="registerOption">Register</button>
            </div>
            <div id="loginForm" class="form-content" style="display:none;">
                <h3>Login</h3>
                <form action="login.php" method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Login</button>
                </form>
            </div>
            <div id="registerForm" class="form-content" style="display:none;">
                <h3>Register</h3>
                <form action="register.php" method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
<style>
    /* Header Styling */
header {
    background-color: #1c1c1c;
    color: #fff;
    padding: 15px 0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
}

.header-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}

/* Logo (left) */
.logo {
    font-size: 26px;
    font-weight: bold;
    color: #ff3333;
}

/* Navigation (center) */
.main-nav {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}

.main-nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 25px;
}

.main-nav ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 17px;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.main-nav ul li a:hover {
    background-color: #ff3333;
    color: #fff;
}

/* Sign In Button */
.sign-in-btn {
    background-color: #ff3333;
    padding: 6px 15px;
    border-radius: 5px;
    font-weight: 600;
    text-transform: uppercase;
}

/* Hamburger Icon (right) */
.hamburger {
    font-size: 26px;
    cursor: pointer;
    color: #fff;
    transition: color 0.3s ease;
}

.hamburger:hover {
    color: #ff3333;
}
</style>
    <script>
        var modal = document.getElementById("signInModal");
        var signInBtn = document.getElementById("signInBtn");
        var closeBtn = document.getElementsByClassName("close")[0];
        var loginOption = document.getElementById("loginOption");
        var registerOption = document.getElementById("registerOption");
        var loginForm = document.getElementById("loginForm");
        var registerForm = document.getElementById("registerForm");

        signInBtn.onclick = function() {
            modal.style.display = "block";
        }

        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        loginOption.onclick = function() {
            loginForm.style.display = "block";
            registerForm.style.display = "none";
        }

        registerOption.onclick = function() {
            registerForm.style.display = "block";
            loginForm.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
