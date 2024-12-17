<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Store the page they were trying to access
    $_SESSION['redirect_page'] = basename($_SERVER['PHP_SELF']);

    // Redirect to the login page
    header('Location: login.php');
    exit();
}

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
        // Get the date of the newly added attendee
        $added_date = date('Y-m-d', strtotime($datetime)); 

        // Redirect to the attendance page with the added date as a parameter
        header('Location: attendance.php?added_date=' . $added_date); 
        exit();
    } else {
        // Handle database errors
        echo "<script>alert('Error adding attendee: " . mysqli_error($conn) . "');</script>"; 
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Attendee</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @font-face {
            font-family: 'AareaKilometer';
            src: url('css/AareaKilometer-Regular.woff2') format('woff2'),
                 url('css/AareaKilometer-Regular.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }
        body {
            background-color: #f5f7fa;
            font-family: 'AareaKilometer', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .content {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #6c757d;
            margin-bottom: 30px;
        }
        .form-group label {
            font-weight: bold;
            text-transform: uppercase;
        }
        .form-control {
            border-radius: 5px;
            font-size: 1em;
        }
        .btn-primary {
            background-color: #6c757d;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1em;
            margin-top: 20px;
        }
        .btn-primary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <a href="attendance.php" class="btn btn-secondary mb-3">Go Back</a>
            <h1>ADD ATTENDEE</h1>
            <form id="attendanceForm" action="submit_attendance.php" method="POST">
                <div class="form-group">
                    <label for="name">NAME:</label>
                    <input type="text" class="form-control" id="name" name="name" required="" oninput="this.value = this.value.toUpperCase()">
                </div>
                <div class="form-group">
                    <label for="email">EMAIL:</label>
                    <input type="email" class="form-control" id="email" name="email" required="">
                </div>
                <div class="form-group">
                    <label for="company">COMPANY/AGENCY/DIVISION/DEPARTMENT:</label>
                    <input type="text" class="form-control" id="company" name="company" required="" oninput="this.value = this.value.toUpperCase()">
                </div>
                <div class="form-group">
                    <label for="meeting">MEETING:</label>
                    <input type="text" class="form-control" id="meeting" name="meeting" required="" oninput="this.value = this.value.toUpperCase()">
                </div>
                <?php
                if (isset($_GET['date'])) { 
                    $selected_date = $_GET['date'];
                    ?>
                    <div class="form-group">
                        <label for="datetime">DATE & TIME:</label>
                        <input type="datetime-local" class="form-control" id="datetime" name="datetime" required="" value="<?php echo date('Y-m-d\TH:i:s', strtotime($selected_date)); ?>"> 
                    </div>
                <?php } else { ?>
                    <div class="form-group">
                        <label for="datetime">DATE & TIME:</label>
                        <input type="datetime-local" class="form-control" id="datetime" name="datetime" required=""> 
                    </div>
                <?php } ?>
                <button type="submit" class="btn btn-primary btn-block">ADD ATTENDEE</button>
            </form>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>

