<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Check if any IDs are passed via POST
if (isset($_POST['ids']) && is_array($_POST['ids'])) {
    $ids = $_POST['ids'];

    // Convert the array of IDs into a comma-separated string for the query
    $placeholders = implode(',', array_map('intval', $ids)); // Ensure IDs are integers

    // Prepare the delete query
    $db = getDatabaseConnection();
    $sql = "DELETE FROM SLA WHERE id IN ($placeholders)";
    $stmt = $db->prepare($sql);

    if ($stmt->execute()) {
        // Redirect back to the dashboard with a success message
        header("Location: sla.php?message=Selected SLAs deleted successfully");
        exit();
    } else {
        // Redirect back with an error message
        header("Location: sla.php?error=Failed to delete selected SLAs");
        exit();
    }
} else {
    // Redirect back with an error message
    header("Location: sla.php?error=No SLAs selected for deletion");
    exit();
}
?>
