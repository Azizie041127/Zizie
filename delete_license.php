<?php
session_start();
include 'connection.php'; 

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

// Get the license ID from the URL
if (isset($_GET['id'])) {
    $license_id = $_GET['id'];

    // Fetch the license details from the database to display in the confirmation message
    $stmt = $conn->prepare("SELECT software_name FROM licenses WHERE id = ?");
    $stmt->bind_param("i", $license_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $license = $result->fetch_assoc();
    $stmt->close();

    if (!$license) {
        header('Location: dashboard.php');
        exit();
    }

    // Delete the license record from the database if confirmed
    if (isset($_POST['confirm'])) {
        $stmt = $conn->prepare("DELETE FROM licenses WHERE id = ?");
        $stmt->bind_param("i", $license_id);

        if ($stmt->execute()) {
            echo "<script>alert('License deleted successfully.'); window.location.href = 'license.php';</script>";
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        $stmt->close();
        exit();
    }
} else {
    header('Location: license.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete License</title>
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
        <h1 class="mt-5">Delete License</h1>
        <form action="" method="POST">
            <div class="alert alert-danger" role="alert">
                Are you sure you want to delete the license for <?php echo htmlspecialchars($license['software_name']); ?>?
            </div>
            <button type="submit" name="confirm" class="btn btn-danger">Yes, delete it</button>
            <a href="license.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
