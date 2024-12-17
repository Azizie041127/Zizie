<?php
session_start();
include 'connection.php'; 

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendReminderEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'irdinafikriyah07@gmail.com';
        $mail->Password = 'uful qhgi vqcq bmzm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        $mail->setFrom('irdinafikriyah07@gmail.com', 'It Workspace');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

$itStaffEmails = ['shinsooah17@gmail.com'];
$reminderCount = 0;

$today = date('Y-m-d');
$expiryThreshold = date('Y-m-d', strtotime($today . ' + 30 days'));
$sql = "SELECT * FROM licenses WHERE expiry_date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $today, $expiryThreshold);
$stmt->execute();
$result = $stmt->get_result();

$emails = [];
$messages = [];

while ($row = $result->fetch_assoc()) {
    $licenseName = $row['software_name'];
    $expiryDate = date('d-m-y', strtotime($row['expiry_date']));
    $contactEmail = $row['contact_email'];

    $subject = "Reminder: License for $licenseName is expiring";
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { padding: 20px; }
            .header { font-size: 18px; font-weight: bold; margin-bottom: 20px; }
            .content { font-size: 14px; line-height: 1.6; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>License Expiry Reminder</div>
            <div class='content'>
                <p>Dear IT Team,</p>
                <p>This is a formal reminder that the license for <strong>$licenseName</strong> is set to expire on <strong>$expiryDate</strong>.</p>
                <p>Kindly take the necessary actions to renew the license to ensure uninterrupted service and compliance.</p>
                <p>Thank you for your attention to this matter.</p>
                <p>Best regards,</p>
                <p>IT Workspace</p>
            </div>
        </div>
    </body>
    </html>";

    // Add to batch
    foreach ($itStaffEmails as $email) {
        $emails[] = $email;
        $messages[] = [
            'subject' => $subject,
            'message' => $message
        ];
    }

    // Add vendor email
    $emails[] = $contactEmail;
    $messages[] = [
        'subject' => $subject,
        'message' => $message
    ];
}

// Send emails in batch
foreach ($emails as $key => $email) {
    if (sendReminderEmail($email, $messages[$key]['subject'], $messages[$key]['message'])) {
        $reminderCount++;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reminder</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h2 class="mt-5">Generate License Expiry Reminders</h2>

        <div class="alert alert-info mt-3">
            <?php if ($reminderCount > 0): ?>
                <p>Successfully sent <?= $reminderCount ?> reminder(s) for licenses expiring within the next 30 days.</p>
            <?php else: ?>
                <p>No licenses are expiring within the next 30 days.</p>
            <?php endif; ?>
        </div>
        
        <a href="license.php" class="btn btn-primary mt-3">Back to License Management</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
