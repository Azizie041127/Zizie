<?php
session_start(); // Start or resume session

// Check if $_SESSION['message'] is set and not null before using it
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';

// Clear the message from session to avoid displaying it again on page refresh
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Submission Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
        }
        .message {
            margin-bottom: 20px;
            padding: 10px;
            background: #dff0d8;
            border: 1px solid #d0e9c6;
            color: #3c763d;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Submission Status</h2>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <a href="attendance.php" class="btn btn-primary">Back to Attendance Dashboard</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
