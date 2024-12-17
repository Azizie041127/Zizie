<?php
include 'functions.php';

if (!isset($_GET['sla_id'])) {
    die("No SLA ID provided.");
}

$sla_id = $_GET['sla_id'];

// Fetch SLA details to get the SLA name
$sla_details = fetchSLADetails($sla_id);
if (!$sla_details) {
    die("Invalid SLA ID.");
}

$documents = fetchDocumentsBySLA($sla_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Documents</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
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
            table th, table td {
                font-size: 14px;
                padding: 10px;
            }

            .header h2 {
                font-size: 1.5em;
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
            <h2>Documents for SLA: <?php echo htmlspecialchars($sla_details['sla_name']); ?></h2>
            <p class="tooltip">Hover for more info
                <span class="tooltiptext">List of all uploaded documents related to this SLA.</span>
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Uploaded Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($documents)) : ?>
                    <?php foreach ($documents as $document) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($document['file_name']); ?></td>
                            <td><?php echo htmlspecialchars($document['upload_date']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($document['file_path']); ?>" download><i class="fas fa-download"></i> Download</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">No documents found for this SLA.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="sla.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>
</html>

