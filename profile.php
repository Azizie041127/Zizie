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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f5f4;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
        }
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            text-align: left; /* Align content to the left */
            padding: 30px; /* Add padding for spacing */
        }
        .card-title {
            color: #60a493;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .card-text {
            font-size: 1.1rem;
        }
        .profile-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
        .btn-primary {
            background-color: #60a493;
            border-color: #60a493;
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
                <h5 class="card-title">MY PROFILE</h5>
                <?php if (isset($user['icon'])): ?>
                    <img src="img/<?php echo htmlspecialchars($user['icon']); ?>" alt="Profile Icon" class="profile-icon mb-3">
                <?php endif; ?>
                <p class="card-text"><strong>NAME:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p class="card-text"><strong>EMAIL:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p class="card-text"><strong>DIVISION:</strong> <?php echo htmlspecialchars($user['division']); ?></p>
                <p class="card-text"><strong>ROLE:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
                <a href="edit_profile.php" class="btn btn-primary">EDIT PROFILE</a>
            </div>
        </div>
    </div>
</body>
</html>
