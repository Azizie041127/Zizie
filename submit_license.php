<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

include 'connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $software_name = $_POST['software_name'];
    $version = $_POST['version'];
    $license_key = $_POST['license_key'];
    $license_type = $_POST['license_type'];
    $delivery_order = $_POST['delivery_order'];
    $our_ref = $_POST['our_ref'];
    $quantity = $_POST['quantity'];
    $purchase_date = date("Y-m-d", strtotime($_POST['purchase_date']));
    $expiry_date = date("Y-m-d", strtotime($_POST['expiry_date']));
    $renewal_date = !empty($_POST['renewal_date']) ? date("Y-m-d", strtotime($_POST['renewal_date'])) : NULL;
    $validity = $_POST['validity'];
    $vendor = $_POST['vendor'];
    $contact_name = $_POST['contact_name'];
    $license_administrator = $_POST['license_administrator'];

    // Insert data into the database
    $sql = "INSERT INTO licenses (software_name, version, license_key, license_type, delivery_order, our_ref, quantity, purchase_date, expiry_date, renewal_date, validity, vendor, contact_name, license_administrator)
            VALUES ('$software_name', '$version', '$license_key', '$license_type', '$delivery_order', '$our_ref', '$quantity', '$purchase_date', '$expiry_date', '$renewal_date', '$validity', '$vendor', '$contact_name', '$license_administrator')";

    if ($conn->query($sql) === TRUE) {
        echo "New license added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
