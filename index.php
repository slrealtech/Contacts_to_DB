<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Management System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 30px;
            margin: 20px;
            text-align: center;
            width: 400px; /* Set a fixed width for the square container */
        }

        h1 {
            color: #000;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            margin-top: 10px;
            font-size: 16px;
            line-height: 1.5;
        }

        .button-container {
            margin-top: 30px;
        }

        .button {
            background-color: #2196F3;
            color: #fff;
            border: none;
            padding: 15px 30px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Contact Management System</h1>
    <p>The Contact Management System is a web-based application that allows users to efficiently manage their contacts.
        It provides features to add, view, delete, and export contacts in VCard (.vcf) format for easy sharing and backup.</p>


    <div class="button-container">
        <button class="button" onclick="location.href='import_to_DB.php'">Import Contacts</button>
    </div>

    <div class="button-container">
        <button class="button" onclick="location.href='export.php'">Export Contacts</button>
    </div>
</div>

</body>
</html>
