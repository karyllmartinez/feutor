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
  font-size: 20px;
}
.degreeProgram{
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
  
  margin-left:1px; 
  margin-right:0px; 
  font-size: 15px;
  width: 100%;
}
.iconmode{
  width: 20px; /* Set a fixed width */
  height: 20px; /* Set a fixed height */
  margin: 0;
 
  object-fit: cover;
}
.subj{
  
  margin-left:1px; 
  margin-right:0px; 
  font-size: 15px;
  width: 100%;
  font-weight: 600;
}
.iconsubj{
  width: 19px; /* Set a fixed width */
  height: 20px; /* Set a fixed height */

  margin-bottom:0.5%;
  margin-right:0.5%;
  margin-left: 1%;
  object-fit: cover;
 
}
.bio{
 
  
  font-size: 15px;
  width: 100%;
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
  
   width: 100%;
}

.btn-outline-custom1 {
    width: 100%;
}
  
.btn-outline-custom2 {
 width: 100%;
}
.rate-btn{

  width:100%;
  height:40px;


  font-size: 15px;
  font-weight: 300px;

}

.modal-content {
  border-radius: 15px; /* Rounded corners */
}

.rate {
    float: left;
    height: 46px;
    
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

// Retrieve logged-in student's studentID
$studentID = $_SESSION['auth_user']['user_id'];



// Query to fetch sessions for the logged-in student
$sql = "SELECT s.sessionID, DATE_FORMAT(s.sessionDate, '%M %e, %Y') AS formattedSessionDate, TIME_FORMAT(s.startTime, '%h:%i %p') AS formattedStartTime, TIME_FORMAT(s.endTime, '%h:%i %p') AS formattedEndTime, s.duration, s.subject, s.teachingMode, s.need, s.paymentStatus, s.status, 
        
        CONCAT(t.firstname, ' ', t.lastname) AS tutorFullName, t.ratePerHour, t.profilePicture
        FROM session s
        INNER JOIN tutor t ON s.tutorID = t.tutorID
        WHERE s.studentID = ? AND s.status = 'Finished' ";



$sql2 = "SELECT reviewID, studentID, sessionID, way, communication, knowledge, engagement, time, comment, timestamp FROM review";





$result2 = $conn->query($sql2);



// Execute the query


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();


if (isset($_POST['submitReview'])) {
  // Get the form data
  $studentID = $_POST['studentID'];
  $sessionID = $_POST['sessionID'];
  $way = $_POST['way'];
  $communication = $_POST['communication'];
  $knowledge = $_POST['knowledge'];
  $engagement = $_POST['engagement'];
  $time = $_POST['time'];
  $comment = $_POST['comment'];
  $timestamp = date('Y-m-d H:i:s'); // current timestamp

  // Check if a review for this sessionID already exists
  $checkSql = "SELECT * FROM `review` WHERE `sessionID` = '$sessionID' AND `studentID` = '$studentID'";
  $checkResult = mysqli_query($conn, $checkSql);

  if (mysqli_num_rows($checkResult) > 0) {
      // Review already exists for this student and session
     
  } else {
      // Insert the data into the review table
      $sql = "INSERT INTO `review` (`studentID`, `sessionID`, `way`, `communication`, `knowledge`, `engagement`, `time`, `comment`, `timestamp`) 
              VALUES ('$studentID', '$sessionID', '$way', '$communication', '$knowledge', '$engagement', '$time', '$comment', '$timestamp')";

      if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Review submitted successfully!');</script>";
      } else {
          echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
  }
}




if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $sessionID = $row['sessionID'];

        // Check if the student has already submitted a review for this session
        $reviewCheckSql = "SELECT * FROM review WHERE sessionID = '$sessionID' AND studentID = '$studentID'";
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
    


    echo "

    <div class='col-md-12 mb-3' style='margin-left:0px; width:100% !important;'>
    <div class='card shadow custom-card' style='height: 100%; margin-top: 1%; '>
    <div class='card-body'>

     <table style = 'width: 100%; display: flex;'>
      <tbody style = 'width: 100%; display: flex;>
        <tr style = 'display:flex; width: 100%; '>
          <td style = 'width: 100%;' >
            <table style = 'width: 100%; height: 100%;'>
              <tbody style = 'width: 100%; '>
                <tr style='display: flex; width: 100%;'>
                                        <td style='width: 100%;'>
                                            <p style='font-weight: bold; font-size: 20px;'>" . $row['tutorFullName'] . "</p>
                                        </td>
                                    </tr>
                                    <tr style='display: flex; width: 100%;'>
                                        <td style='margin: 0; padding: 0; width: 100%;'>
                                            <div style='display: flex; width: 100%; align-items: center;'>
                                                <img src='icons/mode.png' class='iconmode' />
                                                <div style='margin-left: 10px;'>" . $row['teachingMode'] . "</div>
                                                <div style='border-left: 1px solid black; height: 30px; margin-left: 10px;'></div>
                                                <div style='margin-left: 10px;'>" . $row['formattedSessionDate'] . "</div>
                                                <div style='border-left: 1px solid black; height: 30px; margin-left: 10px;'></div>
                                                <div style='margin-left: 10px;'>" . $row['formattedStartTime'] . "</div>
                                                <div style='border-left: 1px solid black; height: 30px; margin-left: 10px;'></div>
                                                <div style='margin-left: 10px;'>" . $row['formattedEndTime'] . "</div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr style='display: flex; width: 100%;'>
                                        <td style='margin: 0; padding: 0; width: 100%;'>
                                            <div style='display: flex; align-items: center;'>
                                                <img src='icons/subj.png' class='iconsubj' />
                                                <div style='margin-left: 10px;'>" . $row['subject'] . "</div>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr style='display: flex; width: 100%;'>
                                        <td style='margin: 0; padding: 0; width: 100%;'>
                                            <p>Total Cost: ₱" . number_format($row['duration'] * $row['ratePerHour'], 2) . "</p>
                                        </td>
                                    </tr>

                

              </tbody>
            </table>
          </td>
        </tr>

        <tr style = 'width: 40%; display: flex; justify-content: center;  flex: center; text-align: center; '>
          <td style = 'width: 100%;' >
           
              <table style = 'width: 100%;'>
              <tbody style = 'width: 100%; '>
                <tr style = 'width: 100%; '> 
                  <td style = 'width: 100%;'>
                    <p class = 'bio'>Status:</p style = 'width: 100%;'>
                  </td>
                </tr> 

                <tr>
                  <td>
                    <p class = 'bio'>" . $row['status'] ."</p>
                  </td>
                </tr> 
              </tbody>
              </table>
            
          </td>
         
        </tr>

        <tr style = 'width: 20%;  display: flex; justify-content: center;  flex: center; text-align: center; '>
          <td style = 'width: 100%;' >
           
              <table style = 'width: 100%;'>
              <tbody style = 'width: 100%; '>
                <tr style = 'width: 100%; '> 
                  <td style = 'width: 100%;'>";
                  if (!$reviewExists) {
                    echo "<button class='btn btn-outline-custom1' data-toggle='modal' data-target='#detailsModal_$sessionID'>Rate</button>";
                }
        
echo "                 

                    </td>
                </tr> 
                                            
                <tr style='width: 100%; '> 
                  <td style='width: 100%;'>
                      <div></div>
                  </td>
                </tr> 
                
                <tr>
                  <td>";

                  if ($reviewExists) {
                    echo "<button class='btn btn-outline-custom2' data-toggle='modal' data-target='#detailsModal2_$sessionID'>View Rating</button>";
                  }

echo "                      
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>


  </div>
  </div>
  </div>


  ";


    


  echo "
<div class='modal fade' id='detailsModal_$sessionID' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title'>Rate Your Tutor</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <div class='modal-body'>
                <form method='post'>
                    <input type='hidden' name='sessionID' value='$sessionID'>

                    <p>Way of Teaching</p>
                    <input type='hidden' name='studentID' value='$studentID'> <!-- Add this line -->
                    <div class='rate'>
                        <input type='radio' id='star5_$sessionID' name='way' value='5' />
                        <label for='star5_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='star4_$sessionID' name='way' value='4' />
                        <label for='star4_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='star3_$sessionID' name='way' value='3' />
                        <label for='star3_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='star2_$sessionID' name='way' value='2' />
                        <label for='star2_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='star1_$sessionID' name='way' value='1' />
                        <label for='star1_$sessionID' class='star'>&#9733;</label>
                    </div>

                    <br>

                    <p>Communication Skills</p>
                    <input type='hidden' name='studentID' value='$studentID'> <!-- Add this line -->
                    <div class='rate'>
                        <input type='radio' id='cstar5_$sessionID' name='communication' value='5' />
                        <label for='cstar5_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='cstar4_$sessionID' name='communication' value='4' />
                        <label for='cstar4_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='cstar3_$sessionID' name='communication' value='3' />
                        <label for='cstar3_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='cstar2_$sessionID' name='communication' value='2' />
                        <label for='cstar2_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='cstar1_$sessionID' name='communication' value='1' />
                        <label for='cstar1_$sessionID' class='star'>&#9733;</label>
                    </div>

                     <br>

                    <p>Knowledge & Expertise</p>
                    <input type='hidden' name='studentID' value='$studentID'> <!-- Add this line -->
                    <div class='rate'>
                        <input type='radio' id='kstar5_$sessionID' name='knowledge' value='5' />
                        <label for='kstar5_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='kstar4_$sessionID' name='knowledge' value='4' />
                        <label for='kstar4_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='kstar3_$sessionID' name='knowledge' value='3' />
                        <label for='kstar3_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='kstar2_$sessionID' name='knowledge' value='2' />
                        <label for='kstar2_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='kstar1_$sessionID' name='knowledge' value='1' />
                        <label for='kstar1_$sessionID' class='star'>&#9733;</label>
                    </div>

                     <br>

                    <p>Engagement</p>
                    <input type='hidden' name='studentID' value='$studentID'> <!-- Add this line -->
                    <div class='rate'>
                        <input type='radio' id='estar5_$sessionID' name='engagement' value='5' />
                        <label for='estar5_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='estar4_$sessionID' name='engagement' value='4' />
                        <label for='estar4_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='estar3_$sessionID' name='engagement' value='3' />
                        <label for='estar3_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='estar2_$sessionID' name='engagement' value='2' />
                        <label for='estar2_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='estar1_$sessionID' name='engagement' value='1' />
                        <label for='estar1_$sessionID' class='star'>&#9733;</label>
                    </div>

                     <br>

                    <p>Time Management</p>
                    <input type='hidden' name='studentID' value='$studentID'> <!-- Add this line -->
                    <div class='rate'>
                        <input type='radio' id='tstar5_$sessionID' name='time' value='5' />
                        <label for='tstar5_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='tstar4_$sessionID' name='time' value='4' />
                        <label for='tstar4_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='tstar3_$sessionID' name='time' value='3' />
                        <label for='tstar3_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='tstar2_$sessionID' name='time' value='2' />
                        <label for='tstar2_$sessionID' class='star'>&#9733;</label>
                        <input type='radio' id='tstar1_$sessionID' name='time' value='1' />
                        <label for='tstar1_$sessionID' class='star'>&#9733;</label>
                    </div>

                     <br>

                    <textarea name='comment' placeholder='Leave a comment...' class='form-control' rows='3'></textarea>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                        <button type='submit' name='submitReview' class='btn btn-primary'>Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>";






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
                    <p style='font-weight: bold; font-size: 15px; display: flex; justify-content: start; margin: 0; color: #0F422A'>" . htmlspecialchars($row['tutorFullName']) . "</p>
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
                    <p style='font-weight: bold; font-size: 15px; display: flex; justify-content: start; margin:0; color: #0F422A'>Comment:</p>
                  </td>
                </tr>

                <tr>
                  <td>
                    <p style='font-size: 10px; display: flex; justify-content: start; color: #0F422A; text-align: justify;'> ". htmlspecialchars($comment) . "</p>                   
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