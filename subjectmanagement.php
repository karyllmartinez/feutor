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
