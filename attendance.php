

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

// Initialize variables
$total_attendees = 0;
$result_recent = null;

// Fetch the total number of attendees
$query = "SELECT COUNT(*) as total_attendees FROM attendance";
$result = $conn->query($query);

if ($result) {
    $total_attendees = $result->fetch_assoc()['total_attendees'];
} else {
    echo '<p>Error fetching total number of attendees.</p>';
}

// Fetch recent records (last 10 entries)
$query_recent = "SELECT * FROM attendance ORDER BY datetime DESC LIMIT 10"; // Use datetime for accurate ordering
$result_recent = $conn->query($query_recent);

if (!$result_recent) {
    echo '<p>Error fetching recent attendance records: ' . $conn->error;
}

// Get unique dates from the database
$unique_dates_query = "SELECT DISTINCT DATE(datetime) as date FROM attendance ORDER BY date DESC";
$result_dates = $conn->query($unique_dates_query); 


if (isset($_GET['added_date'])) {
    $added_date = $_GET['added_date'];
    echo '<div class="alert alert-success">Attendee added successfully!</div>';
}   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> 
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
            font-size: 1rem; /* Base font size */
            text-transform: uppercase; /* Ensure all text is in uppercase */
            background-color: #f0f5f4;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container-fluid {
            display: flex;
            flex: 1;
            padding: 0;
        }

        .main-content {
            margin-left: 250px; /* Adjust margin to accommodate sidebar width */
            flex: 1;
            padding: 20px;
            transition: margin-left 0.3s; /* Smooth transition for sidebar toggle */
        }

        .collapsed .main-content {
            margin-left: 0; /* Adjust based on collapsed sidebar width */
        }

        .container {
            max-width: 1500px; /* Set maximum width */
            margin: 0 auto; /* Center the container */
        }

        table {
            font-size: 0.9rem; /* Adjust the font size for table */
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table thead th, .table tbody td {
            white-space: nowrap;
            padding: 12px; /* Adjust padding for better readability */
        }

        /* Apply uppercase transformation globally */
        .table tbody td {
            text-transform: uppercase;
        }

        /* Exclude contact_email from uppercase transformation */
        .table tbody td.contact-email {
            text-transform: none;
        }

        .sidebar-toggle-icon {
            margin-right: 10px; 
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <?php 
            include 'connection.php';
            include 'sidebar.php'; 
        ?>
        <div class="main-content">
            <button id="sidebar-toggle" class="btn btn-primary mb-3">
                <i class="fas fa-chevron-left sidebar-toggle-icon"></i> 
            </button>
            <div class="content">
                <div class="container">
                <h2 class="mt-5">SEARCH ATTENDANCE</h2>
                    <div class="mb-4">
                        <form method="POST" action="results_attendance.php" class="form-inline">
                            <div class="form-group mr-2">
                                <label for="start_date" class="mr-2">Start Date:</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" required="">
                            </div>
                            <div class="form-group mr-2">
                                <label for="end_date" class="mr-2">End Date:</label>
                                  <input type="date" id="end_date" name="end_date" class="form-control" required="">
                             </div>
                                 <button type="submit" class="btn btn-primary">Search</button>
                        </form>

                    <div class="mb-4">
                        <h3>Summary</h3>
                        <p>Total number of attendees: <strong><?php echo $total_attendees; ?></strong></p> 
                    </div>

                    <div class="mb-4">
                        <h3>View by Date</h3>
                        <ul class="list-group">
                            <?php
                            if ($result_dates->num_rows > 0) {
                                while ($row_date = $result_dates->fetch_assoc()) {
                                    $date = $row_date['date'];
                                    echo '<li class="list-group-item"><a href="results_attendance.php?date=' . $date . '">' . $date . '</a></li>';
                                }
                            } else {
                                echo '<li class="list-group-item">No attendance records found.</li>';
                            }
                            ?>
                        </ul>
                    </div>

                    <div>
                        <h3>Recent Attendance Records</h3>
                        <div class="table-responsive">
                            <table class="table table-striped">
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
                                    if ($result_recent->num_rows > 0) {
                                        $i = 1;
                                        while ($row = $result_recent->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $i . "</td>";
                                            echo "<td>" . $row["name"] . "</td>";
                                            echo "<td>" . $row["email"] . "</td>";
                                            echo "<td>" . $row["company"] . "<br>"; 
                                            echo "<td>" . $row["meeting"] . "</td>";
                                            echo "<td>" . $row["date"] . " " . date('H:i:s', strtotime($row["datetime"])) . "</td>"; // Format datetime
                                            echo "</tr>";
                                            $i++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No recent attendance records found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.container-fluid').classList.toggle('collapsed');
        });
    </script>

</body>
</html>