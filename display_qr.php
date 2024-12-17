<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 450px;
            animation: fadeIn 1.5s;
            border: 1px solid #ddd;
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
        .logo {
            width: 100%;
            max-width: 300px; /* Adjusted max-width */
            height: auto;
            margin: 0 auto 20px auto;
        }
        .qr-code {
            margin: 20px auto;
            width: 100%;
            max-width: 260px;
            height: auto;
        }
        h1 {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #333333;
            font-weight: bold;
        }
        .btn {
            background-color: #6c757d;
            color: #ffffff;
            border-radius: 50px;
            padding: 8px 15px; /* Reduced padding */
            font-size: 0.9em; /* Reduced font size */
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
            display: inline-block;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #5a6268;
            transform: scale(1.05);
        }
        .hidden {
            display: none;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="img/dbkulogowithname.png" alt="Logo" class="logo">
        <h1>Attendance Form</h1>
        <img src="temp_qr.png" alt="QR Code" class="qr-code">
        <div>
            <a href="form.php" id="urlButton" class="btn">Go to URL</a> <!-- Changed href to form.php -->
            <button id="downloadContent" class="btn">Print</button>
        </div>
    </div>

    <script>
        document.getElementById('downloadContent').addEventListener('click', function() {
            const downloadButton = document.getElementById('downloadContent');
            const urlButton = document.getElementById('urlButton');
            
            downloadButton.classList.add('hidden'); // Hide the download button
            urlButton.classList.add('hidden'); // Hide the URL button

            html2canvas(document.querySelector('.container'), {
                useCORS: true,
                scale: 3 // Increased scale for better resolution
            }).then(canvas => {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                
                // Calculate dimensions for the PDF
                const imgWidth = 190; // A4 width in mm
                const pageHeight = 295; // A4 height in mm
                const imgHeight = canvas.height * imgWidth / canvas.width;
                let heightLeft = imgHeight;
                
                let position = 10;
                pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 10, position, imgWidth, imgHeight, undefined, 'FAST');
                heightLeft -= pageHeight;
                
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 10, position, imgWidth, imgHeight, undefined, 'FAST');
                    heightLeft -= pageHeight;
                }
                
                pdf.save('content.pdf'); // Save the PDF
                
                // Show the buttons again
                downloadButton.classList.remove('hidden');
                urlButton.classList.remove('hidden');
            }).catch(error => {
                console.error('Error capturing the content:', error);
                // Show the buttons again if an error occurs
                downloadButton.classList.remove('hidden');
                urlButton.classList.remove('hidden');
            });
        });

        // Event listener to ensure the URL button navigates correctly
        document.getElementById('urlButton').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default action to handle manually
            window.location.href = event.target.href; // Navigate to form.php
        });
    </script>
</body>
</html>
