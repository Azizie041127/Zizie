<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
        @font-face {
            font-family: 'AareaKilometer';
            src: url('css/AareaKilometer-Regular.woff2') format('woff2'),
                 url('css/AareaKilometer-Regular.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }
        body {
            background: #f5f7fa;
            font-family: 'AareaKilometer', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 100%;
            width: 100%;
            max-width: 500px;
            animation: fadeIn 1.5s;
            margin: 20px;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .form-group label {
            font-weight: bold;
            text-transform: uppercase;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 1.1em;
        }
        .form-control:focus {
            border-color: #6c757d;
            box-shadow: 0 0 5px rgba(108, 117, 125, 0.5);
        }
        .btn-primary {
            background: #6c757d;
            border: none;
            border-radius: 50px;
            font-size: 1.2em;
            padding: 10px 20px;
            transition: background 0.3s, transform 0.3s;
        }
        .btn-primary:hover {
            background: #5a6268;
            transform: scale(1.05);
        }
        .text-center {
            text-align: center;
        }
        h1, h2, h3 {
            color: #6c757d;
            text-align: center;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 1.2em;
            margin-bottom: 5px;
            line-height: 1.2;
            font-weight: bold;
        }
        .logo {
            display: block;
            margin: 0 auto 10px auto;
            max-width: 85%;
            height: auto;
        }

        @media (max-width: 576px) {
            .container {
                padding: 15px;
                margin: 10px;
            }
            .form-control {
                font-size: 1em;
            }
            .btn-primary {
                font-size: 1em;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="img/dbkulogowithname.png" alt="Logo" class="logo">
        <br><br>
        <h2>Attendance Form</h2>
        <form id="attendanceForm" method="POST" action="submitForm.php">
            <div class="form-group">
                <label for="name">NAME:</label>
                <input type="text" class="form-control" id="name" name="name" required oninput="this.value = this.value.toUpperCase()">
            </div>
            <div class="form-group">
                <label for="email">EMAIL:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="company">COMPANY/AGENCY/DIVISION/DEPARTMENT:</label>
                <input type="text" class="form-control" id="company" name="company" required oninput="this.value = this.value.toUpperCase()">
            </div>
            <div class="form-group">
                <label for="meeting">MEETING NAME:</label>
                <input type="text" class="form-control" id="meeting" name="meeting" required oninput="this.value = this.value.toUpperCase()">
            </div>
            <div class="form-group">
                <label for="date">DATE:</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
        </form>

        <div class="text-center" style="margin-top: 20px;">
            <button id="generateQrButton" class="btn btn-secondary">Share QR Code</button>
        </div>
    </div>

    <script>
        document.getElementById('generateQrButton').addEventListener('click', function() {
            var formActionUrl = document.getElementById('attendanceForm').action;
            var fullUrl = window.location.protocol + '//' + window.location.host + '/' + formActionUrl;
            window.location.href = 'display_qr.php?url=' + encodeURIComponent(fullUrl);
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
