</div> <!-- end of .content -->
<footer>
    <p>&copy; <?php echo date('Y'); ?> Online Movie Booking System</p>
</footer>
<style>
/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color:rgb(61, 55, 55);
    color: #f4f4f4;
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Ensure body takes full height */
}

/* Container for movie list */
.container {
    flex: 1; /* Makes the container expand to take available space */
    padding: 20px;
    text-align: center;
}

h2 {
    font-size: 32px;
    margin-bottom: 20px;
    color: #ffcc00;
}

.movie-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.movie {
    background-color: #333;
    padding: 15px;
    border-radius: 8px;
    width: 200px;
    text-align: center;
    color: white;
}

.movie h3 {
    font-size: 22px;
    margin-bottom: 10px;
}

.movie p {
    font-size: 16px;
    color: #ccc;
}

/* Footer */
footer {
    background-color:dark grey;
    padding: 20px;
    text-align: center;
    color: #f4f4f4;
    margin-top: auto; /* Push footer to the bottom of the page */
}

footer .footer-content {
    max-width: 1000px;
    margin: 0 auto;
}

footer .social-links {
    list-style-type: none;
    padding: 0;
    display: flex;
    justify-content: center;
    margin-top: 15px;
}

footer .social-links li {
    margin: 0 15px;
}

footer .social-links li a {
    text-decoration: none;
    color: #ffcc00;
    font-size: 24px;
    transition: color 0.3s ease;
}

footer .social-links li a:hover {
    color: white;
}
</style>
</body>
</html>
