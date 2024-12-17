<?php
session_start();
include 'connection.php'; 

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

// Get the license ID from the URL
if (isset($_GET['id'])) {
    $license_id = $_GET['id'];

    // Fetch the license details from the database
    $stmt = $conn->prepare("SELECT * FROM licenses WHERE id = ?");
    $stmt->bind_param("i", $license_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $license = $result->fetch_assoc();
    
    // If the license does not exist, redirect to the dashboard
    if (!$license) {
        header('Location: dashboard.php');
        exit();
    }
    
    $stmt->close();
} else {
    header('Location: dashboard.php');
    exit();
}

// Update the license details in the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $software_name = $_POST['software_name'];
    $version = $_POST['version'];
    $license_key = $_POST['license_key'];
    $license_type = $_POST['license_type'];
    $delivery_order = $_POST['delivery_order'];
    $our_ref = $_POST['our_ref'];
    $quantity = $_POST['quantity'];
    $purchase_date = $_POST['purchase_date'];
    $expiry_date = $_POST['expiry_date'];
    $renewal_date = $_POST['renewal_date'];
    $validity = $_POST['validity'];
    $vendor = $_POST['vendor'];
    $contact_name = $_POST['contact_name'];
    $license_administrator = $_POST['license_administrator'];
    $reminder_status = $_POST['reminder_status'];

    $stmt = $conn->prepare("UPDATE licenses SET software_name = ?, version = ?, license_key = ?, license_type = ?, delivery_order = ?, our_ref = ?, quantity = ?, purchase_date = ?, expiry_date = ?, renewal_date = ?, validity = ?, vendor = ?, contact_name = ?, license_administrator = ?, reminder_status = ? WHERE id = ?");
    $stmt->bind_param("ssssssissssssssi", $software_name, $version, $license_key, $license_type, $delivery_order, $our_ref, $quantity, $purchase_date, $expiry_date, $renewal_date, $validity, $vendor, $contact_name, $license_administrator, $reminder_status, $license_id);

    if ($stmt->execute()) {
        header('Location: license.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit License</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @font-face {
            font-family: 'AareaKilometer';
            src: url('fonts/AareaKilometer-Regular.woff2') format('woff2'),
                 url('fonts/AareaKilometer-Regular.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'AareaKilometer', sans-serif;
            font-size: 1rem; /* Base font size */
            text-transform: uppercase; /* Ensure all text is in uppercase */
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
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1 class="mt-5">Edit License</h1>
        <form action="" method="POST">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="software_name">License Name:</label>
                    <input type="text" class="form-control" id="software_name" name="software_name" value="<?php echo htmlspecialchars($license['software_name']); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="version">Version:</label>
                    <input type="text" class="form-control" id="version" name="version" value="<?php echo htmlspecialchars($license['version']); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="license_key">License Key:</label>
                    <input type="text" class="form-control" id="license_key" name="license_key" value="<?php echo htmlspecialchars($license['license_key']); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="license_type">License Type:</label>
                    <input type="text" class="form-control" id="license_type" name="license_type" value="<?php echo htmlspecialchars($license['license_type']); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="delivery_order">Delivery Order:</label>
                    <input type="text" class="form-control" id="delivery_order" name="delivery_order" value="<?php echo htmlspecialchars($license['delivery_order']); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="our_ref">Our Ref:</label>
                    <input type="text" class="form-control" id="our_ref" name="our_ref" value="<?php echo htmlspecialchars($license['our_ref']); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="quantity">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($license['quantity']); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="purchase_date">Purchase Date:</label>
                    <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?php echo htmlspecialchars($license['purchase_date']); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="expiry_date">Expiry Date:</label>
                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="<?php echo htmlspecialchars($license['expiry_date']); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="renewal_date">Renewal Date:</label>
                    <input type="date" class="form-control" id="renewal_date" name="renewal_date" value="<?php echo htmlspecialchars($license['renewal_date']); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="validity">Validity:</label>
                    <input type="text" class="form-control" id="validity" name="validity" value="<?php echo htmlspecialchars($license['validity']); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="vendor">Vendor:</label>
                    <input type="text" class="form-control" id="vendor" name="vendor" value="<?php echo htmlspecialchars($license['vendor']); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="contact_name">Contact Name:</label>
                    <input type="text" class="form-control" id="contact_name" name="contact_name" value="<?php echo htmlspecialchars($license['contact_name']); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="license_administrator">License Administrator:</label>
                    <input type="text" class="form-control" id="license_administrator" name="license_administrator" value="<?php echo htmlspecialchars($license['license_administrator']); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="reminder_status">Reminder Status:</label>
                    <select class="form-control" id="reminder_status" name="reminder_status">
                        <option value="pending" <?php echo ($license['reminder_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="stopped" <?php echo ($license['reminder_status'] == 'stopped') ? 'selected' : ''; ?>>Stopped</option>
                        <option value="renewed" <?php echo ($license['reminder_status'] == 'renewed') ? 'selected' : ''; ?>>Renewed</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update License</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
