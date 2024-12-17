<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
</head>
<body>
    <h1>Generate QR Code for This Page</h1>
    
    <!-- Button to generate QR code -->
    <button id="generateQrButton">Generate QR Code</button>
    
    <!-- Container to display QR code -->
    <div id="qrCodeContainer" style="margin-top: 20px;">
        <!-- QR code image will be displayed here -->
    </div>
    
    <script>
        document.getElementById('generateQrButton').addEventListener('click', function() {
            // Get the current page URL
            var pageUrl = window.location.href;
            
            // Create the QR code image
            var qrCodeImage = document.createElement('img');
            qrCodeImage.src = 'generate_qr.php?url=' + encodeURIComponent(pageUrl);
            
            // Display the QR code
            var qrCodeContainer = document.getElementById('qrCodeContainer');
            qrCodeContainer.innerHTML = ''; // Clear any previous QR code
            qrCodeContainer.appendChild(qrCodeImage);
        });
    </script>
</body>
</html>
