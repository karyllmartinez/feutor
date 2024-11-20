<?php
session_start();
include('php/s-auth.php');
include('connection/dbconfig.php');

// Fetch student data
$studentID = $_SESSION['auth_user']['user_id'];
$query = "SELECT firstname, lastname, degreeProgram, year, email FROM student WHERE studentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
    <title>FEUTOR</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">

</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-green bg-green">
        <div class="container">
            <a class="navbar-brand" href="#">FEUTOR</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="s-index.php">Find a Tutor</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="appointmentsDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Appointments
                        </a>
                        <div class="dropdown-menu" aria-labelledby="appointmentsDropdown">
                            <a class="dropdown-item" href="s-pending.php">Pending</a>
                            <a class="dropdown-item" href="s-waitingforpayment.php">Waiting for Payment</a>
                            <a class="dropdown-item" href="s-approved.php">Accepted</a>
                            <a class="dropdown-item" href="s-declined.php">Declined</a>
                            <a class="dropdown-item" href="s-finished.php">Finished</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Messages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Notifications</a>
                    </li>
                    <li class="nav-item dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $user_firstname; ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="s-profile.php">Edit Profile</a>
                            <a class="dropdown-item" href="s-logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4>Edit Your Profile</h4>
                    </div>
                    <div class="card-body">
                        <!-- Displaying Student Data -->
                        <!-- Profile Picture -->
                        <div class="mb-4 text-center">
                            <img src="<?php echo !empty($student['profile_picture']) ? 'uploads/' . $student['profile_picture'] : 'icons/default.png'; ?>"
                                alt="Profile Picture" class="rounded-circle" width="150" height="150">
                        </div>

                        <!-- Full Name -->
                        <h3 class="mb-2"><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></h3>

                        <!-- Contact Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Email:</strong> <?php echo $student['email']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Degree Program:</strong> <?php echo $student['degreeProgram']; ?></p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p><strong>Year:</strong> <?php echo $student['year']; ?></p>
                        </div>

                        <!-- Password Update Form -->
                        <form action="s-profilecode.php" method="POST" onsubmit="return validatePasswords()">
                            <div class="mb-3">
                                <label>Enter New Password</label>
                                <input type="password" id="password" name="password" placeholder="Enter New Password"
                                    class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password"
                                    placeholder="Confirm New Password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <span id="error_message" style="color: red; display: none;">Passwords do not
                                    match.</span>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="save_changes" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>

                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-info"><?php echo $_SESSION['message'];
                            unset($_SESSION['message']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Password Validation Script -->
    <script>
        function validatePasswords() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var errorMessage = document.getElementById("error_message");

            if (password !== confirmPassword) {
                errorMessage.style.display = "block"; // Show the error message
                return false; // Prevent form submission
            } else {
                errorMessage.style.display = "none"; // Hide the error message
                return true; // Allow form submission
            }
        }
    </script>

</body>

</html>