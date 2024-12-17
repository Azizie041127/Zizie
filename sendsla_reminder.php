<?php
include 'connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Fetch unsent reminders that are due
$stmt = $conn->prepare("SELECT id, email, reminder_text FROM reminders WHERE reminder_date <= NOW() AND is_sent = 0");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $email = $row['email'];
    $reminder_text = $row['reminder_text'];

    // Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com'; // SMTP username
        $mail->Password = 'your_password';         // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email content
        $mail->setFrom('no-reply@example.com', 'Reminder Service');
        $mail->addAddress($email);
        $mail->Subject = 'Your Reminder';
        $mail->Body = $reminder_text;

        $mail->send();

        // Mark reminder as sent
        $updateStmt = $conn->prepare("UPDATE reminders SET is_sent = 1 WHERE id = ?");
        $updateStmt->bind_param("i", $id);
        $updateStmt->execute();
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}

$stmt->close();
?>
