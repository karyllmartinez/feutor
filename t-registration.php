<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('php/t-restrict.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/t-registration.css">
</head>

<body>
    <div class="background-message">
        <a href="t-login.php" class="btn btn-primary1">Sign In</a>
    </div>
    <div class="registration-form">
        <div class="card">
            <div class="card-header">
                <h1>SIGN UP AS STUDENT TUTOR</h1>
            </div>
            <div class="card-body">

                <div class="card-body">
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="message-container">
                            <h4 class="alert alert-warning"><?= $_SESSION['message'] ?></h4>
                        </div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>

                    <form action="t-registercode.php" method="POST" class="body-reg" onsubmit="return validateForm()">

                        <div class="mb-3">
                            <label>First Name</label>
                            <input type="text" name="firstName" required placeholder="Enter First Name"
                                class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Last Name</label>
                            <input type="text" name="lastName" required placeholder="Enter Last Name"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Email Address</label>
                            <input type="email" name="email" required placeholder="Enter Email Address"
                                class="form-control" pattern="^R\d{10}@feuroosevelt\.edu\.ph$"
                                title="Please enter an email address ending with @feuroosevelt.edu.ph (e.g., R0000000000@student.edu.ph)">
                        </div>

                        <div class="mb-3">
                            <label>Degree Program / Level of Highschool</label>
                            <select name="degreeProgram" required class="form-control">
                                <option value="">Select Degree Program</option>
                                <option value="BS in Information Technology">BS in Information Technology</option>
                                <option value="BS in Business Administration">BS in Business Administration</option>
                                <option value="High School">High School</option>
                                <option value="Senior High School">Senior High School</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Year</label>
                            <input type="text" name="year" required placeholder="Enter Year" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Subject Expertise</label>
                            <select name="subjectExpertise[]" required class="form-select" multiple>
                                <option value="" disabled>Select Subject Expertise</option>
                                <?php include('php/t-subj.php'); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>G-drive Link
                                <i class="fas fa-info-circle" data-bs-html="true" data-bs-toggle="tooltip"
                                    data-bs-placement="right"
                                    title="PLEASE UPLOAD NECESSARY DOCUMENTS: Resume, Certification of Enrollment, Transcript of Records/Grade Slip, Academic Certificates & Parental Consent Form.">
                                </i>
                            </label>
                            <input type="text" name="gdriveLink" placeholder="Provide G-drive Link"
                                class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" required placeholder="Enter Password"
                                class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" required placeholder="Confirm Password"
                                class="form-control">
                        </div>

                        <!-- Checkbox and Terms -->
                        <div class="mb-3 checkbox-container">
                            <input type="checkbox" name="terms" id="terms" required>
                            <label for="terms">
                                Please read the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms
                                    and Conditions</a> before checking this box to agree.
                            </label>
                        </div>

                        <div class="mb-3 sign-up-buttons">
                            <a href="land.php" class=" nope">I DON'T WANT TO SIGN UP</a>
                            <button type="submit" name="register_btn" class="btn btn-primary regbtn">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


        <!-- Modal for Terms and Conditions -->
        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>1. Agreement to Terms</h6>
                        <p>By registering as a student tutor, you agree to these terms and conditions. If you do not
                            agree, please do not proceed with the registration.</p>

                        <h6>2. Eligibility</h6>
                        <p>Only enrolled students of FEU Roosevelt Marikina are eligible to register as tutors. You must
                            provide valid proof of enrollment or certification from the university.</p>

                        <h6>3. Profile Requirements</h6>
                        <p>All registered student tutors must provide complete and truthful information regarding their
                            academic background, contact details, and expertise. Any false or misleading information
                            will result in immediate disqualification from the program.</p>

                        <h6>4. Session Guidelines</h6>
                        <p>Registered tutors are responsible for ensuring timely attendance at tutoring sessions and are
                            expected to conduct the sessions in a professional manner. Tutors should prepare
                            appropriately for each session and maintain a positive, respectful environment.</p>

                        <h6>5. Payments and Fees</h6>
                        <p>Tutors will be compensated according to the rates specified in their profiles. Payments will
                            be handled by the admin through PayPal, and any transaction fees or associated charges are
                            the responsibility of the admin.</p>


                        <h6>6. Tutor-Student Interaction</h6>
                        <p>Both tutors and students must maintain respectful and appropriate communication at all times.
                            Inappropriate behavior, including harassment or discrimination, will not be tolerated and
                            may result in removal from the platform.</p>

                        <h6>7. Confidentiality</h6>
                        <p>Both tutors and students must respect the confidentiality of any personal or academic
                            information shared during sessions. No data shared during the sessions should be used for
                            purposes other than tutoring.</p>

                        <h6>8. Termination of Agreement</h6>
                        <p>Either party may terminate the tutoring agreement at any time with reasonable notice. FEU
                            Roosevelt Marikina reserves the right to suspend or terminate a tutor's account for
                            violation of any terms outlined in these Terms and Conditions.</p>

                        <h6>9. Liability</h6>
                        <p>FEU Roosevelt Marikina will not be held liable for any disputes, damages, or losses incurred
                            as a result of the tutoring sessions. Tutors are responsible for their own actions, and
                            students are responsible for ensuring that they receive the tutoring they require.</p>

                        <h6>10. Modifications to Terms</h6>
                        <p>FEU Roosevelt Marikina reserves the right to modify these Terms and Conditions at any time.
                            All users will be notified of any changes and will be required to agree to the updated terms
                            in order to continue using the platform.</p>

                        <h6>11. Agreement to All Terms</h6>
                        <p>By clicking the 'Checkbox', you acknowledge that you have read, understood, and agree to
                            these Terms and Conditions.</p>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function () {
                // Initialize Select2 for Subject Expertise
                $('select[name="subjectExpertise[]"]').select2();

                // Email Validation Logic
                const emailInput = document.querySelector('input[name="email"]');

                if (emailInput) {
                    emailInput.addEventListener('input', function () {
                        // Check if the email matches the required pattern
                        const pattern = /^R\d{10}@feuroosevelt\.edu\.ph$/;
                        if (!pattern.test(this.value)) {
                            this.setCustomValidity('Please enter a complete school email address ending with @feuroosevelt.edu.ph');
                        } else {
                            // Clear the custom validity message once it is valid
                            this.setCustomValidity('');
                        }

                        // Force the browser to display the updated validation message
                        this.reportValidity();
                    });
                }
            });


            // Form validation to ensure the checkbox is checked
            function validateForm() {
                const termsCheckbox = document.getElementById('terms');
                if (!termsCheckbox.checked) {
                    alert('You must agree to the Terms and Conditions to proceed.');
                    return false;
                }
                return true;
            }
        </script>

<script>
    $(document).ready(function() {
        $('select[name="subjectExpertise[]"]').select2();
    });
</script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/tooltip.js"></script>

        </script>

</body>

</html>