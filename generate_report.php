<?php
session_start();
include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
            margin: 0;
            position: relative;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            max-width: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 250px;
            height: auto;
            margin: 0 auto;
            display: block;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .report {
            margin-top: 20px;
            flex: 1;
            position: relative;
        }

        .event-details {
            margin-bottom: 20px;
            text-align: left;
        }

        .event-details p {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .event-details .label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }

        table th {
            background-color: #f2f2f2;
        }

        .verify-container {
            margin-top: 20px;
            position: relative;
        }

        .verify {
            background-color: white;
            padding: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: left;
        }

        .verify p {
            margin-bottom: 0;
        }

        .verify .signature {
            text-decoration: underline;
            display: inline-block;
            margin-bottom: 10px;
        }

       @media print {
           @page{
               
           }
            @page {
                @bottom-right{
                    content:"Page " counter(page);
                }
            }
        
            body {
                margin: 0;
                padding: 0;
                font-size: 12px;
                min-height: auto;
            }
        
            .container {
                padding: 10mm;
                box-shadow: none;
            }
        
            .header {
                margin-bottom: 10mm;
            }
        
            .header img {
                max-width: 250px;
            }
        
            table {
                width: 100%;
                page-break-inside: auto;
                border-collapse: collapse;
            }
        
            table th {
                background-color: #d3d3d3; /* Grey color for table headers in PDF */
                color: #000; /* Ensures text in header is visible */
            }
        
            table th, table td {
                border: 1px solid #ddd;
                padding: 6px;
                font-size: 10px;
            }
        
            .verify-container {
                page-break-before: auto;
                text-align: right; /* Ensures the verify section is on the right in print */
            }
        
            .btn-container {
                display: none;
            }
        
            .page-number:before {
                content: "Page " counter(page) " of " counter(pages);
                position: fixed;
                bottom: 0x;
                right: 0;
                font-size: 10px;
                color: black;
            }
            .page-break{
                page-break-before:always;
            }
           
        }

        .btn-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="img/dbkulogowithname.png" alt="Logo">
        </div>

        <h2>ATTENDANCE REPORT</h2>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['start_date'])) {
                $start_date = $_POST['start_date'];
                $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
                $event_name = $_POST['event_name'];
                $event_time = $_POST['event_time'];
                $location = $_POST['event_location'];

                // Fetch attendance records based on date range from the database
                if ($end_date) {
                    $stmt = $conn->prepare("SELECT * FROM attendance WHERE DATE(datetime) BETWEEN ? AND ?");
                    $stmt->bind_param("ss", $start_date, $end_date);
                } else {
                    $stmt = $conn->prepare("SELECT * FROM attendance WHERE DATE(datetime) = ?");
                    $stmt->bind_param("s", $start_date);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo '<div class="report">';
                    echo '<div class="event-details">';
                    echo '<p><span class="label">EVENT :</span> ' . htmlspecialchars($event_name) . '</p>';
                    echo '<p><span class="label">START DATE :</span> ' . htmlspecialchars(date('d-m-Y', strtotime($start_date))) . '</p>';
                    if ($end_date) echo '<p><span class="label">END DATE :</span> ' . htmlspecialchars(date('d-m-Y', strtotime($end_date))) . '</p>';
                    if ($event_time) echo '<p><span class="label">TIME :</span> ' . htmlspecialchars($event_time) . '</p>';
                    echo '<p><span class="label">LOCATION :</span> ' . htmlspecialchars($location) . '</p>';
                    echo '</div>';

                    echo '<table class="table table-striped">';
                    echo '<thead><tr><th>#</th><th>NAME</th><th>EMAIL</th><th>COMPANY/AGENCY<br>DIVISION/DEPARTMENT</th><th>MEETING</th><th>DATE</th></tr></thead>';
                    echo '<tbody>';

                    $counter = 1; // Initialize counter for numbering
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>'; 
                        echo '<td>' . htmlspecialchars($counter++) . '</td>'; // Numbering before name
                        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['company']) . '<br>';
                        echo '<td>' . htmlspecialchars($row['meeting']) . '</td>';
                        echo '<td>' . htmlspecialchars(date('d-m-Y H:i:s', strtotime($row['datetime']))) . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                    echo '</div>';
                } else {
                    echo '<p>No attendance records found for the selected date range.</p>';
                }
                
                $stmt->close();
            }
        }
        $conn->close();
        ?>



        <div class="verify-container">
            <div class="verify">
                
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4"></div>
                    <div class="col-4">
                        <p>Verified by,</p>
                        <br><br>
                        <p class="signature">___________________________________</p>
                        <p><center>DIVISION HEAD ICT</center></p>
                        <p>This copy is certified true of the original document</p>
                        
                    </div>
                    
                </div>
                
            </div>
           
        </div>
    </div>
    <div class="btn-container">
        <button onclick="history.back()" class="btn btn-secondary">Go Back</button>
        <button id="print" class="btn btn-primary">Print</button>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function addLastPageClass() {
                const container = document.querySelector('.container');
                const verifyContainer = document.querySelector('.verify-container');
                const report = document.querySelector('.report');
                const reportHeight = report.offsetHeight;
                const containerHeight = container.offsetHeight;

                // Check if the verify section fits in the remaining space on the page
                if (containerHeight - reportHeight - verifyContainer.offsetHeight < 0) {
                    verifyContainer.classList.add('page-break');
                }
            }

            function printPage() {
                addLastPageClass();
                window.print();
            }

            document.getElementById('print').addEventListener('click', printPage);
        });
    </script>

</body>
</html>

