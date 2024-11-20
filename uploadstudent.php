<?php
include('connection/dbconfig.php'); // Include your database connection file

// Function to handle CSV file upload
if (isset($_POST["submit"])) {
    // Check if a file was uploaded
    if (isset($_FILES["csv_file"]) && $_FILES["csv_file"]["error"] == 0) {
        $file = $_FILES["csv_file"]["tmp_name"];
        $handle = fopen($file, "r");

        // Skip the header row
        fgetcsv($handle);

        // Read each row in the CSV
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $firstname = $data[0];
            $lastname = $data[1];
            $degreeProgram = $data[2];
            $year = $data[3];
            $email = $data[4];
            $password = password_hash($data[5], PASSWORD_DEFAULT); // Hash the password

            // Check if the student already exists based on email
            $stmt_check = $conn->prepare("SELECT studentID FROM student WHERE email = ?");
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $result = $stmt_check->get_result();

            if ($result->num_rows > 0) {
                // Student exists, you may update the record if needed
                // Update the existing record if desired
                $stmt_update = $conn->prepare("UPDATE student SET firstname = ?, lastname = ?, degreeProgram = ?, year = ?, password = ?, created_at = NOW() WHERE email = ?");
                $stmt_update->bind_param("ssssss", $firstname, $lastname, $degreeProgram, $year, $password, $email);
                $stmt_update->execute();
            } else {
                // Insert new student record
                $stmt_insert = $conn->prepare("INSERT INTO student (firstname, lastname, degreeProgram, year, email, password, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt_insert->bind_param("ssssss", $firstname, $lastname, $degreeProgram, $year, $email, $password);
                $stmt_insert->execute();
            }
        }

        fclose($handle);
        echo "Data has been imported successfully.";
    } else {
        echo "Please upload a valid CSV file.";
    }
}

$conn->close();
?>


<?php
// Start session
session_start();

include('php/ad-auth.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Earnings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="ad-index.php">Admin Dashboard</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="tutorearnings.php">Tutor Earnings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="tutormanagement.php">Tutor Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="subjectmanagement.php">Subject Management</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="uploadstudent.php">Add Student</a>
            </li>
        </ul>
    </div>
    <a href="ad-logout.php">Logout</a>
</nav>

<div class="container mt-3">

    <table class="table">
        <thead>
            <tr>
                
                
            </tr>
        </thead>
        <tbody>
        <h2>Upload CSV to Import Students</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="csv_file">Choose CSV File:</label>
        <input type="file" name="csv_file" id="csv_file" required>
        <button type="submit" name="submit">Upload</button>
    </form>
        </tbody>
    </table>

    
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>



