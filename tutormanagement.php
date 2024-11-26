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
    <h2>Approved Student Tutors</h2>
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Degree Program / Level of Highschool</th>
                <th>Year</th>
                <th>Google Drive Link</th>
                <th>Subject Expertise</th>
                <th>Rate Per Hour</th>
                <th>Action</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
           include('php/ad-tmanagement.php');
            ?>
        </tbody>
    </table>

    
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
