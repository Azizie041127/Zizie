Automated License Expiry Reminder System
This project is an automated email reminder system that notifies the IT department about expiring software licenses. The system is built using PHP and PHPMailer and runs as a cron job on Hostinger to automate the reminders.

Table of Contents
Features
Prerequisites
Installation
Configuration
Cron Job Setup
Usage


Features
Sends automated email reminders for expiring software licenses.
Configurable reminder period (e.g., 30 days before expiry).
Uses PHPMailer for sending emails via SMTP.
Runs as a cron job to automate the process.

Prerequisites
PHP 7.4 or higher
MySQL Database
Composer (for managing PHPMailer dependencies)
Web hosting with cron job support (e.g., Hostinger)

Installation
Clone the repository:

Copy code
git clone https://github.com/yourusername/license-expiry-reminder.git
cd license-expiry-reminder
Install dependencies:

Copy code
composer install

Database Setup:

Import the SQL script provided (database.sql) to set up the database schema and initial data.
Configuration
Database Connection:

Update connection.php with your database credentials.

Copy code
<?php
$servername = "your_server_name";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
PHPMailer Configuration:

Update the sendReminderEmail function in send_reminders.php with your SMTP credentials.

Copy code
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your_email@gmail.com';
$mail->Password = 'your_app_password';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

Cron Job Setup
Log in to Hostinger:

Go to the Hostinger website and log in to your account.
Navigate to the Cron Jobs Section:

Find the "Advanced" section in the menu.
Click on "Cron Jobs."
Add a New Cron Job:

Set the timing for the cron job (e.g., to run daily at midnight):
Copy code
0 0 * * *

Enter the command to run the send_reminders.php script:

Copy code
/usr/bin/php /home/yourusername/public_html/path/to/your/send_reminders.php
Replace /home/yourusername/public_html/path/to/your/send_reminders.php with the actual path to your script.

Usage
Manual Trigger: You can manually run the generate_reminder.php script by accessing it through your browser to test the email sending functionality.
Automated Trigger: The cron job will automatically run the send_reminders.php script at the specified intervals, sending email reminders as configured.