<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_slas'])) {
    $selected_ids = $_POST['selected_slas'];

    // Convert selected IDs into placeholders for the query
    $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));

    // Prepare the delete query
    $db = getDatabaseConnection();
    $sql = "DELETE FROM SLA WHERE id IN ($placeholders)";
    $stmt = $db->prepare($sql);
    $stmt->execute($selected_ids);

    // Redirect back to the dashboard with a success message
    header("Location: sla.php?message=Selected SLAs deleted successfully");
    exit();
} else {
    // Redirect back with an error message
    header("Location: sla.php?error=No SLAs selected for deletion");
    exit();
}
?>
