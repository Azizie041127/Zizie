<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Font Awesome for icons -->
    <style>
        @font-face {
            font-family: 'AareaKilometer';
            src: url('css/AareaKilometer-Regular.woff2') format('woff2'),
                 url('css/AareaKilometer-Regular.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'AareaKilometer', sans-serif;
            font-size: 1rem; /* Base font size */
            text-transform: uppercase; /* Ensure all text is in uppercase */
            background-color: #f0f5f4;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container-fluid {
            display: flex;
            flex: 1;
            padding: 0;
        }

        .main-content {
            margin-left: 250px; /* Adjust margin to accommodate sidebar width */
            flex: 1;
            padding: 20px;
            transition: margin-left 0.3s; /* Smooth transition for sidebar toggle */
        }

        .collapsed .main-content {
            margin-left: 0; /* Adjust based on collapsed sidebar width */
        }

        .container {
            max-width: 1500px; /* Set maximum width */
            margin: 0 auto; /* Center the container */
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .grid-item {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center align text and icon */
        }

        .grid-item i {
            font-size: 2rem; /* Adjust icon size */
            color: #60a493; /* Color to match button */
            margin-bottom: 10px; /* Space between icon and text */
        }

        .sidebar-toggle-icon {
            margin-right: 10px; 
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <?php 
        include 'connection.php';
        include 'sidebar.php'; 
        ?>

        <div class="main-content">
            <button id="sidebar-toggle" class="btn btn-primary">
                <i class="fas fa-chevron-left sidebar-toggle-icon"></i> 
            </button>
            <div class="content">
                <div class="container">
                    <h2 class="mt-5">Account Management</h2>

                    <div class="grid-container">
                        <div class="grid-item">
                            <i class="fas fa-cogs"></i> <!-- Icon for Sistem -->
                            <h4>Sistem</h4>
                            <!-- Add content specific to Sistem here -->
                            <p>Details about the Sistem section.</p>
                        </div>
                        <div class="grid-item">
                            <i class="fas fa-envelope"></i> <!-- Icon for Email -->
                            <h4>Email</h4>
                            <!-- Add content specific to Email here -->
                            <p>Details about the Email section.</p>
                        </div>
                        <div class="grid-item">
                            <i class="fas fa-network-wired"></i> <!-- Icon for Internet -->
                            <h4>Internet</h4>
                            <!-- Add content specific to Internet here -->
                            <p>Details about the Internet section.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.container-fluid').classList.toggle('collapsed');
        });
    </script>
</body>
</html>
