<?php
// vendor_details.php - Display vendor details

include 'db.php';
include_once 'functions.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$vendor_id = isset($_GET['vendor_id']) ? $_GET['vendor_id'] : null;
$vendor = null;

if ($vendor_id) {
    $vendor = fetchVendorDetails($vendor_id);
}

if (!$vendor) {
    echo "Vendor not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, #89f7fe, #66a6ff); /* Smooth gradient */
    background-attachment: fixed; /* Ensure the gradient is fixed during scrolling */
    color: #333;
    min-height: 100vh; /* Ensure the body spans the full viewport height */
    padding: 20px;
}

.container {
    max-width: 800px;
    margin: auto;
    background: #fff;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    overflow: hidden; /* Prevent overflow that could cause lines in the background */
}


        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            font-size: 2em;
            margin-bottom: 10px;
            color: #4a4a4a;
        }

        .header p {
            color: #6c757d;
        }

        .details {
            margin-top: 20px;
            font-size: 1.1em;
        }

        .details p {
            margin-bottom: 10px;
        }

        .details strong {
            font-weight: bold;
            color: #007bff;
        }

        .back-btn {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 15px;
            background: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: background 0.3s, transform 0.2s;
        }

        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .tooltip {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%; /* Position above the element */
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        @media (max-width: 768px) {
            .details p {
                font-size: 1em;
            }

            .back-btn {
                font-size: 14px;
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Vendor Details</h2>
            <p class="tooltip">Hover for more info
                <span class="tooltiptext">Details of the selected vendor.</span>
            </p>
        </div>

        <div class="details">
            <p><strong>Vendor ID:</strong> <?php echo htmlspecialchars($vendor['vendor_id']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($vendor['name']); ?></p>
            <p><strong>Contact Info:</strong> <?php echo htmlspecialchars($vendor['contact_info']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($vendor['address']); ?></p>
        </div>

        <a href="sla.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>
</html>

