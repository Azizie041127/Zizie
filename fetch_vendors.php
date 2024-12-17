<?php
include 'functions.php';
$vendors = fetchAllVendors(); // Assuming this function fetches all vendors from the database
echo json_encode($vendors);
?>
