<?php
// add_vendor.php

include 'db.php'; // Ensure database connection is included
include_once 'functions.php'; // Include functions
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendor_id = isset($_POST['vendor_id']) ? $_POST['vendor_id'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $contact_info = isset($_POST['contact_info']) ? $_POST['contact_info'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    if ($vendor_id && $name && $contact_info && $address) {
        try {
            add_vendor($vendor_id, $name, $contact_info, $address);
            header("Location: index.php");
            exit();
        } catch (Exception $e) {
            echo "Error: Unable to add vendor. " . $e->getMessage();
        }
    } else {
        echo "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Vendor</title>
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
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s, box-shadow 0.3s;
        }

        input:focus {
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
        <h1>Vendor Management</h1>
        <p>Manage your vendor records efficiently</p>
    </div>

    <div class="container">

        <h2>Add New Vendor</h2>
        <form action="add_vendor.php" method="POST">
            <div class="form-group">
                <label for="vendor_id">Vendor ID:</label>
                <input type="text" id="vendor_id" name="vendor_id" required>
            </div>

            <div class="form-group">
                <label for="name">Vendor Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="contact_info">Contact:</label>
                <input type="text" id="contact_info" name="contact_info" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>

            <button type="submit"><i class="fas fa-plus"></i> Add Vendor</button>
        </form>
        
    </div>

    <!-- Back to Dashboard Button -->
    <a href="sla.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</body>
</html>
