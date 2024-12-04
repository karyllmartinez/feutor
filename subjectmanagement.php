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
        <title>Subject Management</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

body {
    font-family: Helvetica;
}

h4 {
    font-weight: bold;
}


/* Style for the card headers */
.card-header {
    background-color: #e1e0c2; /* Change background color if needed */
    color: #000000; /* Change text color to #e1e0c2 */
    text-align: center; /* Optional: Center-align the text */
}

/* Add border to the Subject List card */
.card {
    border: 2px solid #000; /* Change the thickness and color as needed */
    border-radius: 5px; /* Optional: add rounded corners */
}

/* Optional: Shadow for the card to make it more distinct */
.card {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.subject-list-header {
    border-bottom: 2px solid #000; /* Change the thickness and color as needed */
}

.add-new-subject-header {
    border-bottom: 2px solid #000; /* Change the thickness and color as needed */
}

/* Style for input text field and select dropdowns */
#subjectName {
    border: 1px solid #2b2b2b; /* Change the thickness and color as needed */
    border-radius: 4px; /* Optional: add rounded corners */
}

/* Focus state style for text field and dropdowns */
#subjectName {
    width: 100%;
    padding: 12px 20px;
    margin: 3px 0;
    -webkit-transition: 0.3s;
    transition: 0.3s;
    outline: none;
    border-radius: 4px; /* Optional: add rounded corners */
}

/* Focus state styling for the Subject Name text field */
#subjectName:focus {
    border-color: #e1e0c2; /* Change the border color */
    box-shadow: 0 0 8px #e1e0c2; /* Add glowing effect */
}

/* Button styling for the "Add Subject" button */
.add-subject-btn {
    border-radius: 20px; /* Rounded corners */
    border: 2px solid; /* Border thickness */
    padding: 9px 10px; /* Button padding */
    font-family: Helvetica;
    font-size: 13px; /* Font size */
    background-color: transparent; /* Transparent background */
    transition: all 0.3s ease; /* Smooth transition */
    cursor: pointer; /* Pointer cursor */
    margin-bottom: 5px;
    min-width: 100px; /* Minimum button width */
    text-align: center; /* Center text */
    height: 40px; /* Button height */
}

.add-subject-btn-primary {
    border-color: #1d5512; /* Primary border color */
    color: #1d5512; /* Primary text color */
}

.add-subject-btn-primary:hover {
    background-color: #1d5512; /* Primary background color on hover */
    color: white; /* Primary text color on hover */
}

.button-container {
    text-align: right; /* Aligns the button to the right within the container */
}

/*SEARCH BAR STYLE*/
input[name="search"] {
    padding: 6px 7px;
    margin: 3px 0;
    -webkit-transition: 0.3s;
    transition: 0.3s;
    outline: none;
    border-radius: 4px; /* Optional: add rounded corners */
    border: 1px solid #2b2b2b; /* Add border to match other input fields */
}

/* Focus state styling for the search input field */
input[name="search"]:focus {
    border-color: #e1e0c2; /* Change the border color */
    box-shadow: 0 0 8px #e1e0c2; /* Add glowing effect */
}


/* RESET TO ALL BUTTON STYLE */
.btn-reset {
    border-radius: 20px; /* Rounded corners */
    border: 2px solid; /* Border thickness */
    padding: 9px 10px; /* Button padding */
    font-family: Helvetica;
    font-size: 13px; /* Font size */
    background-color: transparent; /* Transparent background */
    transition: all 0.3s ease; /* Smooth transition */
    cursor: pointer; /* Pointer cursor */
    margin-bottom: 5px;
    min-width: 90px; /* Minimum button width (adjust as needed) */
    text-align: center; /* Center text */
    height: 38px; /* Adjust height for a smaller button */
}

/* Default state for reset button */
.btn-reset {
    border-color: #145da0; /* Primary border color */
    color: #145da0; /* Primary text color */
}

/* Hover state for reset button */
.btn-reset:hover {
    background-color: #145da0; /* Primary background color on hover */
    color: white; /* Primary text color on hover */
}

/* Add space below the search form */
.search-form {
    margin-bottom: 10px; /* Adjust the value as needed */
}

/*TABLE STYLE*/
.table-striped {
    text-align: center;
}

.table-striped tbody tr:nth-child(odd) {
    background-color: #f5f4e6; /* Light gray background for odd rows */
}

.table-striped tbody tr:nth-child(even) {
    background-color: #ffffff; /* White background for even rows (optional) */
}

/* Hover effect for table rows */
.table-striped tbody tr:hover {
    background-color: #f9f9f9; /* Background color when hovering */
}

/* DELETE SUBJECT BUTTON STYLE */
.btn-delete {
    border-radius: 20px; /* Rounded corners */
    border: 2px solid; /* Border thickness */
    padding: 8px 10px; /* Button padding */
    font-family: Helvetica;
    font-size: 13px; /* Font size */
    background-color: transparent; /* Transparent background */
    transition: all 0.3s ease; /* Smooth transition */
    cursor: pointer; /* Pointer cursor */
    margin-bottom: 5px;
    min-width:70px; /* Minimum button width (adjust as needed) */
    text-align: center; /* Center text */
    height: 38px; /* Adjust height for a smaller button */
}

/* Default state for reset button */
.btn-delete {
    border-color: #722410; /* Primary border color */
    color: #722410; /* Primary text color */
}

/* Hover state for reset button */
.btn-delete:hover {
    background-color: #722410; /* Primary background color on hover */
    color: white; /* Primary text color on hover */
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
                <a class="nav-link" href="tutormanagement.php">Tutor Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="subjectmanagement.php">Subject Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="uploadstudent.php">Add Student</a>
            </li>
        </ul>
    </div>
    <a href="ad-logout.php">Logout</a>
</nav>

    <div class="container fluid mt-5">
        <div class="row">
            <!-- Add New Subject Form -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4>Add New Subject</h4>
                    </div>
                    <?php
                        session_start();

                        // Check if session message is set
                        if (isset($_SESSION['message'])) {
                            // Display session message
                            echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';

                            // Clear session message
                            unset($_SESSION['message']);
                        }
                    ?>
                    
                    
                    <div class="card-body">
                        <form action="subjectmanagementcode.php" method="POST">
                            <div class="form-group">
                                <label for="subjectName">Subject Name:</label>
                                <input type="text" class="form-control" id="subjectName" name="subjectName" required>
                            </div>
                          
                            <button type="submit" class="btn btn-primary" name="submit">Add Subject</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Subject List -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4>Subject List</h4>
                    </div>
                    <div class="card-body" style="width: 550px; overflow-y: auto;">
                  
                        <?php include('php/getsubjects.php'); ?>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </body>
    </html>
