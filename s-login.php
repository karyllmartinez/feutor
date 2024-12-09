<?php
session_start();

include('connection/dbconfig.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

if (isset($_SESSION['user_id'])) {
    header("Location: s-index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $_SESSION['email'] = $email;

    // Prepared statement to prevent SQL injection
    $sql = "SELECT studentID, firstname, lastname, email, password FROM student WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data && password_verify($password, $data['password'])) {
        // Generate OTP
        $otp = rand(100000, 999999);
        $otp_expiry = date("Y-m-d H:i:s", strtotime("+3 minutes"));

        // Send OTP via email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = 'moshtekan@gmail.com'; // Your email address
            $mail->Password = 'hgiv uxcl zevl drlx'; // Your app password
            $mail->setFrom('example@gmail.com', 'Two Factor Authentication');
            $mail->addAddress($email, $data['firstname'] . ' ' . $data['lastname']);
            $mail->Subject = "Your OTP for Login";
            $mail->Body = "Your OTP is: $otp";
            $mail->send();
        } catch (Exception $e) {
            $_SESSION['message'] = "Failed to send OTP. Please try again.";
            header("Location: s-login.php");
            exit();
        }

        // Store OTP and expiry in the database
        $update_sql = "UPDATE student SET otp=?, otp_expiry=? WHERE studentID=?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssi", $otp, $otp_expiry, $data['studentID']);
        $update_stmt->execute();

        // Store temporary user data in session
        $_SESSION['temp_user'] = [
            'studentID' => $data['studentID'],
            'otp' => $otp
        ];

        header("Location: otp_verification.php");
        exit();
    } else {
        $_SESSION['message'] = "Invalid Email or Password. Please try again.";
        header("Location: s-login.php");
        exit();
    }
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login System in PHP MySQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/s-login.css">
    <style>

    </style>
</head>

<body>
<div class="box">
        <span class="borderLine"></span>
        <form action="s-login.php" method="POST" class="body-reg">
            <h2>SIGN IN</h2>
            <div class="inputBox">
                <input type="email" name="email" required>
                <span>Email Address</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="password" name="password" required>
                <span>Password</span>
                <i></i>
            </div>

            <!-- Display error message if available -->
            <?php
            if (isset($_SESSION['message'])) {
                echo '<div style="color: red; font-size: 0.9em; margin-top: 10px;">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);  // Clear message after displaying
            }
            ?>

            <div class="links">
                <a href="land.php" class="nope">I DON'T WANT TO SIGN IN AS STUDENT</a>
            </div>
            <input type="submit" name="login" class="btn btn-primary logbtn" value="Submit" id="submit">
        </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>