<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Load PHPMailer Autoload
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Function to calculate 30 days before expiry
function calculateExpiryDate($expiryDate) {
    return date('Y-m-d', strtotime($expiryDate . ' - 30 days'));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure a license ID is selected
    $license_id = $_POST['license_id'] ?? null;
    if (!$license_id) {
        die('License ID is required.');
    }

    // Handle file upload for invoice
    if ($_FILES["invoice"]["error"] == UPLOAD_ERR_OK) {
        $invoice_name = $_FILES["invoice"]["name"];
        $invoice_tmp_name = $_FILES["invoice"]["tmp_name"];
        $invoice_path = "uploads/invoices/" . $invoice_name;
        move_uploaded_file($invoice_tmp_name, $invoice_path);
    } else {
        die('Failed to upload invoice.');
    }

    // Handle file upload for renewal attachment
    if ($_FILES["renewal_attachment"]["error"] == UPLOAD_ERR_OK) {
        $renewal_name = $_FILES["renewal_attachment"]["name"];
        $renewal_tmp_name = $_FILES["renewal_attachment"]["tmp_name"];
        $renewal_path = "uploads/renewals/" . $renewal_name;
        move_uploaded_file($renewal_tmp_name, $renewal_path);
    } else {
        die('Failed to upload renewal attachment.');
    }

    // Save invoice and renewal details to database
    // Assume you have a database connection established
    $conn = new mysqli("localhost", "u801038779_it_workspace", "It_workspace123", "u801038779_workspace");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute SQL to update the license record
    $sql = "UPDATE licenses SET invoice_path = ?, renewal_attachment_path = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $invoice_path, $renewal_path, $license_id);
    $stmt->execute();
    $stmt->close();

    // Close database connection
    $conn->close();

    // Send email notification using PHPMailer
    $mail = new PHPMailer(true); // Passing `true` enables exceptions

    try {
        // Server settings
        $mail->isSMTP(); 
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true; 
        $mail->Username = 'irdinafikriyah07@gmail.com'; 
        $mail->Password = 'uful qhgi vqcq bmzm'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587; 

        // Sender and recipient settings
        $mail->setFrom('irdinafikriyah07@gmail.com', 'ICT Workspace');
        $mail->addAddress('shinsooah17@gmail.com'); // Add a recipient email address

        // Email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'New Invoice and Renewal Submission for License ID: ' . $license_id;
        $mail->Body    = '
            <p>Dear Head of Division,</p>
            <p>A new invoice and renewal attachment have been submitted for License ID: ' . $license_id . '.</p>
            <p>Please review and approve as necessary.</p>
            <p>Invoice File: <a href="' . $invoice_path . '">' . $invoice_name . '</a></p>
            <p>Renewal Attachment File: <a href="' . $renewal_path . '">' . $renewal_name . '</a></p>
            <p>Regards,<br>License Administrator</p>
        ';

        $mail->send();
        echo '<div class="alert alert-success" role="alert">Notification email sent to Head of Division.</div>';
    } catch (Exception $e) {
        echo '<div class="alert alert-danger" role="alert">Failed to send notification email. Error: ' . $mail->ErrorInfo . '</div>';
    }

    // Redirect to license.php or any appropriate page after submission
    header("Location: license.php");
    exit;
}

// Fetch licenses that are within 30 days of expiry
$currentDate = date('Y-m-d');
$thirtyDaysBefore = calculateExpiryDate($currentDate);

$conn = new mysqli("localhost", "u801038779_it_workspace", "It_workspace123", "u801038779_workspace");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, software_name FROM licenses WHERE expiry_date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $thirtyDaysBefore, $currentDate);
$stmt->execute();
$result = $stmt->get_result();

$licenses = [];
while ($row = $result->fetch_assoc()) {
    $licenses[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Invoice and Renewal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f5f4;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #60a493;
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            margin-top: 20px;
        }
        .form-group label {
            font-weight: bold;
            color: #555;
        }
        .btn-primary {
            background-color: #60a493;
            border-color: #60a493;
        }
        .btn-primary:hover {
            background-color: #4c8c77;
            border-color: #4c8c77;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit Invoice and Renewal</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="license_id">Select License ID:</label>
                <select class="form-control" id="license_id" name="license_id" required>
                    <option value="">Select License ID</option>
                    <?php foreach ($licenses as $license): ?>
                        <option value="<?php echo $license['id']; ?>"><?php echo $license['software_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="invoice">Invoice:</label>
                <input type="file" class="form-control-file" id="invoice" name="invoice" accept=".pdf,.doc,.docx" required>
            </div>
            <div class="form-group">
                <label for="renewal_attachment">Renewal Attachment:</label>
                <input type="file" class="form-control-file" id="renewal_attachment" name="renewal_attachment" accept=".pdf,.doc,.docx" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
