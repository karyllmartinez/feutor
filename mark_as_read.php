<?php
session_start();
include('connection/dbconfig.php'); // Include your database connection file

// Check if the request is a POST request and contains the necessary parameters
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['notification_id']) && isset($_POST['action'])) {
    $notification_id = $_POST['notification_id'];
    $action = $_POST['action'];

    // Set 'read' or 'unread' based on the action
    $status = ($action === 'read') ? 'read' : 'unread';

    // Update the status of the notification in the database
    $query = "UPDATE notifications SET status = ? WHERE notificationID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $notification_id);
    
    if ($stmt->execute()) {
        echo "Notification marked as $status.";
    } else {
        echo "Error marking notification.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>


