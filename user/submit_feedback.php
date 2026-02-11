<?php
session_start();
include('../includes/db.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Validate the input
    if (empty($name) || empty($email) || empty($message)) {
        // Redirect back to the contact page with an error message
        header("Location: contact.php?error=1");
        exit();
    }

    // Insert the feedback into the database
    $stmt = $conn->prepare("INSERT INTO feedback (user_name, user_email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // Redirect back to the contact page with a success message
        header("Location: contact.php?success=1");
    } else {
        // Redirect back to the contact page with an error message
        header("Location: contact.php?error=1");
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    // Redirect to the contact page if the request method is not POST
    header("Location: contact.php");
    exit();
}