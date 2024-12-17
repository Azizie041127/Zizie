<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $agency = $_POST['company'];
    $attendance_date = $_POST['date'];

    $stmt = $conn->prepare("UPDATE attendance SET name=?, email=?, company=?, date=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $email, $agency, $attendance_date, $id);
    $stmt->execute();

    header('Location: attendance.php');
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM attendance WHERE id=$id");
$attendee = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Attendee</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h2 class="mt-5">Edit Attendee</h2>
        <form action="edit_attendee.php" method="POST" class="mt-3">
            <input type="hidden" name="id" value="<?php echo $attendee['id']; ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $attendee['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $attendee['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="agency">Agency:</label>
                <input type="text" class="form-control" id="company" name="company" value="<?php echo $attendee['company']; ?>" required>
            </div>
            <div class="form-group">
                <label for="attendance_date">Date:</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo $attendee['date']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
