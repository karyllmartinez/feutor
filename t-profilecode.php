<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('connection/dbconfig.php');
$message = '';

// Create directory for uploads if it doesn't exist
$uploadFileDir = 'img/';
if (!file_exists($uploadFileDir)) {
    mkdir($uploadFileDir, 0777, true);
}

// Handle profile update
if (isset($_POST['register_btn'])) {
    // Handle profile picture upload
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profilePicture']['tmp_name'];
        $fileName = $_FILES['profilePicture']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'jpeg', 'png');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $teachingMode = $_POST['teachingMode'];
               
                $bio = $_POST['bio'];
                $tutorID = $_SESSION['auth_tutor']['tutor_id'];

                $query = "UPDATE tutor SET profilePicture=?, firstName=?, lastName=?, teachingMode=?, bio=? WHERE tutorID=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssssi", $dest_path, $firstName, $lastName, $teachingMode, $bio, $tutorID);

                if ($stmt->execute()) {
                    /* DATI
                    $_SESSION['auth_tutor']['tutor_fullname'] = $firstName . ' ' . $lastName;
                    $message = "Profile updated successfully";
                    header("Location: t-profile.php");
                    exit();
                    */

                    //BAGO ETO
                    $_SESSION['auth_tutor']['tutor_fullname'] = $firstName . ' ' . $lastName;
                    $message = "Profile updated successfully.";
                    $messageType = "success"; // success message
                    //HANGGANG DITO LANG
                } else {
                    $message = "Error updating profile: " . $stmt->error;
                    $messageType = "error"; // success message

                }
            } else {
                $message = 'Error occurred while uploading the file to the destination directory.';
                $messageType = "error"; // success message
            }
        } else {
            $message = 'Upload failed as the file type is not acceptable. The allowed file types are: ' . implode(', ', $allowedfileExtensions);
            $messageType = "error"; // success message
        }
    
    } else {
        $message = 'Error: No file uploaded. Please try again.';
        $messageType = "error"; // success message
    }
}

if (isset($_POST['delete_availability'])) {
    $availability_id = $_POST['availability_id'];

    // Prepare SQL to delete the availability record
    $deleteQuery = "DELETE FROM tutorAvailability WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $availability_id); 
    
    if ($deleteStmt->execute()) {
        // Success: Redirect to the same page (or any other page you prefer)
        $message =  "Availability removed successfully."; //BAGO ETO ($_SESSION['message'] = "Availability removed successfully.")
        $messageType = "success"; // success message // BAGO DIN ETO $messageType = "success";
        header("Location: t-profile.php");
    } else {
        // Failure: Handle error (you can display an error message if needed)
        echo "Error removing availability.";
    }
}

/*DATIIII ETOOO NAG DAGDAG LANG PERO ETO PADIN MAY INADD LANG sa knila
// Handle availability form submission
<!--
if (isset($_POST['setAvailability'])) {
    $availableDay = $_POST['availableDays']; // Single day selected from the dropdown
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $tutorID = $_SESSION['auth_tutor']['tutor_id'];

    // Insert the selected day availability
$query = "INSERT INTO tutorAvailability (tutor_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("isss", $tutorID, $availableDay, $startTime, $endTime);
$stmt->execute();


    $message = "Availability saved successfully.";
    $_SESSION['message'] = $message;
    header("Location: t-profile.php");
    exit();
}
*/

if (isset($_POST['setAvailability'])) {
    $availableDay = $_POST['availableDays']; // Single day selected from the dropdown
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $tutorID = $_SESSION['auth_tutor']['tutor_id'];

    // Check if the same available day already exists for the tutor
    $checkQuery = "SELECT * FROM tutorAvailability WHERE tutor_id = ? AND day_of_week = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("is", $tutorID, $availableDay);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Duplicate day found
        $_SESSION['message'] = "Error: Cannot have duplicate available days.";
        $_SESSION['message_type'] = "error"; // Set message type to 'error'
        header("Location: t-profile.php");
        exit(); // Ensure the script stops here
    } else {
        // Insert the selected day availability
        $query = "INSERT INTO tutorAvailability (tutor_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $tutorID, $availableDay, $startTime, $endTime);

        if ($stmt->execute()) {
            $message = "Availability saved successfully.";
            $_SESSION['message'] = $message;
            $messageType = "success"; // success message
        } else {
            $_SESSION['message'] = "Error saving availability.";
            $messageType = "error"; // success message
        }

        header("Location: t-profile.php");
        exit();
    }
}

// Store any messages in session for display
$_SESSION['message'] = $message;
$_SESSION['message_type'] = $messageType; // Store message type //BAGO ETO $_SESSION['message_type'] = $messageType;
header("Location: t-profile.php");
exit();
?>