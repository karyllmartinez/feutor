<?php

include('connection/dbconfig.php');


/// Function to handle tutor deletion
function deleteTutor($tutorID) {
    global $conn;
    $delete_query = "DELETE FROM tutor WHERE tutorID=?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "s", $tutorID);
    $delete_result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($delete_result) {
        return true; // Deletion successful
    } else {
        return false; // Deletion failed
    }
}

// Handle tutor deletion when form is submitted
if (isset($_POST['delete_btn'])) {
    $tutorIDToDelete = $_POST['tutorID'];
    if (deleteTutor($tutorIDToDelete)) {
        // Tutor deletion successful
        header("Location: tutormanagement.php"); // Redirect to refresh the page
    } else {
        // Tutor deletion failed
        echo "Error deleting tutor.";
    }
}


// Fetch approved tutors
$approved_query = "SELECT * FROM tutor WHERE approvalStatus='Approved'";
$approved_result = mysqli_query($conn, $approved_query);

// Fetch declined tutors
$declined_query = "SELECT * FROM tutor WHERE approvalStatus='Declined'";
$declined_result = mysqli_query($conn, $declined_query);

while ($row = mysqli_fetch_assoc($approved_result)) {
    echo "<tr>";
    echo "<td>" . $row['tutorID'] . "</td>";
    echo "<td>" . $row['firstName'] . "</td>";
    echo "<td>" . $row['lastName'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['degreeProgram'] . "</td>";
    echo "<td>" . $row['year'] . "</td>";
    echo "<td>" . $row['gdriveLink'] . "</td>";
    echo "<td>" . $row['subjectExpertise'] . "</td>";
    echo "<td>" . $row['ratePerHour'] . "</td>";
    echo "<td>
            <form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this tutor?\")'>
                <input type='hidden' name='tutorID' value='" . $row['tutorID'] . "'>
                <button type='submit' class='btn btn-danger' name='delete_btn'>Delete</button>
            </form>
        </td>";
    // Add more table cells as needed
   
    echo "</tr>";
}

// Output declined tutors in a separate table
echo "</tbody></table><h2>Declined Student Tutors</h2><table class='table'><thead><tr><th>Tutor ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Degree Program / Level of Highschool</th><th>Year</th><th>Google Drive Link</th><th>Action</th></tr></thead><tbody>";

while ($row = mysqli_fetch_assoc($declined_result)) {
    echo "<tr>";
    echo "<td>" . $row['tutorID'] . "</td>";
    echo "<td>" . $row['firstName'] . "</td>";
    echo "<td>" . $row['lastName'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['degreeProgram'] . "</td>";
    echo "<td>" . $row['year'] . "</td>";
    echo "<td>" . $row['gdriveLink'] . "</td>";
    echo "<td>
            <form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this tutor?\")'>
                <input type='hidden' name='tutorID' value='" . $row['tutorID'] . "'>
                <button type='submit' class='btn btn-danger' name='delete_btn'>Delete</button>
            </form>
        </td>";
    // Add more table cells as needed
   
    echo "</tr>";
}


?>
