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
  margin-left: 1%;
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
  top: 65%;
  left: 19%;
  margin-left: 1px;
  margin-right: 0px;
  font-size: 15px;
  width: 58%;
  color: #666; /* Grey font color */
  font-style: italic; /* Italic font */
}
.btn-outline-success{
  color: #0F422A;
  background-color: #ffffff;
  border-color: #0F422A;
  font-weight: bold;
  letter-spacing: 0.05em;

  bottom:10%;
  left:79%;
  width:200px;
  height:40px;
  position: absolute;
  z-index: 2;

}
.btn-outline-secondary{
  background-color: #ffffff;
  border-color: #0F422A;
  font-weight: bold;
  letter-spacing: 0.05em;
  bottom:35%;
  left:79%;
  width:200px;
  height:40px;
  position: absolute;
  z-index: 2;
  color:#0F422A;
}
.rate{
  bottom:59%;
  left:88%;
  width:200px;
  height:40px;
  position: absolute;
  z-index: 2;
  font-size: 23px;
  font-weight: 300px;

}

</style>";

// Get search query and day filter from GET request
$searchQuery = isset($_GET['subjectSearch']) ? $_GET['subjectSearch'] : '';
$dayOfWeek = isset($_GET['dayOfWeek']) ? $_GET['dayOfWeek'] : '';

// Base SQL query with JOIN
$sql = "
    SELECT 
        t.tutorID, t.firstName, t.lastName, t.degreeProgram, t.year, t.profilePicture, 
        t.subjectExpertise, t.teachingMode, t.ratePerHour, t.bio, a.day_of_week, a.start_time, a.end_time
    FROM tutor AS t
    INNER JOIN tutorAvailability AS a ON t.tutorID = a.tutor_id
    WHERE t.approvalStatus = 'Approved' 
        AND t.subjectExpertise IS NOT NULL 
        AND t.teachingMode IS NOT NULL 
        AND t.ratePerHour IS NOT NULL 
        AND t.bio IS NOT NULL
";

// Apply subject search filter
if (!empty($searchQuery)) {
    $sql .= " AND t.subjectExpertise LIKE '%" . mysqli_real_escape_string($conn, $searchQuery) . "%'";
}

// Apply day filter
if (!empty($dayOfWeek)) {
    $sql .= " AND a.day_of_week = '" . mysqli_real_escape_string($conn, $dayOfWeek) . "'";
}


// Group by tutorID to ensure each tutor is listed once
$sql .= " GROUP BY t.tutorID";


// Execute the query
$result = mysqli_query($conn, $sql);

// Display tutors matching the filters
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='col-md-12 mb-3' style='margin-left: 10px; width:100% !important;'>";
            echo "<div class='card shadow custom-card' style='height: 200px; margin-top: 1%;'>";
            echo "<div class='card-body'>";
            echo "<h4 class='tutorName'>" . $row['firstName'] . " " . $row['lastName'] . "</h4>";
            echo "<p class='degreeProgram'><img src='icons/grad.png' class='icongrad'/>" . $row['degreeProgram'] . " - " . $row['year'] . "</p>";
            echo "<p class='card-text'><img src='" . $row['profilePicture'] . "' alt='Profile Picture' class='profile-picture'></p>";
            echo "<p class='mode'><img src='icons/mode.png' class='iconmode'/>" . $row['teachingMode'] . "</p>";
            echo "<p class='subj'><img src='icons/subj.png' class='iconsubj'/>" . $row['subjectExpertise'] . "</p>";
            echo "<p class='bio'>" . substr($row['bio'], 0, 155) . (strlen($row['bio']) > 75 ? '...' : '') . "</p>";
            echo "<p class='rate'>₱" . $row['ratePerHour'] . "/hr</p>";
           // echo "<p>Available on " . $row['day_of_week'] . " from " . $row['start_time'] . " to " . $row['end_time'] . "</p>";
            echo "<a href='s-sessionform.php?tutor=" . urlencode($row['firstName'] . " " . $row['lastName']) . "' class='btn btn-outline-success'>Book a Session</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No tutors found matching your criteria.</p>";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);


?>