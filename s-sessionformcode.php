<?php
session_start();
include('connection/dbconfig.php');

// Check if the user is logged in
if (!isset($_SESSION['authentication'])) {
    header("Location: s-login.php");
    exit();
}

// Retrieve studentID from the session
$studentID = $_SESSION['auth_user']['user_id'];

// Get session form data
$tutorID = $_POST['tutorID'];
$sessionDate = $_POST['sessionDate'];
$startTime = $_POST['startTime'];
$endTime = $_POST['endTime'];
$duration = $_POST['duration'];
$subject = $_POST['subjectExpertise'];
$teachingMode = $_POST['teachingMode'];
$need = $_POST['need'];

// Insert session data into the session table
$query = "INSERT INTO session (tutorID, studentID, sessionDate, startTime, endTime, duration, subject, teachingMode, need, status) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iississsss", $tutorID, $studentID, $sessionDate, $startTime, $endTime, $duration, $subject, $teachingMode, $need, $status);

// Set status to Pending by default
$status = "Pending";

// Execute the prepared statement
if ($stmt->execute()) {
    // Session added successfully
    $_SESSION['message'] = "Session requested successfully.";

    // Insert notification
    $notificationMessage = "You have a pending session request from a student.";
    $notificationStatus = "unread"; // Correct the status to match ENUM values

    // Insert into notifications table
    $notificationQuery = "INSERT INTO notifications (tutorID, studentID, message, status, created_at) 
                      VALUES (?, ?, ?, ?, NOW())";
    $notificationStmt = $conn->prepare($notificationQuery);

    if ($notificationStmt === false) {
        // Handle error in prepare
        echo "Error in preparing notification statement: " . $conn->error;
        exit();
    }

    $notificationStmt->bind_param("iiss", $tutorID, $studentID, $notificationMessage, $notificationStatus);

    if ($notificationStmt->execute()) {
        // Successful execution
        header("Location: s-pending.php");
        exit();
    } else {
        // Error executing the statement
        echo "Error executing notification statement: " . $notificationStmt->error;
        exit();
    }

} else {
    echo "
    <div id='errorModal' style='
        position: fixed;
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        background-color: rgba(0,0,0,0.5); 
        display: flex; 
        justify-content: center; 
        align-items: center;
        z-index: 1000;
    '>
        <div style='
            background-color: white; 
            padding: 20px; 
            border-radius: 10px; 
            text-align: center; 
            width: 300px;
        '>
            <h3 style='margin: 0 0 10px;'>Error</h3>
            <p>Sorry, this date is already booked.</p>
            <button onclick='closeModal()' style='
                background-color: #007bff; 
                color: white; 
                border: none; 
                padding: 10px 20px; 
                border-radius: 5px; 
                cursor: pointer;
            '>Close</button>
        </div>
    </div>
    <script>
        function closeModal() {
            document.getElementById('errorModal').style.display = 'none';
            window.history.back();
        }
    </script>";
    exit();
}

// Close statement and connection
$stmt->close();
$notificationStmt->close();
$conn->close();

?>