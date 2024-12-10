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
    <title>Upload Students</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">

    <style>
    /* General container styling */
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Heading */
    h2 {
        font-size: 24px;
        color: #2d6a4f;
        margin-bottom: 20px;
        text-align: center;
        font-family: 'Arial', sans-serif;
    }

    /* Table */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table thead {
        background-color: #2d6a4f;
        color: white;
        text-align: left;
    }

    .table th, .table td {
        padding: 12px;
        border: 1px solid #d6e1e1;
        font-size: 16px;
    }

    /* File upload form */
    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: center;
        margin-top: 30px;
    }

    label {
        font-size: 18px;
        color: #2d6a4f;
        font-family: 'Arial', sans-serif;
    }

    input[type="file"] {
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #d6e1e1;
        width: 100%;
        max-width: 400px;
        background-color: #ffffff;
        color: #333;
        transition: all 0.3s ease;
    }

    input[type="file"]:hover {
        border-color: #2d6a4f;
        background-color: #e8f5e9;
    }

    button[type="submit"] {
        padding: 12px 20px;
        font-size: 16px;
        color: white;
        background-color: #2d6a4f;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 200px;
        transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: #1e4f34;
    }

    /* Responsive adjustments */
    @media screen and (max-width: 600px) {
        .container {
            padding: 15px;
        }

        h2 {
            font-size: 20px;
        }

        button[type="submit"] {
            width: 100%;
        }

        input[type="file"] {
            max-width: 100%;
        }
    }


    /* Modal Background */
.custom-modal {
  display: none; /* Hidden by default */
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

/* Modal Content */
.modal-content {
  background-color: #fff;
  border-radius: 8px;
  width: 300px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Modal Header */
.modal-header h5 {
  font-size: 20px;
  margin: 0;
}

/* Modal Body */
.modal-body {
  font-size: 16px;
  margin: 20px 0;
}

/* Modal Footer */
.modal-footer {
  text-align: center;
}

button.btn-secondary {
  padding: 8px 16px;
  background-color: #4CAF50;
  border: none;
  color: white;
  cursor: pointer;
  border-radius: 5px;
  font-size: 16px;
  transition: background-color 0.3s ease;
}

button.btn-secondary:hover {
  background-color: #45a049;
}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="ad-index.php">Admin Dashboard</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="tutorearnings.php">Tutor Earnings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="studentrefunds.php">Student Refunds</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="tutormanagement.php">Tutor Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="subjectmanagement.php">Subject Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="uploadstudent.php">Add Student</a>
            </li>
        </ul>
    </div>
    <a href="ad-logout.php">Logout</a>
</nav>

<div class="container mt-3">
    <h2>Upload CSV to Import Students</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="csv_file">Choose CSV File:</label>
        <input type="file" name="csv_file" id="csv_file" required>
        <button type="submit" name="submit">Upload</button>
    </form>

    <h3>CSV Preview</h3>
    <div id="csvPreview"></div>
</div>

<!-- Modal Structure -->
<div id="uploadSuccessModal" class="custom-modal">
  <div class="modal-content">
    <div class="modal-header">
      <h5>Upload Successful</h5>
    </div>
    <div class="modal-body">
      Data has been uploaded successfully.
    </div>
    <div class="modal-footer">
      <button id="closeModal" class="btn btn-secondary">Close</button>
    </div>
  </div>
</div>


<script>
    document.getElementById('csv_file').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = function (event) {
            const csvData = event.target.result;
            const rows = csvData.split("\n");
            let table = '<table class="table table-bordered"><thead><tr><th>First Name</th><th>Last Name</th><th>Degree Program</th><th>Year</th><th>Email</th><th>Password</th></tr></thead><tbody>';

            rows.forEach(function (row, index) {
                if (index > 0) {  // Skip header row
                    const cols = row.split(",");
                    if (cols.length === 6) {
                        table += `<tr>
                                    <td>${cols[0]}</td>
                                    <td>${cols[1]}</td>
                                    <td>${cols[2]}</td>
                                    <td>${cols[3]}</td>
                                    <td>${cols[4]}</td>
                                    <td>${cols[5]}</td>
                                  </tr>`;
                    }
                }
            });

            table += '</tbody></table>';
            document.getElementById('csvPreview').innerHTML = table;
        };

        reader.readAsText(file);
    });
</script>


<script>
    // Function to show the success modal
    function showSuccessModal() {
        const modal = document.getElementById("uploadSuccessModal");
        modal.style.display = "flex"; // Show the modal
        setTimeout(function () {
            modal.style.display = "none"; // Hide the modal after 3 seconds
        }, 3000);
    }

    // Close the modal manually
    document.getElementById('closeModal').addEventListener('click', function () {
        const modal = document.getElementById("uploadSuccessModal");
        modal.style.display = "none"; // Hide the modal when close button is clicked
    });

    // Form submission handling
    document.querySelector('form').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting immediately

        // Simulate form submission to server via AJAX or equivalent
        const formData = new FormData(this);

        fetch("", {  // Replace "" with your form's action URL
            method: "POST",
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            // Handle successful form submission
            console.log(data); // Optional: log response for debugging
            showSuccessModal(); // Show success modal

            // Reset file input and preview
            document.getElementById('csv_file').value = "";  // Reset file input
            document.getElementById('csvPreview').innerHTML = "";  // Clear preview
        })
        .catch(error => {
            console.error('Error:', error); // Handle errors if necessary
        });
    });

    document.getElementById('csv_file').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = function (event) {
            const csvData = event.target.result;
            const rows = csvData.split("\n");
            let table = '<table class="table table-bordered"><thead><tr><th>First Name</th><th>Last Name</th><th>Degree Program</th><th>Year</th><th>Email</th><th>Password</th></tr></thead><tbody>';

            rows.forEach(function (row, index) {
                if (index > 0) {  // Skip header row
                    const cols = row.split(",");
                    if (cols.length === 6) {
                        table += `<tr>
                                    <td>${cols[0]}</td>
                                    <td>${cols[1]}</td>
                                    <td>${cols[2]}</td>
                                    <td>${cols[3]}</td>
                                    <td>${cols[4]}</td>
                                    <td>${cols[5]}</td>
                                  </tr>`;
                    }
                }
            });

            table += '</tbody></table>';
            document.getElementById('csvPreview').innerHTML = table;
        };

        reader.readAsText(file);
    });
</script>




    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>