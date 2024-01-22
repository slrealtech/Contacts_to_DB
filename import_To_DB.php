<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mycontacts";

// connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit_single"])) {
        // Insert single contact
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $number = mysqli_real_escape_string($conn, $_POST["number"]);
        $relationship = mysqli_real_escape_string($conn, $_POST["relationship"]);

        $sqlInsert = "INSERT INTO contacts (name, number, relationship) VALUES ('$name', '$number', '$relationship')";
        if ($conn->query($sqlInsert) === TRUE) {
            echo "<p class='success-message'>Contact added successfully.</p>";
        } else {
            echo "<p class='error-message'>Error adding contact: " . $conn->error . "</p>";
        }
    } elseif (isset($_FILES["vcf_file"])) {
        // Import contacts from VCF file
        $vcfFile = $_FILES["vcf_file"];

        $fileType = pathinfo($vcfFile["name"], PATHINFO_EXTENSION);
        if ($fileType != "vcf") {
            echo "<p class='error-message'>Invalid file format. Please upload a VCF file.</p>";
            exit();
        }


        $vcfContent = file_get_contents($vcfFile["tmp_name"]);

        preg_match_all('/FN:(.*?)(?=\r\n|\n|\r|END:VCARD)/s', $vcfContent, $nameMatches);
        preg_match_all('/TEL:(.*?)(?=\r\n|\n|\r|END:VCARD)/s', $vcfContent, $numberMatches);

        $names = $nameMatches[1];
        $numbers = $numberMatches[1];

        for ($i = 0; $i < count($names) && $i < count($numbers); $i++) {
            $name = mysqli_real_escape_string($conn, $names[$i]);
            $number = mysqli_real_escape_string($conn, $numbers[$i]);

            $sqlInsert = "INSERT INTO contacts (name, number) VALUES ('$name', '$number')";
            $conn->query($sqlInsert);
        }

        echo "<p class='success-message'>Contacts imported successfully.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Contact</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Insert Single Contact</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="name">Name:</label>
    <input type="text" name="name" required><br>
    <label for="number">Number:</label>
    <input type="text" name="number" required><br>
    <label for="relationship">Relationship:</label>
    <input type="text" name="relationship"><br>
    <button type="submit" name="submit_single">Insert Single Contact</button>
</form>

<h2>Import from VCF File</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
    <label for="vcf_file">Choose a VCF file:</label>
    <input type="file" name="vcf_file" accept=".vcf" required>
    <button type="submit" name="submit_vcf">Import from VCF File</button>
</form>

</body>
</html>
