<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mycontacts";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $contactId = mysqli_real_escape_string($conn, $_POST["delete"]);
    $sqlDelete = "DELETE FROM contacts WHERE id = '$contactId'";
    
    if ($conn->query($sqlDelete) === TRUE) {
        echo "<p class='success-message'>Contact deleted successfully.</p>";
    } else {
        echo "<p class='error-message'>Error deleting contact: " . $conn->error . "</p>";
    }
}

// Export contacts as VCard
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["export"])) {
    // Retrieve all contacts
    $sqlSelect = "SELECT * FROM contacts";
    $result = $conn->query($sqlSelect);

    // Generate vCard content
    $vcardContent = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $vcardContent .= "BEGIN:VCARD\r\n";
            $vcardContent .= "VERSION:3.0\r\n";
            $vcardContent .= "FN:" . $row["name"] . "\r\n";
            $vcardContent .= "TEL:" . $row["number"] . "\r\n";
            $vcardContent .= "NOTE:" . $row["relationship"] . "\r\n";
            $vcardContent .= "END:VCARD\r\n";
        }
    }

    // Set the HTTP headers for vCard download
    header('Content-Type: text/vcard');
    header('Content-Disposition: attachment; filename="contacts.vcf"');

    // Output vCard content
    echo $vcardContent;

    // Close the database connection and stop further execution
    $conn->close();
    exit();
}

// Retrieve all contacts
$sqlSelect = "SELECT * FROM contacts";
$result = $conn->query($sqlSelect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contacts</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
        }

        /* Container for the table to enable scrolling */
        .table-container {
            overflow: auto;
            max-height: 400px; /* Adjust the max height as needed */
        }

        table {
            background-color: #fff;
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin: auto;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4caf50;
            color: white;
        }

        td {
            position: relative;
        }

        .delete-btn {
            background-color: #f44336;
            color: #fff;
            border: none;
            padding: 5px;
            cursor: pointer;
            position: absolute;
            right: 5px;
            top: 5px;
            border-radius: 4px;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }

        .export-btn {
            background-color: #2196F3;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .export-btn:hover {
            background-color: #0b7dda;
        }

        .success-message {
            color: #4CAF50;
            margin-top: 20px;
        }

        .error-message {
            color: #F44336;
            margin-top: 20px;
        }

        /* Additional styling for sticky header */
        thead th {
            position: -webkit-sticky; /* For Safari */
            position: sticky;
            top: 0;
            background-color: #4caf50;
            color: white;
        }
    </style>
</head>
<body>

<!-- Container for the table to enable scrolling -->
<div class="table-container">

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Number</th>
                <th>Relationship</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["number"] . "</td>";
                    echo "<td>" . $row["relationship"] . "</td>";
                    echo "<td><form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' style='display:inline;'>
                              <button type='submit' class='delete-btn' name='delete' value='" . $row["id"] . "'>Delete</button>
                          </form></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No contacts found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</div>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <button type="submit" class="export-btn" name="export">Export as VCard</button>
</form>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
