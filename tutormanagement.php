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
    <title>Tutor Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">

    <style>
   /* Main Section Centering */
.main-section {
    margin: 0 auto;
    padding: 20px;
    text-align: left;
    max-width: 1200px;  /* Limit the width of the main section */
    overflow-x: auto;   /* Add horizontal scroll if content overflows */
}

/* Grid Layout for Tutors */
.tutor-container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;  /* Ensure columns wrap on smaller screens */
}

/* Individual Column Styling */
.tutor-column {
    flex: 1 1 48%;  /* Adjust columns to take up 48% width, allowing space between */
    padding: 10px;
    
   
    margin-bottom: 20px;  /* Add some space between columns */
}

/* Table Styling */
.table {
    width: 100%;
    max-width: 100%;  /* Ensure table doesn't exceed the container width */
    border-collapse: collapse;
    margin: 10px 0;
    overflow-x: auto; /* Add horizontal scroll if the table is too wide */
}

.table thead th {
    background-color: green;
    color: white;
    padding: 8px;
    font-size: 13px;
}

.table td, .table th {
    border: 1px solid #ccc;
    padding: 8px;
    font-size: 12px;
}

.table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table tbody tr:hover {
    background-color: #f1f1f1;
}

/* Responsive Design */
@media (max-width: 768px) {
    .tutor-column {
        flex: 1 1 100%; /* Make each column take full width on smaller screens */
    }

    .table th, .table td {
        padding: 6px;
    }
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
                <a class="nav-link active" href="tutormanagement.php">Tutor Management</a>
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


    <div class="main-section">
    
    <div class="tutor-container">
        <!-- Approved Student Tutors -->
        <div class="tutor-column">
            <h3>Approved Student Tutors</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Degree Program / Level</th>
                        <th>Year</th>
                        <th>Google Drive Link</th>
                        <th>Subject Expertise</th>
                        <th>Rate Per Hour</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include('php/ad-tmanagement.php'); ?>
                </tbody>
            </table>
        </div>

        <!-- Declined Student Tutors -->
        <div class="tutor-column">
            <h3>Declined Student Tutors</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Degree Program / Level</th>
                        <th>Year</th>
                        <th>Google Drive Link</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php


// Fetch declined tutors
$declined_query = "SELECT * FROM tutor WHERE approvalStatus='Declined'";
$declined_result = mysqli_query($conn, $declined_query);
                    while ($row = mysqli_fetch_assoc($declined_result)) {
                        echo "<tr>";
                        echo "<td>" . $row['tutorID'] . "</td>";
                        echo "<td>" . $row['firstName'] . "</td>";
                        echo "<td>" . $row['lastName'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['degreeProgram'] . "</td>";
                        echo "<td>" . $row['year'] . "</td>";
                        echo "<td>" . $row['gdriveLink'] . "</td>";
                        echo "<td>
                                <form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this tutor?\")'>
                                    <input type='hidden' name='tutorID' value='" . $row['tutorID'] . "'>
                                    <button type='submit' class='btn btn-danger' name='delete_btn'>Delete</button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>