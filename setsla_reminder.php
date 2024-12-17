<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $reminder_text = $_POST['reminder_text'];
    $reminder_date = $_POST['reminder_date'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (empty($reminder_text) || empty($reminder_date)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO reminders (email, reminder_text, reminder_date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $reminder_text, $reminder_date);

        if ($stmt->execute()) {
            $success = "Reminder successfully set!";
        } else {
            $error = "Error setting reminder. Please try again.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set SLA Reminder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            color: #333;
        }

        .header {
            text-align: center;
            padding: 20px;
            color: #fff;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.2em;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .back-btn {
            display: inline-block;
            margin: 20px;
            padding: 10px 15px;
            background: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: background 0.3s, transform 0.2s;
        }

        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4a4a4a;
            font-weight: bold;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="datetime-local"],
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s, box-shadow 0.3s;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        input:focus, textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        button {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #007bff;
            color: #fff;
            padding: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        button i {
            margin-right: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 20px;
            }

            .header h1 {
                font-size: 2em;
            }

            .header p {
                font-size: 1em;
            }

            button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SLA Reminder</h1>
        <p>Set a reminder for your SLA tasks efficiently</p>
    </div>

    <div class="container">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <h2>Set Reminder</h2>
        <form action="sendsla_reminder.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="reminder_text">Reminder Text:</label>
                <textarea id="reminder_text" name="reminder_text" required></textarea>
            </div>

            <div class="form-group">
                <label for="reminder_date">Reminder Date:</label>
                <input type="datetime-local" id="reminder_date" name="reminder_date" required>
            </div>

            <button type="submit"><i class="fas fa-plus"></i> Set Reminder</button>
        </form>
    </div>

    <!-- Back to Dashboard Button -->
    <a href="sla.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</body>
</html>
