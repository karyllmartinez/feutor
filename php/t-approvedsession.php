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
.btn-outline-custom2,.btn-outline-custom3 {
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
    top: 10%;
}

.btn-outline-custom2 {
    top: 40%;
}
.rate{
  top:70%;
  left:5%;
  width:200px;
  height:40px;
  position: absolute;
  z-index: 2;
  font-size: 15px;
  font-weight: 300px;

}
.btn-outline-custom3 {
    top: 65%;
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

/* Modal Styles */
.custom-modal {
  display: none; /* Hidden by default */
  position: fixed;
  z-index: 1000; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Overlay */
  justify-content: center;
  align-items: center;
}

.custom-modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  max-width: 500px;
  width: 80%;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.custom-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.custom-modal-title {
  margin: 0;
  font-size: 18px;
  font-weight: bold;
}

.custom-modal-close {
  background: none;
  border: none;
  font-size: 25px;
  font-weight: bold;
  color: #333;
  cursor: pointer;
}

.custom-modal-body {
  padding: 15px 0;
  font-size: 16px;
  color: #333;
}

.custom-modal-footer {
  display: flex;
  justify-content: flex-end;
}

.custom-modal-footer .btn {
  padding: 8px 16px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.custom-modal-footer .btn:hover {
  background-color: #0056b3;
}

.custom-modal.show {
  display: flex;
}



</style>";

// Retrieve logged-in tutor's tutorID
$tutorID = $_SESSION['auth_tutor']['tutor_id'];
// Query to fetch sessions for the logged-in tutor with student names, ordered by nearest session date
$sql = "SELECT s.sessionID, 
                DATE_FORMAT(s.sessionDate, '%M %e, %Y') AS formattedSessionDate, 
                TIME_FORMAT(s.startTime, '%h:%i %p') AS formattedStartTime, 
                TIME_FORMAT(s.endTime, '%h:%i %p') AS formattedEndTime, 
                s.duration, s.subject, s.teachingMode, s.need, s.paymentStatus, 
                CASE
                    WHEN s.status = 'Approved' THEN 'Paid'
                    ELSE s.status
                END AS status,
                CONCAT(st.firstname, ' ', st.lastname) AS studentFullName, 
                st.degreeProgram, st.year, t.ratePerHour, 
                st.email AS studentEmail  -- Added student's email here
        FROM session s
        INNER JOIN student st ON s.studentID = st.studentID
        INNER JOIN tutor t ON s.tutorID = t.tutorID
        WHERE s.tutorID = ? AND (s.status = 'Approved' OR s.status = 'Waiting for Payment')
        ORDER BY s.sessionDate ASC"; // Order by session date in ascending order


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
  // Check if there are no sessions in the result
  if (mysqli_num_rows($result) == 0) {
    echo "<p>No approved sessions for now.</p>";
  } else {
    // Loop through the result set and display the data
    while ($row = mysqli_fetch_assoc($result)) {
      $sessionID = $row['sessionID'];

      // Inside the loop where you display session details:
      echo "<div class='col-md-12 mb-3' style = 'margin-left:0px; width:100% !important;'>";
      echo "<div class='card shadow custom-card' style='height: 200px; margin-top: 1%;'>";
      echo "<div class='card-body'>";

      echo "<h4 class='tutorName'>" . $row['studentFullName'] . "</h4>";
      echo "<p class='mode'>" . "<img src = 'icons/mode.png' class = 'iconmode'/>" . $row['teachingMode'] . "  " . "<strong>|</strong>" . "  " . $row["formattedSessionDate"] . "  " . "<strong>|</strong>" . "  " . $row["formattedStartTime"] . " - " . $row["formattedEndTime"] . "</p>";
      echo "<p class='subj'> " . "<img src = 'icons/subj.png' class = 'iconsubj'/>" . $row['subject'] . "</p>";
      echo "<p class = 'bio'>Status: <br>" . $row['status'] . "</p>";
      echo "<p class= 'rate'>Total Cost: ₱" . number_format($row['duration'] * $row['ratePerHour'], 2) . "</p>";

      echo "<button class='btn btn-outline-custom1' data-toggle='modal' data-target='#detailsModal_$sessionID'>View Details</button>";

      
      if ($row['status'] == 'Paid') {
        // Get the session date and today's date
        $sessionDate = $row['formattedSessionDate'];  // Use 'formattedSessionDate' from the SQL query
        $todayDate = date('M d, Y');  // Today's date in the same format
        $nextDay = new DateTime();
        $nextDay->modify('+1 day');  // Get tomorrow's date
        
        // Convert session date and today's date to DateTime objects for comparison
        $sessionDateObj = DateTime::createFromFormat('M d, Y', $sessionDate);
        $todayDateObj = new DateTime($todayDate);
        
        // Check if DateTime objects were created correctly
        if ($sessionDateObj === false || $todayDateObj === false) {
            echo "<p class='text-danger'>Error in date conversion.</p><br><br>";
        } else {
            // Compare the session date with today's date and the next day
            if ($sessionDateObj < $todayDateObj) {
                // Session date is in the past, allow marking as finished
                echo "<a href='php/finishedsession.php?sessionID=" . $sessionID . "'>
                        <button class='btn btn-outline-custom2'>Mark as Finished</button>
                      </a><br><br>";
            } elseif (($sessionDateObj >= $todayDateObj) && ($sessionDateObj <= $nextDay)) {
                // Session date is today or tomorrow, so allow marking as finished
                echo "<a href='php/finishedsession.php?sessionID=" . $sessionID . "'>
                        <button class='btn btn-outline-custom2'>Mark as Finished</button>
                      </a><br><br>";
            } else {
                // Session date is more than one day in the future, show button but trigger modal
                echo "<button class='btn btn-outline-custom2' id='openFinishModalBtn'>
                        Mark as Finished
                      </button><br><br>";
            }
        }
    } else if ($row['status'] == 'Waiting for Payment') {
        echo "<a href='php/declinedsession.php?sessionID=" . $sessionID . "'>
                <button class='btn btn-outline-custom2'>Decline</button>
              </a><br><br>";
    }
    
    
      
    

      // Message button
      $studentEmail = $row['studentEmail'];  // Get the student's email

      $teamsUrl = "https://teams.microsoft.com/l/chat/0/0?users=" . urlencode($studentEmail);

      echo "
      <a href='$teamsUrl' target='_blank'>
          <button class='btn btn-outline-custom3' style='margin-top: 10px;'>Message</button>
      </a>
    ";

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
      <td colspan='2' style='background-color: #28a745; color: #ffffff; text-align: center; padding: 15px; font-size: 18px; font-weight: bold;'>Student Details</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style='padding: 15px; background-color: #f8f9fa; border-bottom: 1px solid #ddd;'>
        <p style='font-weight: bold; font-size: 16px; color: #0F422A; margin: 0;'>" . $row['studentFullName'] . "</p>
      </td>
    </tr>
    <tr>
      <td style='padding: 10px; background-color: #ffffff; border-bottom: 1px solid #ddd;'>
        <p style='font-weight: bold; font-size: 15px; color: #0F422A; margin: 0;'>Teaching Mode: <span style='font-weight: bold; color: #28a745;'>" . $row['teachingMode'] . "</span></p>
      </td>
    </tr>
    <tr>
      <td style='padding: 10px; background-color: #f8f9fa; border-bottom: 1px solid #ddd;'>
        <p style='font-weight: bold; font-size: 15px; color: #0F422A; margin: 0;'>Subject: <span style='font-weight: bold; color: #28a745;'>" . $row['subject'] . "</span></p>
      </td>
    </tr>
    <tr>
      <td style='padding: 10px; background-color: #ffffff; border-bottom: 1px solid #ddd;'>
        <p style='font-weight: bold; font-size: 15px; color: #0F422A; margin: 0;'>Date: <span style='font-weight: bold; color: #28a745;'>" . $row['formattedSessionDate'] . "</span></p>
      </td>
    </tr>
    <tr>
      <td style='padding: 10px; background-color: #f8f9fa; border-bottom: 1px solid #ddd;'>
        <p style='font-weight: bold; font-size: 15px; color: #0F422A; margin: 0;'>Time: <span style='font-weight: bold; color: #28a745;'>" . $row['formattedStartTime'] . " - " . $row['formattedEndTime'] . "</span></p>
      </td>
    </tr>
    <tr>
      <td style='padding: 10px; background-color: #ffffff;'>
        <p style='font-weight: bold; font-size: 15px; color: #0F422A; margin: 0;'>Note: <span style='font-weight: bold; color: #28a745;'>" . $row['need'] . "</span></p>
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
  }
} else {
  // Error handling in case of failed query
  echo "Error: " . mysqli_error($conn);
}


// Close connection
mysqli_close($conn);
?>


<div class="custom-modal" id="finishModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Session Finished</h5>
            <button class="custom-modal-close" id="closeFinishModalBtn">×</button>
        </div>
        <div class="custom-modal-body">
            You can only mark sessions as finished <strong>on the day of</strong> or <strong>the next day</strong> after the session date.
        </div>
        <div class="custom-modal-footer">
    <button class="btn btn-secondary" id="closeFinishModalBtn" style="display: none;">Close</button>
</div>

    </div>
</div>

<script>
    // Only bind the modal trigger if the session is in the future
    document.getElementById('openFinishModalBtn')?.addEventListener('click', function() {
        document.getElementById('finishModal').classList.add('show');
    });

    document.getElementById('closeFinishModalBtn').addEventListener('click', function() {
        document.getElementById('finishModal').classList.remove('show');
    });

    // Optional: Close modal if the overlay (background) is clicked
    document.querySelector('.custom-modal').addEventListener('click', function(event) {
        if (event.target === this) {
            document.getElementById('finishModal').classList.remove('show');
        }
    });
</script>
