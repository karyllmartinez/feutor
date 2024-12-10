<?php
session_start();

include('../connection/dbconfig.php'); // Include your database connection file

// Check if the session ID and cancellation reason are provided
if (isset($_GET['sessionID']) && isset($_POST['cancelReason'])) {
    // Get the session ID from the URL
    $sessionID = $_GET['sessionID'];
    
    // Get the cancellation reason from the form
    $cancelReason = $_POST['cancelReason'];
    
    // Retrieve logged-in student's studentID
    $studentID = $_SESSION['auth_user']['user_id'];

    // Prepare and execute a query to update session status and cancellation reason
    $sql = "UPDATE session SET status = 'Cancelled by Student', remarks = ? WHERE sessionID = ? AND studentID = ?";
    
    if ($conn) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $cancelReason, $sessionID, $studentID);  // 's' for string, 'ii' for integers
        $stmt->execute();
        
        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            // Redirect to tutor's dashboard or confirmation page
            header("Location: ../s-declined.php");
            exit();
        } else {
            // Display an error message if the session ID is not found or does not belong to the student
            echo "Session not found or does not belong to you.";
        }
    } else {
        // Handle database connection errors
        echo "Failed to connect to the database.";
    }
} else {
    // Display an error message if the session ID or reason is not provided
    echo "Session ID or cancellation reason not provided.";
}
?>
