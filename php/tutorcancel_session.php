<?php
session_start();

include('../connection/dbconfig.php'); // Include your database connection file

if (isset($_GET['sessionID']) && isset($_POST['cancelReason'])) {
    $sessionID = $_GET['sessionID'];
    $cancelReason = $_POST['cancelReason'];
    $tutorID = $_SESSION['auth_tutor']['tutor_id'];

    if ($conn) {
        // Debugging: Check values before running queries
        echo "sessionID: $sessionID, tutorID: $tutorID, cancelReason: $cancelReason<br>";

        // Check if the session exists and belongs to the tutor
        $sql_check = "SELECT * FROM session WHERE sessionID = ? AND tutorID = ?";
        $check_stmt = $conn->prepare($sql_check);
        $check_stmt->bind_param("ii", $sessionID, $tutorID);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Debugging: Print the session details
            $row = $result->fetch_assoc();
            echo "Current status: " . $row['status'] . ", Current remarks: " . $row['remarks'] . "<br>";

            // Perform the update
            $sql = "UPDATE session SET status = 'Cancelled by Tutor', remarks = ? WHERE sessionID = ? AND tutorID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $cancelReason, $sessionID, $tutorID);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: ../t-declined.php");
                exit();
            } else {
                echo "Failed to update the session status. Please check the current status and remarks.";
            }
        } else {
            echo "Session not found or does not belong to you.";
        }
    } else {
        echo "Failed to connect to the database.";
    }
} else {
    echo "Session ID or cancellation reason not provided.";
}
?>
