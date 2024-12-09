<?php 

echo "







";

// Get the email of the currently logged-in tutor user from the session
$email = $_SESSION['auth_tutor']['tutor_email'];

// Fetch the row from the tutor table for the current tutor user
$query = "SELECT * FROM `tutor` WHERE `email` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();




// Check if the query was successful
if ($result && $result->num_rows > 0) {
    // Output the data
    $row = $result->fetch_assoc();
    $approvalStatus = $row['approvalStatus'];

    // Display status based on approval status
    if ($approvalStatus == 'Pending') {
        $status_message = '
<div class="wrapper">
    <div class="container-box">
      <!-- Top box for the image and message -->
      <div class="top-box">
        <img src="icons/appstatA.png" alt="Picture" class="top-box-img">
        <p class="top-box-boldmessage">Thank you for joining <br> FEUTOR!</p>
        <p class="top-box-message">Your account is under review. We are processing your registration, and you can expect to be notified within the next 2 days once your account has been activated. Thank you for your patience!</p>
      </div>

      <!-- Bottom box with green background -->
      <div class="bottom-box"></div>
    </div>
</div>
';

    } elseif ($approvalStatus == 'Approved') {
        header("Location: t-dashboard.php");
        unset($_SESSION['message']); // Clear the message session after redirection to remove the "You are Logged In Successfully" // BAGO ETO
        exit();
    } elseif ($approvalStatus == 'Declined') {
        $status_message = '   <!-- Wrapper for centering the container-box -->
  <div class="wrapper">
    <div class="container-box">
      <!-- Top box for the image and message -->
      <div class="top-box">
        <img src="icons/appstatD.png" alt="Picture" class="top-box-img">
        <p class="top-box-boldmessage">Registration Status Update</p>
        <p class="top-box-message">We regret to inform you that, after careful review, we are unable to proceed with your registration due to factors related to the information provided or our eligibility criteria. You are welcome to reapply for the next semester.
                                    <br><br>We appreciate your interest and understanding.</p>
      </div>

      <!-- Bottom box with green background -->
      <div class="bottom-box"></div>
    </div>
  </div>';
    } else {
        $status_message = "Unknown approval status";
    }
} else {
    $status_message = "No records found for the logged-in user.";
}
// Close database connection
mysqli_close($conn);

?>