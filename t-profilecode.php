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
                    $message = "Profile updated successfully";
                    $_SESSION['auth_tutor']['tutor_fullname'] = $firstName . ' ' . $lastName;
                    header("Location: t-profile.php");
                    exit();
                } else {
                    $message = "Error updating profile: " . $stmt->error;
                }
            } else {
                $message = 'Error occurred while uploading the file to the destination directory.';
            }
        } else {
            $message = 'Upload failed as the file type is not acceptable. The allowed file types are: ' . implode(', ', $allowedfileExtensions);
        }
    } else {
        $message = 'Error occurred while uploading the file.';
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
        header("Location: t-profile.php");
        exit();
    } else {
        // Failure: Handle error (you can display an error message if needed)
        echo "Error removing availability.";
    }
}


// Handle availability form submission
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

// Store any messages in session for display
$_SESSION['message'] = $message;
header("Location: t-profile.php");
exit();
?>