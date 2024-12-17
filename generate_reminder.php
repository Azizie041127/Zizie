<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reminder</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h2 class="mt-5">Generate License Expiry Reminders</h2>
        <form action="send_reminder.php" method="post">
            <div class="alert alert-warning mt-3">
                <p>Are you sure you want to send reminder emails for licenses expiring within the next 30 days?</p>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Send Reminders</button>
        </form>
        <a href="license.php" class="btn btn-secondary mt-3">Back to License Management</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
