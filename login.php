<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
include 'connection.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Fetch user data from the database using a prepared statement
    $query = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        // User found, verify password
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];
        $role = $row['role'];

        if (password_verify($password, $hashed_password)) {
            // Passwords match, start session and redirect
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            // Redirect to the attendance page after successful login
            header('Location: attendance.php');
            exit();
        } else {
            // Incorrect password
            $_SESSION['error'] = 'Invalid email or password. Please try again.';
        }
    } else {
        // User not found
        $_SESSION['error'] = 'Invalid email or password. Please try again.';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICT Workspace - Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #60a493;
            border-color: #60a493;
        }

        .btn-primary:hover {
            background-color: #539f88;
            border-color: #539f88;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>ICT Workspace - Login</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger text-center">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>
</body>
</html>
