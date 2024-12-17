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

// Check if a specific date is provided in the URL
if (isset($_GET['date'])) {
    $selected_date = $_GET['date'];
    $query = "SELECT * FROM attendance WHERE DATE(datetime) = '$selected_date'";
    $result = $conn->query($query); 
} else { 
    // Handle date range search 
    if (!isset($_POST['start_date']) || !isset($_POST['end_date'])) {
        die('Date range is required');
    }

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validate and format dates
    $start_date = date('Y-m-d', strtotime($start_date));
    $end_date = date('Y-m-d', strtotime($end_date));

    // Check if start date is before end date
    if ($start_date > $end_date) {
        die('Start date must be before or equal to end date');
    }

    // Prepare and execute the query
    $query = "SELECT * FROM attendance WHERE DATE(datetime) BETWEEN ? AND ?"; 
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die('Query preparation failed: ' . $conn->error);
    }

    $stmt->bind_param('ss', $start_date, $end_date);

    if (!$stmt->execute()) {
        die('Query execution failed: ' . $stmt->error); 
    }

    $result = $stmt->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Attendance - Attendance System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f5f4; /* Light gray background */
            font-family: 'AareaKilometer', sans-serif;
        }

        .container {
            max-width: 900px; /* Adjust container width as needed */
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .search-criteria {
            background-color: #f0f5f4; /* Match background color */
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        table {
            width: 100%; 
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-primary {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

    </style>
</head>
<body>
    <div class="container"> 
        <h2>Search Results</h2>

        <div class="search-criteria">
            <?php if (isset($selected_date)) : ?>
                <p><strong>Search Criteria:</strong> Date: <?php echo $selected_date; ?></p>
            <?php else: ?> 
                <p><strong>Search Criteria:</strong> From <?php echo $start_date; ?> to <?php echo $end_date; ?></p>
            <?php endif; ?>
            <a href="attendance.php" class="btn btn-secondary mb-3">Go Back</a>
            <?php if (isset($selected_date)) : ?>
                <a href="add_attendee.php?date=<?php echo $selected_date; ?>" class="btn btn-secondary mb-3">Add Attendee</a>
            <?php endif; ?> 
            <a href="generate_report_form.php?date=<?php echo $selected_date; ?>" class="btn btn-secondary mb-3">Generate Report</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>COMPANY/AGENCY<br>DIVISION/DEPARTMENT</th>
                        <th>MEETING</th>
                        <th>DATE &amp; TIME</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $i . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["company"] . "<br>"; 
                            echo "<td>" . $row["meeting"] . "</td>";
                            echo "<td>" . $row["date"] . " " . date('H:i:s', strtotime($row["datetime"])) . "</td>";
                            echo "</tr>";
                            $i++;
                        }
                    } else {
                        if (isset($selected_date)) {
                            echo "<tr><td colspan='6'>No attendance records found for the selected date.</td></tr>";
                        } else {
                            echo "<tr><td colspan='6'>No attendance records found within the specified date range.</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>