<?php
echo "<style type='text/css'>
      
      .profile-picture {
        margin: %;
        max-width: 200px;
        max-height: 200px;
        width: 160px; /* Set a fixed width */
        height: 160px; /* Set a fixed height */
        object-fit: cover; /* Maintain aspect ratio and cover the container */
      }
      .tutorName{
        position:absolute; 
        top:8%; 
        left:18.5%; 
        margin-left:0px; 
        margin-right:0px; 
        font-size: 20px;
      }
      .degreeProgram{
        position:absolute; 
        top:25%; 
        left:17%; 
        margin-left:0px; 
        margin-right:0px; 
        font-size: 15px;
        width: 100%;
      }
      .icongrad{
        width: 33px; /* Set a fixed width */
        height: 20px; /* Set a fixed height */
        position:relative; 
        margin-bottom:0.5%;
        margin-left: 1.3%;
      }
      .mode{
        position:absolute; 
        top:37%; 
        left:17.7%; 
        margin-left:1px; 
        margin-right:0px; 
        font-size: 15px;
        width: 100%;
      }
      .iconmode{
        width: 20px; /* Set a fixed width */
        height: 20px; /* Set a fixed height */
        position:relative; 
        margin-bottom:0.5%;
        margin-right:0.5%;
        margin-left: 1%;
        object-fit: cover;
      }
      .subj{
        position:absolute; 
        top:49%; 
        left:17.7%; 
        margin-left:1px; 
        margin-right:0px; 
        font-size: 15px;
        width: 100%;

      }
      .iconsubj{
        width: 19px; /* Set a fixed width */
        height: 20px; /* Set a fixed height */
        position:relative; 
        margin-bottom:0.5%;
        margin-right:0.5%;
        margin-left: 1%;
        object-fit: cover;
      }
      .bio{
        position: absolute;
        top: 65%;
        left: 19%;
        margin-left: 1px;
        margin-right: 0px;
        font-size: 15px;
        width: 58%;
        color: #666; /* Grey font color */
        font-style: italic; /* Italic font */
      }
      .btn-view-details{
        color: #0F422A;
        background-color: #ffffff;
        border-color: #0F422A;
        font-weight: bold;
        letter-spacing: 0.05em;
      
        bottom:13%;
       left:80%;
       width:200px;
       height:40px;
       position: absolute;
       z-index: 2;
   
      }
   
      .duration{
        bottom:59%;
        left:85%;
        width:200px;
        height:40px;
        position: absolute;
        font-size: 20px;
        font-weight: 300px;
      }





      .close {
      font-size: 4rem; 
      color: #0F422A; 
      font-weight: 300;
      margin:0;
      }

      .modal-content {
      border-radius: 25px; 
       border: none; 
    }

   .modal-dialog {
    max-width: 50%; /* Adjust percentage as needed */
    width: 50%;     /* Set to 80% of the screen width */
}
  
.profile-picture-container {
    float: left; /* Move profile picture to the left */
    margin-right: 20px; /* Add some space between the picture and the text */
    margin-left: 20px;
}

.profile-picturemod {
    max-width: 200px;
    max-height: 200px;
    width: 160px; /* Set a fixed width */
    height: 160px; /* Set a fixed height */
    object-fit: cover; /* Maintain aspect ratio and cover the container */
    border: 2px solid black; /* Optional: Add a border to the image */
}

      .studentName{
        top:10.5%; 
        left:35.6%; 
        margin-left:0px; 
        margin-right:0px; 
        font-size: 20px;
        font-weight: bold;
        color:#0F422A;
      }


      .degreeProgrammod{
        width: 100%; /* Make sure it takes up the full width */
        font-size: 15px;
        margin-left: 0px;
        margin-right: 0px;
        margin-top:-10px;
      }


      .modemod{
        left:32.3%; 
        margin-left:1px; 
        margin-right:0px; 
        margin-top:-10px;
        font-size: 15px;
        width: 100%;
      }

      .iconmodemodal{
        width: 20px; /* Set a fixed width */
        height: 20px; /* Set a fixed height */
        position:relative; 
        margin-bottom:0.2%;
        margin-right:0.2%;
        margin-left: 0.1%;
        object-fit: cover;
      }

      .subjmod{
        left:32.3%; 
        margin-left:1px; 
        margin-right:0px; 
        margin-top:-10px;
        font-size: 15px;
        width: 100%;
      }

      .iconsubjmodal{
        width: 19px; /* Set a fixed width */
        height: 20px; /* Set a fixed height */
        position:relative; 
        margin-bottom:0.2%;
        margin-right:0.2%;
        margin-left: 0.2%;
        object-fit: cover;
      }

      .timemod{
        position: relative; 
        left:9.9%; 
        margin-left:1px; 
        margin-top:-10px;
        margin-right:0px; 
        font-size: 15px;
        width: 100%;
        
      }
      .icontimemodal{
        width: 19px; /* Set a fixed width */
        height: 20px; /* Set a fixed height */
        position:relative; 
        margin-bottom:0.2%;
        margin-right:0.2%;
        margin-left: -9.5%;
        object-fit: cover;
      }

      .need{
        position: relative; 
        top: 15px; 
        left: 203px; 
        font-size: 15px;
        font-style: italic; 
        text-align: justify;
        width: 74%; 
        white-space: normal; 
        overflow-wrap: break-word;
        }
        .status{
        position: absolute;
        top: 4.2%; 
        right: 7.2%; 
        font-weight:bold;
      }

       .durationmod{
        position: absolute;
        top: 13%; 
        right: 7.0%;
        font-weight:bold;
      }

      .buttons-container {
        display: inline-block;
        margin-top: 3%; 
        margin-right:23%;
        margin-bottom:4%;
        
    }
    .button-container1,
    .button-container2 {
    display: inline-block;
    margin-right: 10px; /* Adjust the right margin as needed */
    border-radius: 50px;
    }
    
    /* Adjust the width of the button containers as per your design */
    .button-container1 {
    width: 300px;
    }
    .button-container2 {
    width: 120px;
    }


    .buttonM {
      background-color: white; 
      color: black; 
      border: 1px solid #008ae6;
      width:160px;
      padding:5.9px;
      border-radius:4px;
      position: absolute;
      top: 25%; /* Adjust the distance from the top */
      right:6.2%;
      background-image: url('icons/mail.png');
       background-size: auto 100%; /* Adjust the size of the background image */
      background-position: 20px center; /* Position the background image */
     background-repeat: no-repeat; 
      padding-left: 43px;
    }
    
    .buttonM:hover {
      background-color: #008ae6;
      color: white;
    }

    .modal-footer{
     border-top: 1px solid #dee2e6; /* Border color */
    margin: 0; /* Remove default margins */
    margin-top:5px;
    padding: 0; /* Remove default padding */
    width: 100%; /* Ensure it takes the full width */
    display: flex; /* Use flexbox to align items */
    }

      </style>";

// Retrieve logged-in tutor's tutorID
$tutorID = $_SESSION['auth_tutor']['tutor_id'];
// Query to fetch sessions for the logged-in tutor with student names
$sql = "SELECT s.sessionID, DATE_FORMAT(s.sessionDate, '%M %e, %Y') AS formattedSessionDate, TIME_FORMAT(s.startTime, '%h:%i %p') AS formattedStartTime, TIME_FORMAT(s.endTime, '%h:%i %p') AS formattedEndTime, s.duration, s.subject, s.teachingMode, s.need, s.paymentStatus, s.status, 
CONCAT(st.firstname, ' ', st.lastname) AS studentFullName, st.degreeProgram, st.year, t.ratePerHour
FROM session s
INNER JOIN student st ON s.studentID = st.studentID
INNER JOIN tutor t ON s.tutorID = t.tutorID
WHERE s.tutorID = ? AND s.status = 'Pending'
ORDER BY s.sessionID DESC"; // Order by session ID in descending order
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tutorID);
$stmt->execute();
$result = $stmt->get_result();

// Check if the prepare statement was successful
if (!$stmt) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

$stmt->bind_param("i", $tutorID);
$stmt->execute();

// Check if the execute statement was successful
if ($stmt->error) {
    die("Execute failed: " . htmlspecialchars($stmt->error));
}

$result = $stmt->get_result();

// Check if the query was successful
if ($result) {
    // Loop through the result set and display the data
    while ($row = mysqli_fetch_assoc($result)) {
        $sessionID = $row['sessionID'];
        echo "<div class='col-md-12 mb-3' style = 'margin-left:0px; width:100% !important;'>";
        echo "<div class='card shadow custom-card' style='height: 200px; margin-top: 1%;'>";
        echo "<div class='card-body'>";
        // Display tutor information
        echo "<h4 class='tutorName'>" . $row['studentFullName']  ."</h4>";
        echo "<p class='card-text'><img src='icons/default.png' alt='Profile Picture' class='profile-picture'></p>";
        echo "<p class='degreeProgram'>" . "<img src = 'icons/grad.png' class = 'icongrad'/>" . $row["degreeProgram"] . " - " . $row['year'] ."</p>";
        echo "<p class='mode'>" . "<img src = 'icons/mode.png' class = 'iconmode'/>"  . $row['teachingMode'] . "  ". "<strong>|</strong>" . "  ". $row["formattedSessionDate"] .  "  ". "<strong>|</strong>" . "  " .   $row["formattedStartTime"] ." - ".   $row["formattedEndTime"] ."</p>";
        echo "<p class='subj'> " . "<img src = 'icons/subj.png' class = 'iconsubj'/>"  . $row['subject'] . "</p>";
        echo "<p class='bio'>" . substr($row['need'], 0, 155) . (strlen($row['need']) > 75 ? '...' : '') . "</p>";

        // Calculate total cost
        $totalCost = $row['duration'] * $row['ratePerHour'];

        // Check if duration has a decimal value
        if ((float)$row['duration'] == (int)$row['duration']) {
            // Display duration without decimal value
            echo "<p class='duration'>" . (int)$row['duration'] . "hrs". " = ₱" . number_format($totalCost, 2) . "</p>";
        } else {
            // Display duration with decimal value
            echo "<p class='duration'>" . $row['duration'] . "hrs</p>";
        }

        echo "<button type='button' class='btn btn-outline-success btn-view-details' data-toggle='modal' data-target='#detailsModal{$sessionID}'>View More Details</button><br><br>";


        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        echo "
        <div class='modal fade' id='detailsModal{$sessionID}' tabindex='-1' role='dialog' aria-labelledby='detailsModalLabel{$sessionID}' aria-hidden='true'>
          <div class='modal-dialog modal-dialog-centered' role='document'>
            <div class='modal-content'>
              <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
                </button>
              </div>
              <div class='modal-body'>
                <div class='container'>
                  <div class='profile-picture-container'>
                    <img src='icons/default.png' alt='Profile Picture' class='profile-picturemod'>
                  </div>
                  <p class='studentName'>" . htmlspecialchars($row['studentFullName']) . "</p>
                  <p class='degreeProgrammod'>" . htmlspecialchars($row['degreeProgram']) . " - " . htmlspecialchars($row['year']) . "</p>
                  <p class='modemod'>" . "<img src='icons/mode.png' class='iconmodemodal'/>" . htmlspecialchars($row['teachingMode']) . "</p>
                  <p class='subjmod'>" . "<img src='icons/subj.png' class='iconsubjmodal'/>" . htmlspecialchars($row['subject']) . "</p>
                  <p class='timemod'>" . "<img src='icons/time.png' class='icontimemodal'/>" . htmlspecialchars($row["formattedSessionDate"]) . " <strong>|</strong> " . htmlspecialchars($row["formattedStartTime"]) . " - " . htmlspecialchars($row["formattedEndTime"]) . " <strong>|</strong> " . (int)$row['duration'] . "hrs" . "</p>
                  <p class='need'>Need: <br/> " . htmlspecialchars($row['need']) . "</p>
                  <p class='status'>Status: " . htmlspecialchars($row['status']) . "</p>";

                   // Check if duration has a decimal value
          if ((float)$row['duration'] == (int)$row['duration']) {
              // Display total price without decimal value
              echo "<p class='durationmod'>Total Price: ₱" . number_format($totalCost, 2) . "</p>";
          } else {
              // Display duration with decimal value
              echo "<p class='durationmod'>" . htmlspecialchars($row['duration']) . " hrs</p>";
          }



              echo "<button class='button buttonM'>Message</button>
                  </div>
              </div>
              <div class='modal-footer'> <!-- Modal footer -->
                <div class='buttons-container'>
                  <div class='button-container1'>
                    <a href='php/acceptsession.php?sessionID=" . htmlspecialchars($sessionID) . "'>
                      <button class='btn btn-outline-success'>Accept & Ask for Payment</button>
                    </a>
                  </div>
                  <div class='button-container2'>
                    <a href='php/declinedsession.php?sessionID=" . htmlspecialchars($sessionID) . "'>
                      <button class='btn btn-outline-danger'>Decline</button>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        ";       
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>