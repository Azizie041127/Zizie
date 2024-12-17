<?php
include 'connection.php'; 
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
        
        $mail->setFrom('irdinafikriyah07@gmail.com', 'IT Department');
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

$today = date('Y-m-d');
$expiryThreshold = date('Y-m-d', strtotime($today . ' + 30 days'));

$sql = "SELECT * FROM licenses WHERE expiry_date BETWEEN ? AND ? AND reminder_status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $today, $expiryThreshold);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $licenseName = $row['software_name'];
    $expiryDate = date('d-m-y', strtotime($row['expiry_date']));
    $licenseId = $row['id'];
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

    // Send reminder to IT staff
    foreach ($itStaffEmails as $email) {
        sendReminderEmail($email, $subject, $message);
    }

    // Send reminder to vendor
    sendReminderEmail($contactEmail, $subject, $message);
}
$stmt->close();
$conn->close();
?>
