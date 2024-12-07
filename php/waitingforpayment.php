<?php
echo "<script src='https://www.paypal.com/sdk/js?client-id=AU6AYP9LgxUcMt-MC3QjSq0ByXzxhDNXZCwVRNQ0fbPpr7avAKTncNpgsEIBdfODYUJ6BXqFXh8bGYIM&disable-funding=credit,card'></script>";

echo "<style type='text/css'>
/* Styling for the entire interface */

.profile-picture {
  margin: %;
  max-width: 200px;
  max-height: 200px;
  width: 160px; /* Set a fixed width */
  height: 160px; /* Set a fixed height */
  object-fit: cover; /* Maintain aspect ratio and cover the container */
}

.tutorName {
  position: absolute;
  top: 10%;
  left: 5%;
  font-size: 20px;
}

.degreeProgram {
  position: absolute;
  top: 25%;
  left: 17%;
  font-size: 15px;
  width: 100%;
}

.icongrad {
  width: 33px;
  height: 20px;
  position: relative;
  margin-bottom: 0.5%;
  margin-left: 1%;
}

.mode {
  position: absolute;
  top: 30%;
  left: 3.5%;
  font-size: 15px;
  width: 100%;
}

.iconmode {
  width: 20px;
  height: 20px;
  position: relative;
  margin-bottom: 0.5%;
  margin-right: 0.5%;
  margin-left: 1%;
  object-fit: cover;
}

.subj {
  position: absolute;
  top: 55%;
  left: 3.5%;
  font-size: 15px;
  width: 100%;
  font-weight: 600;
}

.iconsubj {
  width: 19px;
  height: 20px;
  position: relative;
  margin-bottom: 0.5%;
  margin-right: 0.5%;
  margin-left: 1%;
  object-fit: cover;
}

.bio {
  position: absolute;
  top: 20%;
  left: 49%;
  font-size: 15px;
  width: 58%;
  color: #666; /* Grey font color */
  font-style: italic; /* Italic font */
}

.btn-outline-custom1,
.btn-outline-custom2,
.btn-outline-custom3,
.btn-outline-custom-message {
  color: #0F422A;
  background-color: #ffffff;
  border-color: #0F422A;
  font-weight: bold;
  letter-spacing: 0.05em;
  position: absolute;
  left: 80%;
  width: 200px;
}

.paypalbtn {
  position: absolute;
  left: 80%;
  top: 15%;
}

.btn-outline-custom2 {
  top: 40%;
}
  .btn-outline-custom3 {
  top: 70%;
}

.btn-outline-custom-message {
  top: 70%; /* Adjust to position below the Cancel button */
}

.rate {
  top: 80%;
  left: 5%;
  width: 200px;
  height: 40px;
  position: absolute;
  font-size: 15px;
}
</style>";

// Retrieve logged-in student's studentID
$studentID = $_SESSION['auth_user']['user_id'];

// Query to fetch sessions for the logged-in student
$sql = "SELECT s.sessionID, DATE_FORMAT(s.sessionDate, '%M %e, %Y') AS formattedSessionDate, 
        TIME_FORMAT(s.startTime, '%h:%i %p') AS formattedStartTime, 
        TIME_FORMAT(s.endTime, '%h:%i %p') AS formattedEndTime, 
        s.duration, s.subject, s.teachingMode, s.need, s.paymentStatus, s.status, 
        CONCAT(t.firstname, ' ', t.lastname) AS tutorFullName, 
        t.ratePerHour, t.profilePicture, t.email
        FROM session s
        INNER JOIN tutor t ON s.tutorID = t.tutorID
        WHERE s.studentID = ? AND s.status = 'Waiting for Payment'";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();




if ($result) {
  // If no sessions are found with the status 'Waiting for Payment'
  if (mysqli_num_rows($result) == 0) {
    echo "<p>There are no pending payments.</p>";
  } else {
    echo "<script src='https://www.paypal.com/sdk/js?client-id=sb&currency=PHP&disable-funding=credit,card'></script>";

    while ($row = mysqli_fetch_assoc($result)) {
      // Existing code for displaying session details
      $sessionID = $row['sessionID'];

      echo "<div class='col-md-12 mb-3' style='margin-left:0px; width:100% !important;'>";
      echo "<div class='card shadow custom-card' style='height: 200px; margin-top: 1%;'>";
      echo "<div class='card-body'>";

      echo "<h4 class='tutorName'>" . $row['tutorFullName'] . "</h4>";
      echo "<br>";
      echo "<p class='mode'><img src='icons/mode.png' class='iconmode'/>" . $row['teachingMode'] . "  " . "<strong>|</strong>" . "  " . $row["formattedSessionDate"] . "  " . "<strong>|</strong>" . "  " . $row["formattedStartTime"] . " - " . $row["formattedEndTime"] . "</p>";
      echo "<p class='subj'><img src='icons/subj.png' class='iconsubj'/>" . $row['subject'] . "</p>";

      echo "<p class='bio'>Status: <br>" . $row['status'] . "</p>";
      echo "<p class='rate'>Total Cost: ₱" . number_format($row['duration'] * $row['ratePerHour'], 2) . "</p>";

      // PayPal Button Container
      echo "<a href='php/updatepaymentstatus.php?sessionID=" . $sessionID . "'>
            <div class='paypalbtn' id='paypal-button-container-" . $sessionID . "'></div> 
        </a>";

      // Cancel Request Button
      echo "<a href='php/cancelsession.php?sessionID=" . $sessionID . "'>
        <button class='btn btn-outline-custom2'>Cancel Request</button>
      </a>";

      // Message Button for Microsoft Teams with dynamic tutor email
      $tutorEmail = $row['email']; // Assuming you have already fetched the tutor's email from the database
      $teamsLink = "https://teams.microsoft.com/l/chat/0/0?users=" . urlencode($tutorEmail);

      echo "<a href='" . $teamsLink . "' target='_blank'>
        <button class='btn btn-outline-custom3'>Message</button>
      </a>";


      echo "</div>";
      echo "</div>";
      echo "</div>";

      // PayPal Button Script
      echo "<script>
        paypal.Buttons({
            style: {
                color: 'gold',
                shape: 'rect',
                label: 'paypal'
            },
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '" . number_format($row['duration'] * $row['ratePerHour'], 2) . "'
                        }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    console.log(details);
                    window.location.replace('php/updatepaymentstatus.php?sessionID=" . $sessionID . "');
                });
            },
            onCancel: function (data) {
                window.location.replace('s-waitingforpayment.php');
            }
        }).render('#paypal-button-container-" . $sessionID . "');
     </script>";
    }
  }
} else {
  echo "Error: " . mysqli_error($conn);
}


mysqli_close($conn);
?>