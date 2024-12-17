<?php
// add_sla.php
include 'functions.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'reference_number' => $_POST['reference_number'],
        'sla_name' => $_POST['sla_name'], // Capture SLA Name
        'contract_start_date' => $_POST['contract_start_date'],
        'contract_end_date' => $_POST['contract_end_date'],
        'vendor_id' => $_POST['vendor_id'],
        'status' => $_POST['status']
    ];
    $sla_id = addSLA($data); // Pass SLA data to the database function

    // Handle file uploads
    if (!empty($_FILES['documents']['name'][0])) {
        $target_dir = "uploads/";
        
        // Ensure the upload directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        foreach ($_FILES['documents']['tmp_name'] as $key => $tmp_name) {
            if (!empty($tmp_name)) {
                $file_name = basename($_FILES['documents']['name'][$key]);
                $file_path = $target_dir . $file_name;

                // Check for successful file upload and save to DB
                if (move_uploaded_file($tmp_name, $file_path)) {
                    uploadDocument($sla_id, $file_name, $file_path);
                } else {
                    // Handle file upload errors (optional)
                    echo "Error uploading file: $file_name";
                }
            }
        }
    }

    header("Location: sla.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New SLA</title>
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
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            color: #333;
        }

        .header {
            text-align: center;
            padding: 20px;
            color: #fff;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.2em;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .back-btn {
            display: inline-block;
            margin: 20px;
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

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4a4a4a;
            font-weight: bold;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s, box-shadow 0.3s;
        }

        input:focus,
        select:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        button {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #007bff;
            color: #fff;
            padding: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        button i {
            margin-right: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-of-type {
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 20px;
            }

            .header h1 {
                font-size: 2em;
            }

            .header p {
                font-size: 1em;
            }

            button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Service Level Agreement</h1>
        <p>Manage your SLA records with ease</p>
    </div>

    <div class="container">

        <h2>Add New SLA</h2>
        <form method="POST" action="add_sla.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="reference_number">Reference Number:</label>
                <input type="text" id="reference_number" name="reference_number" required>
            </div>

            <div class="form-group">
                <label for="sla_name">SLA Name:</label>
                <input type="text" id="sla_name" name="sla_name" required>
            </div>

            <div class="form-group">
                <label for="contract_start_date">Contract Start Date:</label>
                <input type="date" id="contract_start_date" name="contract_start_date" required>
            </div>

            <div class="form-group">
                <label for="contract_end_date">Contract End Date:</label>
                <input type="date" id="contract_end_date" name="contract_end_date" required>
            </div>

            <div class="form-group">
                <label for="vendor_id">Vendor ID:</label>
                <input type="text" id="vendor_id" name="vendor_id" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status">
                    <option value="active">Active</option>
                    <option value="expired">Expired</option>
                    <option value="nearing_expiration">Nearing Expiration</option>
                </select>
            </div>

            <div class="form-group">
                <label for="documents">Upload Documents:</label>
                <input type="file" id="documents" name="documents[]" multiple>
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i> You can upload multiple files.
                </div>
            </div>

            <button type="submit"><i class="fas fa-plus"></i> Add SLA</button>
        </form>
        
    </div>
    <!-- Back to Dashboard Button -->
    <a href="sla.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</body>
</html>
