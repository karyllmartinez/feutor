<?php
session_start();
include('connection/dbconfig.php');

// Check if the user has submitted the form
if (isset($_POST['save_changes'])) {
    $studentID = $_SESSION['auth_user']['user_id'];
    $password = $_POST['password']; // Capture the new password input
    $confirmPassword = $_POST['confirm_password']; // Capture the confirm password input

    // Check if both passwords match
    if ($password !== $confirmPassword) {
        $_SESSION['message'] = "Passwords do not match. Please try again.";
        header("Location: s-profile.php");
        exit();
    }

    // Prepare query if passwords match
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE student SET password=? WHERE studentID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $hashedPassword, $studentID);
    } else {
        $_SESSION['message'] = "No password provided to update.";
        header("Location: s-profile.php");
        exit();
    }

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['message'] = "Password updated successfully!";
        header("Location: s-profile.php");
        exit();
    } else {
        $_SESSION['message'] = "Failed to update password.";
        header("Location: s-profile.php");
        exit();
    }
}
?>
