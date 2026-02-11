// Get the modal and elements
var modal = document.getElementById("bookingModal");
var closeBtn = document.getElementsByClassName("close")[0];
var buttons = document.querySelectorAll(".book-ticket-btn");

// Open modal when 'Book Tickets' button is clicked
buttons.forEach(button => {
    button.addEventListener('click', function() {
        var movieId = this.getAttribute('data-movie-id');
        document.getElementById('movie_id').value = movieId;
        modal.style.display = "block";
    });
});

// Close the modal when the 'x' is clicked
closeBtn.onclick = function() {
    modal.style.display = "none";
}

// Close the modal if clicked outside of the modal content
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
