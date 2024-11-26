<?php
session_start();

include('php/t-auth.php');
include('connection/dbconfig.php'); // Include your database connection file



$query = "SELECT `tutorID`, `firstName`, `lastName`, `email`, `degreeProgram`, `year`, `gdriveLink`, `approvalStatus`, `created_at`, `profilePicture`, `subjectExpertise`, `teachingMode`, `ratePerHour`, `bio` FROM `tutor` WHERE `tutorID` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $tutorID); // Assuming tutorID is an integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  // Fetch data
  $tutorData = $result->fetch_assoc();
} else {
  $message = "No data found for the tutor.";
}

// Check if profile picture exists and if not, use a default image
$profilePicture = !empty($profilePicture) ? $profilePicture : 'icons/default.png';

// Query to fetch tutor availability
$availabilityQuery = "SELECT id, tutor_id, day_of_week, start_time, end_time FROM tutorAvailability WHERE tutor_id = ?";
$availabilityStmt = $conn->prepare($availabilityQuery);
$availabilityStmt->bind_param("i", $tutorID); // Use the same tutorID
$availabilityStmt->execute();
$availabilityResult = $availabilityStmt->get_result();

// Prepare availability data
$availabilityData = [];
if ($availabilityResult->num_rows > 0) {
    while ($row = $availabilityResult->fetch_assoc()) {
        $availabilityData[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Edit Your Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">

  <style>
    /* Modal styles */
    .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            overflow: hidden; /* Prevent scrolling */
        }

    .modal-content {
      background-color: #fefefe;
      margin: 8% auto;
      /* 15% from the top and centered */
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      /* Could be more or less, depending on screen size */
      max-width: 500px;
      /* Max width */
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }


    .close {
      color: #aaa;
      display: flex;
      justify-content: flex-end;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }
  </style>

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




  <!-- <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

             

                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4>Set up your Profile</h4>
                    </div>
                    <div class="card-body">

                    <form action="t-profilecode.php" method="POST" enctype="multipart/form-data">

                    
                    <div class="mb-3 text-center">
                        <img id="profilePicturePreview" src="#" alt="Profile Picture Preview" class="rounded-circle" style="max-width: 100px; max-height: 100px;">

                          </div>

                          <div class="mb-3">
                              <label>Profile Picture</label>
                              <input type="file" name="profilePicture" accept="image/jpeg, image/png" class="form-control-file" id="profilePictureInput">
                          </div>


                            <div class="mb-3">
                                <label>First Name</label>
                                <input type="text" name="firstName" required placeholder="Enter First Name" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Last Name</label>
                                <input type="text" name="lastName" required placeholder="Enter Last Name" class="form-control">
                            </div>
                           
                            
                            

                            

                      

                            <div class="mb-3">
                                <label>Teaching Mode</label>
                                <select name="teachingMode" class="form-control" required>
                                    <option value="" selected disabled>Select Preferred Mode</option>
                                    <option value="Online">Online</option>
                                    <option value="School">School</option>
                                    <option value="Online & School">Online & School</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Rate Per Hour</label>
                                <input type="number" name="ratePerHour" required placeholder="Enter Rate Per Hour" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Bio</label>
                                <textarea name="bio" required placeholder="Enter Bio" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <button type="submit" name="register_btn" class="btn btn-primary">Save</button>
                            </div>
                          
                          
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div> -->


  <div
    style="max-width: 800px; margin: auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 20px; margin-top: 5%;">
    <div style="display: flex; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 20px;">
      <img src="<?php echo $tutorData['profilePicture']; ?>" alt="Profile Picture"
        style="width: 120px; height: 120px; border-radius: 50%; margin-right: 20px;">
      <div style="flex-grow: 1;">
        <h1 style="margin: 0; color: #333;"><?php echo $tutorData['firstName'] . ' ' . $tutorData['lastName']; ?></h1>
      </div>
      <div style="text-align: right;">
        <a href="#" onclick="document.getElementById('editModal').style.display='block';"
          style="text-decoration: none; background-color: #007bff; color: white; padding: 10px 15px; border-radius: 5px; display: block; margin-bottom: 5px; text-align: center;">Edit
          Profile</a>
          <a href="#" onclick="document.getElementById('availabilityModal').style.display='block';"
   style="text-decoration: none; background-color: #28a745; color: white; padding: 10px 15px; border-radius: 5px; display: block;">
   Set Availablility
</a>
      </div>
    </div>

    <div style="margin-top: 20px;">
    <h2 style="color: #555;">About</h2>
    <p><strong>Email:</strong> <?php echo $tutorData['email']; ?></p>
    <p><strong>Degree Program:</strong> <?php echo $tutorData['degreeProgram']; ?></p>
    <p><strong>Year:</strong> <?php echo $tutorData['year']; ?></p>
    <p><strong>Subject Expertise:</strong> <?php echo $tutorData['subjectExpertise']; ?></p>
    <p><strong>Teaching Mode:</strong> <?php echo $tutorData['teachingMode']; ?></p>
    <p><strong>Rate Per Hour:</strong> P<?php echo $tutorData['ratePerHour']; ?></p>
    <p><strong>Bio:</strong> <?php echo nl2br($tutorData['bio']); ?></p>
    <p><strong>Account Created On:</strong> <?php echo date('F j, Y', strtotime($tutorData['created_at'])); ?></p>

    <!-- Display Availability -->
    <p><strong>Available Days and Times:</strong></p>
    <ul>
        <?php
        if (!empty($availabilityData)) {
            foreach ($availabilityData as $availability) {
                // Convert times to 12-hour format
                $startTime = date("g:i A", strtotime($availability['start_time']));
                $endTime = date("g:i A", strtotime($availability['end_time']));
                echo "<li>{$availability['day_of_week']}: $startTime - $endTime</li>";
            }
        } else {
            echo "<li>No availability set.</li>";
        }
        ?>
    </ul>
</div>




  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="document.getElementById('editModal').style.display='none';">&times;</span>
      <form action="t-profilecode.php" method="POST" enctype="multipart/form-data">


        <div class="mb-3 text-center">
          <img id="profilePicturePreview" src="#" alt="Profile Picture Preview" class="rounded-circle"
            style="max-width: 100px; max-height: 100px;">

        </div>

        <div class="mb-3">
          <label>Profile Picture</label>
          <input type="file" name="profilePicture" accept="image/jpeg, image/png" class="form-control-file"
            id="profilePictureInput">
        </div>


        <div class="mb-3">
          <label>First Name</label>
          <input type="text" name="firstName" required placeholder="Enter First Name" class="form-control">
        </div>

        <div class="mb-3">
          <label>Last Name</label>
          <input type="text" name="lastName" required placeholder="Enter Last Name" class="form-control">
        </div>

        <div class="mb-3">
          <label>Teaching Mode</label>
          <select name="teachingMode" class="form-control" required>
            <option value="" selected disabled>Select Preferred Mode</option>
            <option value="Online">Online</option>
            <option value="School">School</option>
            <option value="Online & School">Online & School</option>
          </select>
        </div>

        <!-- <div class="mb-3">
          <label>Rate Per Hour</label>
          <input type="number" name="ratePerHour" required placeholder="Enter Rate Per Hour" class="form-control">
        </div> -->
        
        <div class="mb-3">
          <label>Bio</label>
          <textarea name="bio" required placeholder="Enter Bio" class="form-control"></textarea>
        </div>

        <div class="mb-3">
          <button type="submit" name="register_btn" class="btn btn-primary">Save</button>
        </div>


      </form>
    </div>
  </div>

 <!-- Modal for Availability -->
<div id="availabilityModal" class="modal">
    <div class="modal-content">
        <!-- Close button -->
        <span class="close" onclick="document.getElementById('availabilityModal').style.display='none';">&times;</span>

        <!-- Button to add new availability -->
        <div style="text-align: center; margin-bottom: 20px;">
            <button id="addScheduleButton" class="btn btn-success" style="width: auto; padding: 10px 70px; font-size: 26px; cursor: pointer; display: inline-block;">Add Schedule</button>
        </div>

     <!-- Form to set new availability (hidden by default) -->
<div id="addAvailabilityForm" style="display: none;">
    <h3>Add/Update Availability</h3><br>
    <form action="t-profilecode.php" method="POST">
        <div class="mb-3" style="display: flex; align-items: center; margin-bottom: 20px;">
            <label for="availableDays" style="flex-basis: 20%; font-size: 16px;">Available Days:</label>
            <select name="availableDays" id="availableDays" class="form-control" style="flex-grow: 1; font-size: 16px;" required>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
        </div>
        <div class="mb-3" style="display: flex; align-items: center; margin-bottom: 20px;">
            <label for="startTime" style="flex-basis: 20%; font-size: 16px;">Start Time:</label>
            <input type="time" name="startTime" id="startTime" class="form-control" style="flex-grow: 1; font-size: 16px;" required>
        </div>
        <div class="mb-3" style="display: flex; align-items: center; margin-bottom: 20px;">
            <label for="endTime" style="flex-basis: 20%; font-size: 16px;">End Time:</label>
            <input type="time" name="endTime" id="endTime" class="form-control" style="flex-grow: 1; font-size: 16px;" required>
        </div>
        <button type="submit" name="setAvailability" class="btn btn-success" style="width: 100%; padding: 10px 30px; font-size: 16px;">Save Availability</button>
    </form>
</div>


        <!-- Table displaying current availability -->
        <br><h3>Current Availability</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <th style="width: 40%; background-color: #f2f2f2; padding: 8px; text-align: left; border: 1px solid #ddd;">Day of Week</th>
                <th style="width: 30%; background-color: #f2f2f2; padding: 8px; text-align: left; border: 1px solid #ddd;">Start Time</th>
                <th style="width: 30%; background-color: #f2f2f2; padding: 8px; text-align: left; border: 1px solid #ddd;">End Time</th>
                <th style="width: 10%; background-color: #f2f2f2; padding: 8px; text-align: left; border: 1px solid #ddd;">Action</th>
            </tr>
            <?php
            if (!empty($availabilityData)) {
                foreach ($availabilityData as $availability) {
                    // Convert times to 12-hour format
                    $startTime = date("g:i A", strtotime($availability['start_time']));
                    $endTime = date("g:i A", strtotime($availability['end_time']));
                    echo "<tr>
                            <td style='padding: 8px; border: 1px solid #ddd;'>{$availability['day_of_week']}</td>
                            <td style='padding: 8px; border: 1px solid #ddd;'>$startTime</td>
                            <td style='padding: 8px; border: 1px solid #ddd;'>$endTime</td>
                            <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>
                                <form method='POST' action='t-profilecode.php'>
                                    <input type='hidden' name='availability_id' value='{$availability['id']}'>
                                    <button type='submit' name='delete_availability' style='padding: 4px 8px; background-color: red; color: white; border: none; border-radius: 4px; cursor: pointer;'>Remove</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='padding: 8px; text-align: center; border: 1px solid #ddd;'>No availability found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<!-- JavaScript to toggle the form visibility -->
<script>
    document.getElementById('addScheduleButton').addEventListener('click', function() {
        // Hide the "Add Schedule" button
        document.getElementById('addScheduleButton').style.display = 'none';
        
        // Show the "Add/Update Availability" form
        document.getElementById('addAvailabilityForm').style.display = 'block';
    });
</script>






    </div>
  </div>

  <script>
    // Close the modal when clicking anywhere outside of it
    window.onclick = function (event) {
      if (event.target == document.getElementById('editModal')) {
        document.getElementById('editModal').style.display = "none";
      }
    }
  </script>

<script>
    // Close the modal when clicking anywhere outside of it
    window.onclick = function (event) {
      if (event.target == document.getElementById('availabilityModal')) {
        document.getElementById('availabilityModal').style.display = "none";
      }
    }
  </script>



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



  <script>
    $(document).ready(function () {
      $('.form-select').select2();
    });
  </script>

  <script>
    // JavaScript code to handle file input change event and update image preview
    document.getElementById('profilePictureInput').addEventListener('change', function () {
      var reader = new FileReader();

      reader.onload = function (e) {
        document.getElementById('profilePicturePreview').setAttribute('src', e.target.result);
      };

      reader.readAsDataURL(this.files[0]);
    });
  </script>

<script>
function toggleScheduleForm() {
    const form = document.getElementById('scheduleForm');
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}
</script>
</body>

</html>