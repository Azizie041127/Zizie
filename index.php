<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Workspace - Dashboard</title>
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
            height: 100vh;
            display: flex;
        }
        .container-fluid {
            display: flex;
            flex: 1;
            padding: 0;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            overflow-y: auto;
            flex: 1;
            transition: margin-left 0.3s; /* Smooth transition for sidebar toggle */
        }
        .collapsed .main-content {
            margin-left: 0; /* Adjust based on collapsed sidebar width */
        }
        .border-bottom {
            border-color: #b5d1c7;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-header {
            background: linear-gradient(135deg, #e0f7fa, #b3e5fc);
            color: #808080;
        }
        .content-container {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .content-row {
            display: flex;
            flex-wrap: wrap;
        }
        .content-column {
            flex: 1;
            min-width: 350px;
            max-height: 400px;
            overflow-y: auto;
            margin: 10px;
        }
        .sidebar-toggle-icon {
            margin-right: 10px; 
        }

        
    </style>
</head>
<body>
    <div class="container-fluid">
        <?php include 'sidebar.php'; ?>

        <main role="main" class="main-content">
            <button id="sidebar-toggle" class="btn btn-primary">
                <i class="fas fa-chevron-left sidebar-toggle-icon"></i> 
            </button>
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">WELCOME TO IT WORKSPACE</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">DASHBOARD</li>
                    </ol>
                </nav>
            </div>
            <div class="container-fluid">
                <div class="content-row">
                    <div class="col-md-4 content-column">
                        <div class="card">
                            <div class="card-header">ATTENDANCE MANAGEMENT</div>
                            <div class="card-body">
                                <p>Manage attendee records, add new attendees, and generate attendance reports.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 content-column">
                        <div class="card">
                            <div class="card-header">LICENSE APPLICATION MANAGEMENT</div>
                            <div class="card-body">
                                <p>View, approve, or reject license applications.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 content-column">
                        <div class="card">
                            <div class="card-header">USER PROFILE</div>
                            <div class="card-body">
                                <p>View and update your user profile.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-row">
                    <div class="col-md-6 content-column">
                        <div class="card">
                            <div class="card-header">RECENT ACTIVITIES</div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li>10:30 AM - John Doe checked in for the meeting.</li>
                                    <li>09:15 AM - License application approved for XYZ Company.</li>
                                    <li>08:45 AM - New attendee added by Admin.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 content-column">
                        <div class="card">
                            <div class="card-header">KEY METRICS</div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li>Total Attendees: 120</li>
                                    <li>Pending License Applications: 5</li>
                                    <li>Approved License Applications: 45</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
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