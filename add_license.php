<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add License</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style>
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="content">
            <h1>ADD LICENSE</h1>
            <form action="submit_license.php" method="POST">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="software_name">LICENSE NAME:</label>
                        <input type="text" class="form-control" id="software_name" name="software_name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="version">VERSION:</label>
                        <input type="text" class="form-control" id="version" name="version" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="license_key">LICENSE KEY:</label>
                        <input type="text" class="form-control" id="license_key" name="license_key" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="license_type">LICENSE TYPE:</label>
                        <input type="text" class="form-control" id="license_type" name="license_type" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="delivery_order">DELIVERY ORDER:</label>
                        <input type="text" class="form-control" id="delivery_order" name="delivery_order" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="our_ref">OUR REF:</label>
                        <input type="text" class="form-control" id="our_ref" name="our_ref" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="quantity">QUANTITY:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="purchase_date">PURCHASE DATE:</label>
                        <input type="text" class="form-control datepicker" id="purchase_date" name="purchase_date" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="expiry_date">EXPIRY DATE:</label>
                        <input type="text" class="form-control datepicker" id="expiry_date" name="expiry_date" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="renewal_date">RENEWAL DATE:</label>
                        <input type="text" class="form-control datepicker" id="renewal_date" name="renewal_date">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="validity">VALIDITY:</label>
                        <input type="text" class="form-control" id="validity" name="validity">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="vendor">VENDOR:</label>
                        <input type="text" class="form-control" id="vendor" name="vendor">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="contact_name">CONTACT NAME:</label>
                        <input type="text" class="form-control" id="contact_name" name="contact_name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="contact_email">CONTACT EMAIL:</label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="license_administrator">LICENSE ADMINISTRATOR:</label>
                        <input type="text" class="form-control" id="license_administrator" name="license_administrator">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">ADD LICENSE</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy'
            });
        });
    </script>
</body>
</html>
