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
}

.btn-outline-custom1 {
    top: 20%;
}

.btn-outline-custom2 {
    top: 49%;
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
        CONCAT(t.firstname, ' ', t.lastname) AS tutorFullName, t.ratePerHour, t.profilePicture
        FROM session s
        INNER JOIN tutor t ON s.tutorID = t.tutorID
        WHERE s.studentID = ? AND s.status = 'Pending'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();



if ($result) {
  if (mysqli_num_rows($result) > 0) {
    // Loop through the result set and display the data
    while ($row = mysqli_fetch_assoc($result)) {
      $sessionID = $row['sessionID'];

      echo "<div class='col-md-12 mb-3' style = 'margin-left:0px; width:100% !important;'>";
      echo "<div class='card shadow custom-card' style='height: 200px; margin-top: 1%;'>";
      echo "<div class='card-body'>";
  
      echo "<h4 class = 'tutorName'> " . $row['tutorFullName'] . "</h4>";
      echo "<p class='mode'>" . "<img src = 'icons/mode.png' class = 'iconmode'/>" . $row['teachingMode'] . "  " . "<strong>|</strong>" . "  " . $row["formattedSessionDate"] . "  " . "<strong>|</strong>" . "  " . $row["formattedStartTime"] . " - " . $row["formattedEndTime"] . "</p>";
      echo "<p class='subj'> " . "<img src = 'icons/subj.png' class = 'iconsubj'/>" . $row['subject'] . "</p>";
  
      echo "<p class = 'bio'>Status: <br>" . $row['status'] . "</p>";
  
      echo "<p class= 'rate'>Total Cost: ₱" . number_format($row['duration'] * $row['ratePerHour'], 2) . "</p>";
  
  
      echo "<button class='btn btn-outline-custom1' data-toggle='modal' data-target='#detailsModal_$sessionID'>View Details</button>";
  
      
  
  
  
      echo "</div>";
      echo "</div>";
      echo "</div>";
  
  
  
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
                 
                  <table style='width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
  <thead>
    <tr>
      <td colspan='2' style='background-color: green; color: #ffffff; text-align: center; padding: 15px; font-size: 18px; font-weight: bold;'>Pending Request Details</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style='padding: 15px; background-color: #f8f9fa; border-bottom: 1px solid #ddd;'>
        <p style='font-weight: bold; font-size: 16px; color: #333; margin: 0;'>" ."Tutor: " . $row['tutorFullName'] . "</p>
      </td>
    </tr>
    <tr>
      <td style='padding: 10px; background-color: #ffffff; border-bottom: 1px solid #ddd;'>
        <div style='color: #555; font-size: 14px;'>" . "Teaching Mode: <span style='font-weight: bold; color: #28a745;'>" . $row['teachingMode'] . "</span>" . "</div>
      </td>
    </tr>
    <tr>
      <td style='padding: 10px; background-color: #f8f9fa; border-bottom: 1px solid #ddd;'>
        <p style='font-size: 14px; color: #333; margin: 0;'>Subject: <span style='font-weight: bold; color: #28a745;'>" . $row['subject'] . "</span></p>
      </td>
    </tr>
    <tr>
      <td style='padding: 10px; background-color: #ffffff; border-bottom: 1px solid #ddd;'>
        <div style='color: #555; font-size: 14px;'>Date: <span style='font-weight: bold; color: #28a745;'>" . $row['formattedSessionDate'] . "</span></div>
      </td>
    </tr>
    <tr>
      <td style='padding: 10px; background-color: #f8f9fa; border-bottom: 1px solid #ddd;'>
        <div style='color: #555; font-size: 14px;'>Time: <span style='font-weight: bold; color: #28a745;'>" . $row['formattedStartTime'] . " - " . $row['formattedEndTime'] . "</span></div>
      </td>
    </tr>
    <tr>
      <td style='padding: 10px; background-color: #ffffff;'>
        <p style='font-size: 14px; color: #333; margin: 0;'>Your Note: <span style='font-weight: bold; color: #28a745;'>" . $row['need'] . "</span></p>
      </td>
    </tr>

<tr>
     <td style='padding: 10px; background-color: #f8f9fa; border-bottom: 1px solid #ddd;'>
      <p style='font-size: 14px; color: #333; margin: 0;'>Total Cost: <span style='font-weight: bold; color: #28a745;'>" ."₱" . number_format($row['duration'] * $row['ratePerHour'], 2) . "</span></p>
      
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
    // No pending requests
    echo "<p>No pending request</p>";
  }
} else {
  // Error in executing the query
  echo "Error: " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>