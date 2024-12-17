<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

// Include database connection
include 'connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Safely retrieve form inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $meeting = mysqli_real_escape_string($conn, $_POST['meeting']);
    $datetime = mysqli_real_escape_string($conn, $_POST['datetime']); 

    // Insert data into the database
    $query = "INSERT INTO attendance (name, email, company, meeting, date, datetime) 
              VALUES ('$name', '$email', '$company', '$meeting', DATE('$datetime'), '$datetime')"; 

    if (mysqli_query($conn, $query)) {
        // Redirect to the attendance page after successful insertion
        header('Location: attendance.php');
        exit();
    } else {
        // Handle database errors
        echo "<script>alert('Error adding attendee: " . mysqli_error($conn) . "');</script>"; 
    }
}

// Close the database connection
mysqli_close($conn);
?>