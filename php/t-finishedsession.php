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
.btn-outline-custom1{
color: #0F422A;
    background-color: #ffffff;
    border-color: none;
    font-weight: bold;
    letter-spacing: 0.05em;
    position: absolute;
    left: 80%;
    width: 200px; /* Adjust width as needed */
}
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
    top: 20%;
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

.rate:not(:checked) > input {
    position:absolute;
    top:-9999px;
}
.rate:not(:checked) > label {
    float:right;
    width:1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:30px;
    color:#ccc;
}
.rate:not(:checked) > label:before {
    content: '★ ';
}
.rate > input:checked ~ label {
    color: #ffc700;    
}
.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #deb217;  
}
.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
}
    #comment {
  border: 2px solid #2D7A41; /* Set the border color */
  border-radius: 8px; /* Add border radius */
  display: flex;
  justify-content: flex-start;
  padding: 10px; /* Optional: adds padding inside the textarea */
  resize: none; /* Optional: disables resizing */
  width: 100%;
  height: 150px;
}

 .star {
      font-size: 30px;
      color: #ccc;
    }

    .star.filled {
      color: #ffc700;
    }


</style>";

// Retrieve logged-in tutor's tutorID
$tutorID = $_SESSION['auth_tutor']['tutor_id'];
// Query to fetch sessions for the logged-in tutor with student names, ordered by nearest session date
$sql = "SELECT s.sessionID, DATE_FORMAT(s.sessionDate, '%M %e, %Y') AS formattedSessionDate, TIME_FORMAT(s.startTime, '%h:%i %p') AS formattedStartTime, TIME_FORMAT(s.endTime, '%h:%i %p') AS formattedEndTime, s.duration, s.subject, s.teachingMode, s.need, s.paymentStatus, 
    CASE
        WHEN s.status = 'Finished' THEN 'Finished'
        ELSE s.status
    END AS status,
    CONCAT(st.firstname, ' ', st.lastname) AS studentFullName, st.degreeProgram, st.year, t.ratePerHour
FROM session s
INNER JOIN student st ON s.studentID = st.studentID
INNER JOIN tutor t ON s.tutorID = t.tutorID
WHERE s.tutorID = ? AND (s.status = 'Finished' )
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
    // Loop through the result set and display the data
    while ($row = mysqli_fetch_assoc($result)) {
        $sessionID = $row['sessionID'];

         // Check if the student has already submitted a review for this session
         $reviewCheckSql = "SELECT * FROM review WHERE sessionID = '$sessionID' ";
         $reviewCheckResult = mysqli_query($conn, $reviewCheckSql);
         $reviewExists = mysqli_num_rows($reviewCheckResult) > 0;
 
         if ($reviewExists) {
             $reviewRow = mysqli_fetch_assoc($reviewCheckResult);
             $way = $reviewRow['way'];
            $communication = $reviewRow['communication'];
            $knowledge = $reviewRow['knowledge'];
            $engagement = $reviewRow['engagement'];
            $time = $reviewRow['time'];
             $comment = $reviewRow['comment'];
         }
    

        echo "<div class='col-md-12 mb-3' style = 'margin-left:0px; width:100% !important;'>";
        echo "<div class='card shadow custom-card' style='height: 200px; margin-top: 1%;'>";
        echo "<div class='card-body'>";

        echo "<h4 class='tutorName'>" . $row['studentFullName']  ."</h4>";
        echo "<p class='mode'>" . "<img src = 'icons/mode.png' class = 'iconmode'/>"  . $row['teachingMode'] . "  ". "<strong>|</strong>" . "  ". $row["formattedSessionDate"] .  "  ". "<strong>|</strong>" . "  " .   $row["formattedStartTime"] ." - ".   $row["formattedEndTime"] ."</p>";
        echo "<p class='subj'> " . "<img src = 'icons/subj.png' class = 'iconsubj'/>"  . $row['subject'] . "</p>";
        
        echo "<p class = 'bio'>Status: <br>" . $row['status'] . "</p>";

        echo "<p class= 'rate'>Total Cost: ₱" . number_format($row['duration'] * $row['ratePerHour'], 2) . "</p>";

        if (!$reviewExists) {
          echo "<button class='btn btn-outline-custom1' data-toggle='modal' data-target='#detailsModal_$sessionID'>No Rating Yet</button>";
      }

      if ($reviewExists) {
        echo "<button class='btn btn-outline-custom2' data-toggle='modal' data-target='#detailsModal2_$sessionID'>View Rating</button>";
      }
     
      

        
       

        echo "</div>";
        echo "</div>";
        echo "</div>";

        echo "
    <div class='modal fade' id='detailsModal2_$sessionID' tabindex='-1' role='dialog' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title' id='detailsModalLabel2{$sessionID}'></h5>
            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <div class='modal-body'>
            <table>
              <tbody>
                <tr>
                  <td>
                    <p style='font-weight: bold; font-size: 15px; display: flex; justify-content: start; margin: 0; color: #0F422A'>" . htmlspecialchars($row['studentFullName']) . "</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p style='font-size: 15px; display: flex; justify-content: start; color: #0F422A'>Subject: " . htmlspecialchars($row['subject']) . "</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p style='font-weight: bold; font-size: 10px; display: flex; justify-content: start; margin:0; color: #0F422A'>Way of Teaching:</p>
                  </td>
                </tr>
                

                <tr>
                  <td>";

                    // Display stars based on the rating value
                    $totalStars = 5; // Total number of stars
                    for ($i = 1; $i <= $totalStars; $i++) {
                        if ($i <= $way) {
                            echo "<span class='star filled'>★</span>"; // Filled star
                        } 
                        
                        else {
                            echo "<span class='star'>★</span>"; // Empty star
                        }
                    }


                  echo"

                  </td>
                </tr>

                <tr>
                  <td>
                    <p style='font-weight: bold; font-size: 10px; display: flex; justify-content: start; margin:0; color: #0F422A'>Communication Skills:</p>
                  </td>
                </tr>
                

                <tr>
                  <td>";

                    // Display stars based on the rating value
                    $totalStars = 5; // Total number of stars
                    for ($i = 1; $i <= $totalStars; $i++) {
                        if ($i <= $communication) {
                            echo "<span class='star filled'>★</span>"; // Filled star
                        } 
                        
                        else {
                            echo "<span class='star'>★</span>"; // Empty star
                        }
                    }


                  echo"

                  </td>
                </tr>

                <tr>
                  <td>
                    <p style='font-weight: bold; font-size: 10px; display: flex; justify-content: start; margin:0; color: #0F422A'>Knowledge and Skills:</p>
                  </td>
                </tr>
                

                <tr>
                  <td>";

                    // Display stars based on the rating value
                    $totalStars = 5; // Total number of stars
                    for ($i = 1; $i <= $totalStars; $i++) {
                        if ($i <= $knowledge) {
                            echo "<span class='star filled'>★</span>"; // Filled star
                        } 
                        
                        else {
                            echo "<span class='star'>★</span>"; // Empty star
                        }
                    }


                  echo"

                  </td>
                </tr>


                <tr>
                  <td>
                    <p style='font-weight: bold; font-size: 10px; display: flex; justify-content: start; margin:0; color: #0F422A'>Engagement:</p>
                  </td>
                </tr>
                

                <tr>
                  <td>";

                    // Display stars based on the rating value
                    $totalStars = 5; // Total number of stars
                    for ($i = 1; $i <= $totalStars; $i++) {
                        if ($i <= $engagement) {
                            echo "<span class='star filled'>★</span>"; // Filled star
                        } 
                        
                        else {
                            echo "<span class='star'>★</span>"; // Empty star
                        }
                    }


                  echo"

                  </td>
                </tr>

                <tr>
                  <td>
                    <p style='font-weight: bold; font-size: 10px; display: flex; justify-content: start; margin:0; color: #0F422A'>Time Management:</p>
                  </td>
                </tr>
                

                <tr>
                  <td>";

                    // Display stars based on the rating value
                    $totalStars = 5; // Total number of stars
                    for ($i = 1; $i <= $totalStars; $i++) {
                        if ($i <= $time) {
                            echo "<span class='star filled'>★</span>"; // Filled star
                        } 
                        
                        else {
                            echo "<span class='star'>★</span>"; // Empty star
                        }
                    }


                  echo"

                  </td>
                </tr>


                
                <tr>
                  <td>
                    <p style='font-weight: bold; font-size: 20px; display: flex; justify-content: start; margin:0; color: #0F422A'>Comment:</p>
                  </td>
                </tr>

                <tr>
                  <td>
                    <p style='font-size: 15px; display: flex; justify-content: start; color: #0F422A; text-align: justify;'> ". htmlspecialchars($comment) . "</p>

                   
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
    echo "Error: " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>
