<?php
include 'connection.php'; 

if (isset($_GET['license_id'])) {
    $licenseId = $_GET['license_id'];

    $sql = "UPDATE licenses SET reminder_status = 'stopped' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $licenseId);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "Reminder successfully stopped.";
    } else {
        echo "Failed to stop the reminder. Please try again.";
    }
    
    $stmt->close();
}
$conn->close();
?>
