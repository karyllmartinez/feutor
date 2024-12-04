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
    <link rel="stylesheet" href="css/admin.css">

    <style>
        /*NAVBAR STYLE*/
.navbar-custom{
    background-color: #1d5512;
    border-bottom: 2px solid #e1e0c2; /* thin bottom navbar color */
}

.navbar-custom .navbar-brand {
    color: #e1e0c2; /* Change the ADMIN DASHBOARD font color to white */
    font-weight: bold;
}

.navbar-custom .nav-link{
    color: white;
    transition: color 0.2s ease-in-out; /* Add transition for smooth hover effect */
}

.navbar-custom .nav-link:hover {
    color: #f2b41b; /* Hover effect color */
}

.navbar-custom .nav-link:active,
.navbar-custom .nav-link.active {
    color: #f2b41b; /* Active (clicked) state color */
}

.navbar a {
    font-family: Helvetica;
}

.navbar-custom .nav-link, 
.navbar-custom a {
    color: white; /* Change nav-link and LOGOUT link font color to white */
    text-decoration: none; /* Remove underline */
    transition: color 0.2s ease-in-out; /* Smooth hover effect */
    margin-right: 20px; /* Add space between links */
}

.navbar-custom .nav-link:hover, 
.navbar-custom a:hover {
    color: #f2b41b; /* Hover effect color */
}

.navbar-custom .nav-link:active, 
.navbar-custom .nav-link.active, 
.navbar-custom a:active {
    color: #f2b41b; /* Active (clicked) state color */
}

/*
*
*
*
*
*
*/

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="ad-index.php">Admin Dashboard</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link active" href="tutorearnings.php">Tutor Earnings</a>
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
            <?php
           include('php/ad-tearnings.php');
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


