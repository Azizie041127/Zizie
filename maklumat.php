<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Permohonan Akaun Sistem - Personal Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1 {
            text-align: center;
            color: #333333;
            font-size: 22px;
            font-weight: bold;
        }

        label {
            color: #333333;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #dddddd;
            border-radius: 4px;
        }

        .custom-button {
            width: 100%;
            padding: 10px;
            background-color: #60a493; /* Base color */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            display: block;
            text-align: center;
            line-height: 1.5; /* Align text vertically */
        }

        .custom-button:hover {
            background-color: #4a8a7b; /* Hover color */
        }

        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }

            .custom-button {
                font-size: 14px;
            }
        }

        /* Unique class for the logo using Flexbox */
        .unique-logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .unique-logo-container img {
            max-width: 250px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="unique-logo-container">
            <img src="img/dbkulogowithname.png" alt="Logo">
        </div>
        <br><br>
        <form id="personalDetailsForm" class="bg-light p-5 rounded shadow" action="your_submission_script.php" method="POST">
            <h1 class="text-center mb-4">Sila Masukkan Justifikasi Permohonan</h1>
            
            <div class="form-group">
                <fieldset>
                    <legend>Maklumat Pemohon</legend>

                    <label for="applicantId" class="mt-2">No. K/P:</label>
                    <input type="text" id="applicantId" name="applicantId" class="form-control" required>

                    <label for="applicantName">Nama Pemohon:</label>
                    <input type="text" id="applicantName" name="applicantName" class="form-control" required>

                    <label for="accountPosition" class="mt-2">Jawatan/Gred:</label>
                    <input type="text" id="accountPosition" name="accountPosition" class="form-control" required>

                    <label for="accountDepartment" class="mt-2">Bahagian/Jabatan:</label>
                    <input type="text" id="accountDepartment" name="accountDepartment" class="form-control" required>

                    <label for="justification" class="mt-2">Justifikasi:</label>
                    <textarea id="justification" name="justification" rows="4" class="form-control" required></textarea>
                </fieldset>
            </div>

            <button type="submit" class="custom-button">Hantar</button>
        </form>
    </div>
</body>
</html>
