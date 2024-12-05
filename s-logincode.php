<?php
session_start();
include('connection/dbconfig.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

if (isset($_POST['login_button'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['email'] = $email;

    // Retrieve tutor details from the database based on email
    $query = "SELECT tutorID, firstname, lastname, email, password FROM tutor WHERE email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify the hashed password
        if (password_verify($password, $row['password'])) {
            // Authentication successful
            $_SESSION['authentication'] = true;
            $_SESSION['auth_tutor'] = [
                'tutor_id' => $row['tutorID'],
                'tutor_fullname' => $row['firstname'] . ' ' . $row['lastname'],
                'tutor_email' => $row['email'],
            ];

            $_SESSION['message'] = "You are Logged In Successfully";
            header("Location: t-index.php");
            exit(0);
        }
    }

    // Authentication failed
    $_SESSION['message'] = "Invalid Email or Password";
    header("Location: t-login.php");
    exit(0);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tutor Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/T-login.css">
</head>

<body>

    <div class="background-message">
        <a href="t-registration.php" class="btn btn-primary1">Sign Up</a>
        <p class="text-center">Don't have an account?</p>
    </div>

    <div class="registration-form">
        <div class="card">
            <div class="card-header">
                <h1>TUTOR SIGN IN</h1>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="message-container">
                        <h4 class="alert alert-warning"><?= $_SESSION['message'] ?></h4>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <form action="t-login.php" method="POST" class="body-reg">
                    <div class="mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" required class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" required class="form-control">
                    </div>

                    <div class="mb-3 sign-up-buttons">
                        <a href="land.php" class="nope">I DON'T WANT TO SIGN IN</a>
                        <input type="submit" name="login_button" class="btn btn-primary logbtn" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
