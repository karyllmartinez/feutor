<?php
echo "<script src='https://www.paypal.com/sdk/js?client-id=AU6AYP9LgxUcMt-MC3QjSq0ByXzxhDNXZCwVRNQ0fbPpr7avAKTncNpgsEIBdfODYUJ6BXqFXh8bGYIM&disable-funding=credit,card'></script>";

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
  top:10%; 
  left:5%; 
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
  margin-left: 1%;
}
.mode{
  position:absolute; 
  top:30%; 
  left:3.5%; 
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
  top:55%; 
  left:3.5%; 
  margin-left:1px; 
  margin-right:0px; 
  font-size: 15px;
  width: 100%;
  font-weight: 600;
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
  top: 20%;
  left: 49%;
  margin-left: 1px;
  margin-right: 0px;
  font-size: 15px;
  width: 58%;
  color: #666; /* Grey font color */
  font-style: italic; /* Italic font */
}
.btn-outline-custom1,
.btn-outline-custom2 {
    color: #0F422A;
    background-color: #ffffff;
    border-color: #0F422A;
    font-weight: bold;
    letter-spacing: 0.05em;
    position: absolute;
    left: 80%;
    width: 200px; /* Adjust width as needed */
    margin-top: 5px; /* Add a small margin to space them slightly apart */
}

.btn-outline-custom1 {
  top: 20%;
}
  
.btn-outline-custom2 {
    top: 49%;
}

.messageBtn {
    position: absolute;
    top: 49%;
    left: 80%;
    width: 200px; /* Make sure it's the same width as the other button */
    margin-top: 5px; /* Small margin for separation */
}

.rate{
  top:80%;
  left:5%;
  width:200px;
  height:40px;
  position: absolute;
  z-index: 2;
  font-size: 15px;
  font-weight: 300px;
}

</style>";

// Retrieve logged-in student's studentID
$studentID = $_SESSION['auth_user']['user_id'];

// Query to fetch sessions for the logged-in student
$sql = "SELECT s.sessionID, DATE_FORMAT(s.sessionDate, '%M %e, %Y') AS formattedSessionDate, TIME_FORMAT(s.startTime, '%h:%i %p') AS formattedStartTime, TIME_FORMAT(s.endTime, '%h:%i %p') AS formattedEndTime, s.duration, s.subject, s.teachingMode, s.need, s.paymentStatus, s.status, 
        CONCAT(t.firstname, ' ', t.lastname) AS tutorFullName, t.ratePerHour, t.profilePicture, t.email AS tutorEmail
        FROM session s
        INNER JOIN tutor t ON s.tutorID = t.tutorID
        WHERE s.studentID = ? AND s.status = 'Approved'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are approved sessions
if ($result && mysqli_num_rows($result) > 0) {
  // PayPal SDK script
  echo "<script src='https://www.paypal.com/sdk/js?client-id=sb&currency=PHP&disable-funding=credit,card'></script>";

  // Loop through the result set and display the data
  while ($row = mysqli_fetch_assoc($result)) {
      $sessionID = $row['sessionID'];
      $tutorEmail = $row['tutorEmail']; // Get tutor's email

      echo "<div class='col-md-12 mb-3' style='margin-left:0px; width:100% !important;'>";
      echo "<div class='card shadow custom-card' style='height: 200px; margin-top: 1%;'>";
      echo "<div class='card-body'>";

      echo "<h4 class='tutorName'>" . $row['tutorFullName'] .  "</h4>";
      echo "<br>";
      echo "<p class='mode'><img src='icons/mode.png' class='iconmode'/>" . $row['teachingMode'] . "  " . "<strong>|</strong>" . "  " . $row["formattedSessionDate"] .  "  " . "<strong>|</strong>" . "  " . $row["formattedStartTime"] . " - " . $row["formattedEndTime"] . "</p>";
      echo "<p class='subj'><img src='icons/subj.png' class='iconsubj'/>" . $row['subject'] . "</p>";

      echo "<p class='bio'>Status: <br>" . $row['status'] . "</p>";
      echo "<p class='rate'>Total Cost: ₱" . number_format($row['duration'] * $row['ratePerHour'], 2) . "</p>";

      // PayPal Button for payment
      echo "<button class='btn btn-outline-custom1' data-toggle='modal' data-target='#detailsModal_$sessionID'>View Details</button>";

      // Add Message Button to redirect to Teams chat
      $teamsLink = "https://teams.microsoft.com/l/chat/0/0?users=" . urlencode($tutorEmail);
      echo "<a href='" . $teamsLink . "' target='_blank' class='btn btn-outline-custom2 messageBtn'>Message</a>";

      echo "</div>";
      echo "</div>";
      echo "</div>";

      // Modal for session details
      echo "
      <div class='modal fade' id='detailsModal_$sessionID' tabindex='-1' role='dialog' aria-hidden='true'>
        <div class='modal-dialog modal-dialog-centered' role='document'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h5 class='modal-title' id='detailsModalLabel'></h5>
              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
            </div>
            <div class='modal-body'>
              <table>
                <tbody>
                  <tr>
                    <td>
                      <p style='font-weight: bold; font-size: 15px; display: flex; justify-content: start;'>" . $row['tutorFullName'] . "</p>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div>". "Teaching Mode: " . $row['teachingMode'] . "</div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <p style='font-size: 15px; display: flex; justify-content: start;'>Subject: " . $row['subject'] . "</p>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div>". "Date:" . $row['formattedSessionDate'] . "</div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div>". "Time: " . $row['formattedStartTime'] . " - ". $row['formattedEndTime']. "</div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <p style='font-size: 15px; display: flex; justify-content: start;'>Your Note: " . $row['need'] . "</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      ";
  }
} else {
  // If no approved sessions, display this message
  echo "<p>There are no approved sessions.</p>";
}


// Close connection
mysqli_close($conn);
?>