<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}

$email = $_SESSION['email'];

// Fetch user profile details from the profile table
$stmt = $conn->prepare("SELECT * FROM profile WHERE email = ?");
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("s", $email);

if (!$stmt->execute()) {
    die('Execute failed: ' . htmlspecialchars($stmt->error));
}

$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    die("User details not found for email: " . htmlspecialchars($email));
}

// Processing form submission if POST data exists
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = htmlspecialchars($_POST['name']);
    $division = htmlspecialchars($_POST['division']);

    // Update profile in the database
    $update_stmt = $conn->prepare("UPDATE profile SET name = ?, division = ? WHERE email = ?");
    if ($update_stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $update_stmt->bind_param("sss", $name, $division, $email);

    if (!$update_stmt->execute()) {
        die('Execute failed: ' . htmlspecialchars($update_stmt->error));
    }

    // Redirect to profile page after successful update
    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f5f4;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
        }
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 30px;
        }
        .card-title {
            color: #60a493;
            font-size: 1.8rem;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group label {
            font-weight: bold;
            color: #336b58;
        }
        .form-control {
            border: 1px solid #b5d1c7;
            border-radius: 5px;
            box-shadow: none;
        }
        .form-control:focus {
            border-color: #60a493;
            box-shadow: 0 0 0 0.2rem rgba(96, 164, 147, 0.25);
        }
        .btn-primary {
            background-color: #60a493;
            border-color: #60a493;
            width: 100%;
            margin-top: 20px;
        }
        .btn-primary:hover {
            background-color: #336b58;
            border-color: #336b58;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">EDIT PROFILE</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="division">Division</label>
                        <input type="text" class="form-control" id="division" name="division" value="<?php echo htmlspecialchars($user['division']); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
