<?php
// index.php

include 'functions.php';
session_start();


$filter = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $filter['search'] = $_GET['search'] ?? '';  // Capture the search term
    $filter['status'] = $_GET['status'] ?? '';
    $filter['vendor_id'] = $_GET['vendor_id'] ?? '';
    $filter['date_range'] = [
        'start' => $_GET['start_date'] ?? '',
        'end' => $_GET['end_date'] ?? ''
    ];
}

$slas = fetchSLAs($filter);

$currentDate = new DateTime();
$totalSlas = count($slas);
$totalNearingExpiration = 0;
$totalExpired = 0;

// Update SLA statuses and count nearing expiration/expired
$updatedSlas = array_map(function ($sla) use ($currentDate, &$totalNearingExpiration, &$totalExpired) {
    $endDate = new DateTime($sla['contract_end_date']);
    $interval = $currentDate->diff($endDate);

    if ($interval->invert == 0 && $interval->days <= 10) {
        $sla['status'] = 'Nearing Expiration';
        $totalNearingExpiration++;
    } elseif ($interval->invert == 1) {
        $sla['status'] = 'Expired';
        $totalExpired++;
    }

    return $sla;
}, $slas);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLA Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Font Awesome for icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
       body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 14px; /* Add space around the body */
            border: 7px solid #fff; /* Add a border */
            box-sizing: border-box; /* Ensure padding doesn't affect the width */
        }

        header {
            margin: 0;
            font-size: 2rem;
            background-color: #3b82f6;
            color: #fff;
            padding: 1.5rem 0;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
            right: 1050px;
            text-align: center;
        }

        .alert-box {
            background-color: #ffdddd;
            border-left: 6px solid #f44336;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #d8000c;
        }

        .container-fluid {
            display: flex;
        }

        .sidebar {
        width: 250px;
        transition: width 0.3s ease;
        }

        .sidebar.hidden {
            width: 0;
            overflow: hidden;
        }

        main {
            transition: margin-left 0.3s ease, width 0.3s ease;
            margin-left: 250px;
        }

        main.expanded {
            margin-left: 0;
            width: 100%;
        }


        /* Hidden sidebar styles */
        .sidebar.hidden {
            width: 0;
        }


        .summary-section {
            display: flex;
            justify-content: space-around;
            margin-bottom: 2rem;
            gap: 1rem;
        }

        .status-card {
            background: linear-gradient(135deg, #e0f7fa, #b3e5fc);
            color: #004d61;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            flex: 1;
            margin: 0.5rem;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .status-card:hover {
            transform: translateY(-5px);
        }

        .status-card h3 {
            margin-bottom: 0.5rem;
        }

        /* Chart and Filter Section */
        .filter-and-chart {
            display: flex;
            justify-content: space-between;
            gap: 2rem;
            margin-bottom: 2rem;
            align-items: stretch; /* Ensure equal height for children */
        }

        .filter-form, .chart-container {
            flex: 1; /* Equal width for both */
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            display: flex; /* Allow child content to stretch */
            flex-direction: column; /* Ensure vertical layout for content */
            justify-content: space-between; /* Ensure full height usage */
            min-height: 400px; /* Set a minimum height */
        }


        .chart-container canvas {
            display: block;
            margin: auto;
            width: 100% !important;
            max-height: 500px;
        }


        .filter-form label {
            font-weight: bold;
        }

        .filter-form select, .filter-form button {
            display: block;
            width: 100%;
            margin: 0.5rem 0;
            padding: 0.5rem;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .filter-form input{
            display: block;
            width: 100%;
            margin: 0.5rem 0;
            padding: 0.5rem 0;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;

        }

        .filter-form button {
            background: #3b82f6;
            color: #fff;
            cursor: pointer;
         
        }

        .filter-form button:hover {
            background: #2563eb;
        }

        .delete-button{
            background-color: #d8000c;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s ease;
            display: block;
            width: 20%;
            margin: 0.5rem 0;
            padding: 0.5rem;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .delete-button:hover{
            background: rgba(0, 0, 0, 0.1);
        }

        /* SLA Table */
        .sla-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        .sla-table th,
        .sla-table td {
            border: 1px solid #ddd;
            padding: 0.75rem;
            text-align: left;
        }

        .sla-table th {
            background-color: #3b82f6;
            color: #fff;
        }

        /* Table Buttons */
        /* Light Green Buttons for Vendor and Documents */
        .vendor-button,
        .document-button {
            display: inline-block;
            text-decoration: none;
            background-color: #bbf7d0;
            color: #065f46;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border-radius: 5px;
            border: 1px solid #10b981;
            text-align: center;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .vendor-button:hover,
        .document-button:hover {
            background-color: #86efac;
            transform: scale(1.05);
        }

        /* Light Yellow Button for Edit */
        .edit-button {
            display: inline-block;
            text-decoration: none;
            background-color: #fef9c3;
            color: #854d0e;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border-radius: 5px;
            border: 1px solid #facc15;
            text-align: center;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .edit-button:hover {
            background-color: #fde047;
            transform: scale(1.05);
        }

        /* Row Hover Effect */
        .sla-table tr:hover {
            background-color: #f5f5f5;
        }

        /* Back to Top */
        #back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 20px;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            display: none;
        }

        .sidebar-toggle-icon {
            margin-right: 10px; 
        }
        .main-content {
            flex: 1;
            margin-left: 250px; /* Adjust based on sidebar width */
        }

        .container-fluid.fullscreen .main-content {
            margin-left: 0;
        }

    </style>
</head>


<div class="container-fluid">
    <?php include 'connection.php';
        include 'sidebar.php';
        include 'db.php'; ?>
</div>
    <main>
    <button id="sidebar-toggle" class="btn btn-primary mb-3" >
                <i class="fas fa-chevron-left sidebar-toggle-icon"></i> 
            </button>
    <header><i class="fas fa-file-contract"></i> SLA Dashboard</header>
            
     <!-- Alert for nearing expiration and expired SLAs -->
     <?php if (!empty($updatedSlas)): ?>
        <div class="alert-box">
            <strong>Warning:</strong> The following SLAs are approaching their end dates:
            <ul>
                <?php foreach ($slas as $sla): ?>
                    <?php if ($sla['status'] == 'Nearing Expiration' || $sla['status'] == 'Expired'): ?>
                        <li>
                            <?php echo htmlspecialchars($sla['sla_name']); ?> (Status: 
                            <?php echo htmlspecialchars($sla['status']); ?>, Expires on: 
                            <?php echo htmlspecialchars($sla['contract_end_date']); ?>)
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

        <!-- Summary Section -->
         <div class="summary-section">
            <div class="status-card">
                <i class="fas fa-file-alt"></i>
                <h3>Total SLAs</h3>
                <p><?php echo $totalSlas; ?></p>
            </div>
            <div class="status-card">
                <i class="fas fa-exclamation-circle"></i>
                <h3>Nearing Expiration</h3>
                <p><?php echo $totalNearingExpiration; ?></p>
            </div>
            <div class="status-card">
                <i class="fas fa-calendar-times"></i>
                <h3>Expired</h3>
                <p><?php echo $totalExpired; ?></p>
            </div>
        </div>

            <!-- Filter and Chart Section -->
            <div class="filter-and-chart">
            <!-- Filter Section -->
            <div class="filter-form">
            <h2> Filter SLAs</h2>
                <form action="sla.php" method="get">
                    <label for="search"><i class="fas fa-search"></i> Search</label>
                    <input type="text" name="search" id="search" placeholder=" Search by Reference No or SLA Name">
                    
                    <label for="status"><i class="fas fa-filter"></i> Status</label>
                    <select name="status" id="status">
                        <option value="">All</option>
                        <option value="active">Active</option>
                        <option value="Expired">Expired</option>
                        <option value="Nearing Expiration">Nearing Expiration</option>
                    </select>
                    
                    <label for="start_date"><i class="fas fa-calendar-alt"></i> Start Date</label>
                    <input type="date" name="start_date" id="start_date">

                    <label for="end_date"><i class="fas fa-calendar-alt"></i> End Date</label>
                    <input type="date" name="end_date" id="end_date">
                    
                    <button type="submit"><i class="fas fa-filter"></i> Filter</button>
                </form>
                <form action="add_sla.php" method="get" style="margin-top: 1rem;">
                    <button type="submit" class="button"> Add SLA</button>
                </form>
                <form action="add_vendor.php" method="get" style="margin-top: 1rem;">
                    <button type="submit" class="button"> Add Vendor</button>
                </form>
                <form action="setsla_reminder.php" method="get" style="margin-top: 1rem;">
                    <button type="submit" class="button"> Set Reminder </button>
                </form>
            </div>

           <!-- Chart Section -->
           <div class="chart-container">
                <h2><i class="fas fa-chart-pie"></i> SLA Distribution</h2>
                <canvas id="slaChart"></canvas>
            </div>
        </div>
       <!-- SLA Table -->
       <section class="sla-table">
    <h2>SLAs</h2>
    <form action="delete_sla.php" method="post" class="table-form" onsubmit="return confirm('Are you sure you want to delete the selected SLAs?');">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>NO</th>
                    <th>Reference Number</th>
                    <th>SLA Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Vendor</th>
                    <th>Status</th>
                    <th>Documents</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php $counter = 1;?>
                <?php foreach ($updatedSlas as $sla):?>
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="<?php echo $sla['id']; ?>"></td>
                        <td><?php echo $counter++; ?></td>
                        <td><?php echo htmlspecialchars($sla['reference_number']); ?></td>
                        <td><?php echo htmlspecialchars($sla['sla_name']); ?></td>
                        <td><?php echo htmlspecialchars($sla['contract_start_date']); ?></td>
                        <td><?php echo htmlspecialchars($sla['contract_end_date']); ?></td>
                        <td>
                            <a href="vendor_details.php?vendor_id=<?php echo htmlspecialchars($sla['vendor_id']); ?>" class="vendor-button"><?php echo htmlspecialchars($sla['vendor_id']); ?></a>
                        </td>
                        <td><?php echo htmlspecialchars($sla['status']); ?></td>
                        <td>
                            <a href="support_documents.php?sla_id=<?php echo $sla['id']; ?>" class="document-button"> View Documents</a>
                        </td>
                        <td>
                            <a href="edit_sla.php?id=<?php echo $sla['id']; ?>" class="edit-button">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="delete-button">Delete Selected</button>
        <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </form>
</section>

    </main>
  
    <button id="back-to-top"><i class="fas fa-arrow-up"></i></button>

            <script>
                // Chart.js for SLA Distribution
                const ctx = document.getElementById('slaChart').getContext('2d');
                const slaChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Active', 'Nearing Expiration', 'Expired'],
                        datasets: [{
                            label: 'SLA Status Distribution',
                            data: [
                                <?php echo $totalSlas - $totalNearingExpiration - $totalExpired; ?>, 
                                <?php echo $totalNearingExpiration; ?>, 
                                <?php echo $totalExpired; ?>
                            ],
                            backgroundColor: ['#22c55e', '#facc15', '#ef4444'],
                            borderWidth: 1
                        }]
                            },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    scales: {
                                  y: { beginAtZero: true }
                             }
                    }
            });


        
        // Back to Top Button
        const backToTop = document.getElementById('back-to-top');
        window.addEventListener('scroll', () => {
            backToTop.style.display = window.scrollY > 300 ? 'block' : 'none';
        });

        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

    </script>

    
    <script>
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('main');
    const toggleButton = document.getElementById('sidebar-toggle');

    // Function to update positions and states
    function updateLayout() {
        const isSidebarHidden = sidebar.classList.contains('hidden');
        if (isSidebarHidden) {
            toggleButton.style.left = "10px"; // Position near the left edge
            mainContent.style.marginLeft = "0";
        } else {
            toggleButton.style.left = "250px"; // Align with the sidebar width
            mainContent.style.marginLeft = "250px";
        }
    }

    // Event listener for the toggle button
    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
        mainContent.classList.toggle('expanded');
        updateLayout();
                // Update the icon
                const icon = toggleButton.querySelector('.sidebar-toggle-icon');
        if (sidebar.classList.contains('hidden')) {
            icon.classList.remove('fa-chevron-left');
            icon.classList.add('fa-chevron-right');
        } else {
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-left');
        }
    });

    // Initial layout update
    updateLayout();

    // Select-all functionality for checkboxes
    const selectAllCheckbox = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('input[name="ids[]"]');

    selectAllCheckbox.addEventListener('change', (e) => {
        checkboxes.forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
    });
    </script>
</body>
</html>

