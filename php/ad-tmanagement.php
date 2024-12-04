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
           <button type='button' class='btn btn-danger open-modal-btn' data-tutorid='" . $row['tutorID'] . "'>Delete</button>
        </td>";
    echo "</tr>";
}
?>

<!-- Modal HTML (Custom Design) -->
<div class="custom-modal" id="deleteModal">
    <div class="custom-modal-content">
        <span class="close-btn">&times;</span>
        <p>Are you sure you want to delete this tutor?</p>
        <form method="POST" id="deleteForm">
            <input type="hidden" name="tutorID" id="tutorID">
            <div class="modal-buttons">
                <button type="submit" class="button" name="delete_btn">Delete</button>
                <button type="button" class="cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Modal Background */
.custom-modal {
    display: none; /* Hide modal by default */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
    align-items: center;
    justify-content: center;
    text-align: center;
    opacity: 0; /* Start with opacity 0 */
    animation: fadeIn 0.3s forwards; /* Animation for fade-in */
}

/* Modal Content Box */
.custom-modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transform: translateY(-50px); /* Start above the screen */
    opacity: 0; /* Start with opacity 0 */
    animation: slideIn 0.3s forwards, fadeInContent 0.3s forwards; /* Animation for slide-in and fade-in */
}

/* Fade-in Animation */
@keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

/* Slide-in Animation for Modal Content */
@keyframes slideIn {
    0% {
        transform: translateY(-50px);
    }
    100% {
        transform: translateY(0);
    }
}

/* Fade-in Animation for Modal Content */
@keyframes fadeInContent {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

/* Close button */
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    font-weight: bold;
    color: black;
    cursor: pointer;
    visibility: hidden;
}

/* Modal Buttons */
.modal-buttons {
    margin-top: 20px;
}

/* "Delete" Button */
.button {
    background-color: red;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.button:hover {
    background-color: darkred;
}

/* "Cancel" Button */
.cancel-btn {
    background-color: gray;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 10px;
}

.cancel-btn:hover {
    background-color: darkgray;
}

/* Close modal animation when closed */
.custom-modal.close {
    animation: fadeOut 0.3s forwards; /* Fade-out animation for the background */
}

/* Slide-out animation for modal content */
.custom-modal-content.close {
    animation: slideOut 0.3s forwards, fadeOutContent 0.3s forwards; /* Slide-out and fade-out */
}

/* Fade-out Animation for Modal */
@keyframes fadeOut {
    0% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

/* Slide-out Animation for Modal Content */
@keyframes slideOut {
    0% {
        transform: translateY(0);
    }
    100% {
        transform: translateY(-50px);
    }
}

/* Fade-out Animation for Modal Content */
@keyframes fadeOutContent {
    0% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

</style>

<script>
// Open modal when delete button is clicked
document.querySelectorAll('.open-modal-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Get tutorID from data attribute
        var tutorID = this.getAttribute('data-tutorid');
        
        // Set tutorID to hidden input
        document.getElementById('tutorID').value = tutorID;
        
        // Show the modal
        document.getElementById('deleteModal').style.display = 'flex';
    });
});

// Close modal on clicking the close button
document.querySelector('.close-btn').addEventListener('click', function() {
    document.getElementById('deleteModal').style.display = 'none';
});

// Close modal on clicking the Cancel button
document.querySelector('.cancel-btn').addEventListener('click', function() {
    document.getElementById('deleteModal').style.display = 'none';
});
</script>
