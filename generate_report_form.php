<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reports</title>
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
            font-family: 'AareaKilometer', sans-serif;
            text-transform: uppercase;
            background-color: #f0f5f4;
        }
        .content {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #336b58;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group label {
            font-weight: bold;
            color: #336b58;
        }
        .form-control {
            border: 1px solid #b5d1c7;
            border-radius: 5px;
            box-shadow: none;
        }
        .form-control:focus {
            border-color: #60a493;
            box-shadow: 0 0 0 0.2rem rgba(96, 164, 147, 0.25);
        }
        .btn-primary {
            background-color: #60a493;
            border-color: #60a493;
            width: 100%;
            margin-top: 20px;
        }
        .btn-primary:hover {
            background-color: #336b58;
            border-color: #336b58;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <a href="attendance.php" class="btn btn-secondary mb-3">Go Back</a>
            <h1>Generate Reports</h1>
            <form action="generate_report.php" method="POST">
                <div class="form-group">
                    <label for="start_date">START DATE:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="end_date">END DATE:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>
                <div class="form-group">
                    <label for="event_name">EVENT/MEETING NAME:</label>
                    <input type="text" class="form-control" id="event_name" name="event_name">
                </div>
                <div class="form-group">
                    <label for="event_time">EVENT/MEETING TIME:</label>
                    <input type="time" class="form-control" id="event_time" name="event_time">
                </div>
                <div class="form-group">
                    <label for="event_location">EVENT/MEETING LOCATION:</label>
                    <input type="text" class="form-control" id="event_location" name="event_location">
                </div>
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </form>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
