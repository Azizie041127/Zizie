<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Font Awesome for icons -->
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
            <button id="sidebar-toggle" class="btn btn-primary">
                <i class="fas fa-chevron-left sidebar-toggle-icon"></i> 
            </button>
            <div class="content">
                <div class="container">
                    <h2 class="mt-5">License Application Management</h2>

                    <div class="my-3">
                        <a href="add_license.php" class="btn btn-success">Add License</a>
                        <a href="generate_reminder.php" class="btn btn-info">Generate Reminder</a>
                        <a href="submit_invoice_renewal.php" class="btn btn-warning">Submit Invoice/Renewal</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th> <!-- Numbering column -->
                                    <th>License Name</th>
                                    <th>Version</th>
                                    <th>License Key</th>
                                    <th>Type</th>
                                    <th>Delivery Order</th>
                                    <th>Our Ref</th>
                                    <th>Quantity</th>
                                    <th>Purchase</th>
                                    <th>Expiry</th>
                                    <th>Renewal</th>
                                    <th>Validity</th>
                                    <th>Vendor</th>
                                    <th>Contact Name</th>
                                    <th>Contact Email</th>
                                    <th>Admin</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT * FROM licenses");
                                $count = 1; // Initialize counter
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $count++ . '</td>'; // Output counter
                                    echo '<td>' . htmlspecialchars($row['software_name']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['version']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['license_key']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['license_type']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['delivery_order']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['our_ref']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
                                    echo '<td>' . date('d-m-Y', strtotime($row['purchase_date'])) . '</td>';
                                    echo '<td>' . date('d-m-Y', strtotime($row['expiry_date'])) . '</td>';
                                    echo '<td>' . date('d-m-Y', strtotime($row['renewal_date'])) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['validity']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['vendor']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['contact_name']) . '</td>';
                                    echo '<td class="contact-email">' . htmlspecialchars($row['contact_email']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['license_administrator']) . '</td>';
                                    echo '<td>
                                        <a href="edit_license.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete_license.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm">Delete</a>
                                        </td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
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
