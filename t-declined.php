<?php
session_start();

include ('php/t-auth.php');
include('connection/dbconfig.php'); // Include your database connection file
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch the profile picture of the tutor
$tutorID = $_SESSION['auth_tutor']['tutor_id'];
$query = "SELECT profilePicture FROM tutor WHERE tutorID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $tutorID);
$stmt->execute();
$stmt->bind_result($profilePicture);
$stmt->fetch();
$stmt->close();

// Check if profile picture exists and if not, use a default image
$profilePicture = !empty($profilePicture) ? $profilePicture : 'icons/default.png';

?>




<!DOCTYPE html>
<html>
<head>
  <title>FEUTOR</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">




</head>
<body>

   <!-- Navigation Bar -->
   <nav class="navbar navbar-expand-lg navbar-green bg-green">
    <div class="container">
      <!-- Brand -->
      <a class="navbar-brand" href="#">FEUTOR</a>
      <!-- Toggler Button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Navigation Items -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="t-dashboard.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="appointmentsDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Appointments
            </a>
            <div class="dropdown-menu" aria-labelledby="appointmentsDropdown">
              <a class="dropdown-item" href="t-approved.php">Accepted</a>
              <a class="dropdown-item" href="t-declined.php">Declined</a>
              <a class="dropdown-item" href="t-finished.php">Finished</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Messages</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Notifications</a>
          </li>
          <li class="nav-item user-dropdown" style="position: relative; display: inline-block;">
            <!-- Profile Picture and Inverted Triangle as trigger -->
            <a href="#" id="userDropdown" style="text-decoration: none;" onclick="toggleDropdown(event)">
              <img src="<?php echo $profilePicture; ?>" alt="Profile Picture"
                style="width: 40px; height: 40px; border-radius: 50%; margin-right: 5px;">
              <!-- White Inverted Triangle Indicator -->
              <span
                style="border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 5px solid #fff; display: inline-block; vertical-align: middle;"></span>
            </a>

            <!-- Dropdown menu -->
            <ul id="dropdownMenu"
              style="display: none; position: absolute; top: 50px; right: 0; background-color: #fff; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); border-radius: 4px; list-style-type: none; padding: 10px; min-width: 150px;">
              <li style="margin-bottom: 5px;">
                <strong><?php echo $tutor_firstname; ?></strong>
              </li>
              <li style="border-bottom: 1px solid #ddd; margin-bottom: 5px;"></li> <!-- Divider -->
              <li style="margin-bottom: 5px;">
                <a href="t-profile.php" style="text-decoration: none; color: #000;">Edit Profile</a>
              </li>
              <li>
                <a href="t-logout.php" style="text-decoration: none; color: #000;">Logout</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-3" style="align-content: center">
        <h1 class="s-header">Declined Sessions</h1>
        <div class="row justify-content-center">
            <?php include('php/t-declinedsession.php'); ?>
        </div>
    </div>

  
    <script>
    function toggleDropdown(event) {
      event.preventDefault(); // Prevent default anchor behavior
      const dropdownMenu = document.getElementById('dropdownMenu');
      // Toggle dropdown visibility
      dropdownMenu.style.display = (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') ? 'block' : 'none';
    }

    // Close dropdown if clicked outside
    window.onclick = function (event) {
      const dropdownMenu = document.getElementById('dropdownMenu');
      const userDropdown = document.getElementById('userDropdown');

      if (!userDropdown.contains(event.target)) {
        // Close the dropdown if the click is outside the dropdown area
        if (dropdownMenu.style.display === 'block') {
          dropdownMenu.style.display = 'none';
        }
      }
    }
  </script>





  <!-- jQuery, Popper.js, and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


  <script src="disableBackButton.js"></script>


 


</body>
</html>


