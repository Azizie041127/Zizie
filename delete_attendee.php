<?php
session_start();
include 'connection.php';

$id = $_GET['id'];

// Check if ID is provided and numeric
if (!isset($id) || !is_numeric($id)) {
    // Handle invalid or missing ID case (redirect or error message)
    header('Location: dashboard.php');
    exit();
}

// Only proceed with deletion if confirmed
if (isset($_POST['confirmed']) && $_POST['confirmed'] == 'yes') {
    // Prepare the SQL query to delete the record
    $sql = "DELETE FROM attendance WHERE id=$id";

    // Check if deletion is successful
    if ($conn->query($sql) === TRUE) {
        // Redirect to admin.php after successful deletion
        header('Location: attendance.php');
        exit();
    } else {
        // Handle deletion failure (redirect or error message)
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Attendee</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @font-face {
            font-family: 'AareaKilometer';
            src: url('fonts/AareaKilometer-Regular.woff2') format('woff2'),
                 url('fonts/AareaKilometer-Regular.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'AareaKilometer', sans-serif;
            font-size: 1rem;
            text-transform: uppercase;
        }

        .container {
            max-width: 1500px;
            margin: 0 auto;
        }

        table {
            font-size: 0.9rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table thead th, .table tbody td {
            white-space: nowrap;
            padding: 12px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1 class="mt-5">Delete Attendee</h1>
        <form action="" method="POST">
            <div class="alert alert-danger" role="alert">
                Are you sure you want to delete this attendee record?
            </div>
            <button type="submit" name="confirmed" value="yes" class="btn btn-danger">Yes, delete it</button>
            <a href="attendance.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
