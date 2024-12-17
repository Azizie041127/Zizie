<?php
session_start();
include 'connection.php';

date_default_timezone_set("Asia/Kuala_Lumpur");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $company = htmlspecialchars($_POST['company']);
    $meeting = htmlspecialchars($_POST['meeting']);
    $date = htmlspecialchars($_POST['date']);
    
    // Convert the date to "YYYY-MM-DD" format for MySQL
    $formattedDate = date("Y-m-d", strtotime($date));
    $current = new DateTime();
    $current = $current->format('Y-m-d H:i:s');

    // Insert data into the database
    $sql = "INSERT INTO attendance (name, email, company, meeting, date, datetime) 
            VALUES ('$name', '$email', '$company', '$meeting', '$formattedDate','$current')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Your attendance has been recorded";
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();

    // Redirect to the form status page
    header('Location: submission_status.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Form Submission</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Submission Status</h2>
        <div class="alert alert-info"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
        <a href="form.php" class="btn btn-primary">Back to Form</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
