<?php
session_start();


include('php/s-auth.php');
include('connection/dbconfig.php'); // Include your database connection file
include('php/tutorname.php'); // Include your database connection file



?>

<!DOCTYPE html>
<html>
<head>
  <title>FEUTOR</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <!-- CSS -->
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">

  <style>
    /* Style for the modal button */
#modalButton {
  background-color: #007bff; /* Primary button color */
  color: white; /* Text color */
  border: none; /* Remove border */
  padding: 10px 20px; /* Padding around the text */
  border-radius: 5px; /* Rounded corners */
  font-size: 16px; /* Adjust font size */
  cursor: pointer; /* Show pointer cursor */
  transition: background-color 0.3s ease; /* Smooth transition for hover effect */
}

/* Hover effect for the modal button */
#modalButton:hover {
  background-color: #0056b3; /* Darker shade of blue when hovered */
}

/* Disabled state styling for the button */
#modalButton:disabled {
  background-color: #cccccc; /* Grey background when disabled */
  color: #666666; /* Darker grey text */
  cursor: not-allowed; /* Show not-allowed cursor when disabled */
}

/* Optional: Additional focus state for accessibility */
#modalButton:focus {
  outline: none; /* Remove default focus outline */
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Add custom focus shadow */
}

  </style>

</head>

<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-green bg-green">
    <div class="container">
      <!-- Brand -->
      <a class="navbar-brand" href="#">FEUTOR</a>
      <!-- Toggler Button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Navigation Items -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="s-index.php">Find a Tutor</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="appointmentsDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
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
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              <?php echo $user_firstname; ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="userDropdown">
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
            <h4>Request a Session with <?php echo htmlspecialchars($tutorName); ?></h4>
          </div>
          <div class="card-body">
            <form method="post" action="s-sessionformcode.php" id="sessionForm">

              <input type="hidden" name="tutorID" value="<?php echo $tutorID; ?>">
              <div class="mb-3">
                <h2></h2>
              </div>
              <div class="mb-3">
                <label for="teachingMode">Lesson Type:</label>
                <?php include("php/s-form.php"); ?>
              </div>
              <div class="mb-3">
                <label for="subjectExpertise">Subject Expertise:</label>
                <?php if ($subjectCount === 1) { ?>
                  <input type="text" class="form-control" id="subjectExpertise" name="subjectExpertise"
                    value="<?php echo $subjectExpertise; ?>" readonly>
                <?php } else { ?>
                  <select class="form-control" id="subjectExpertise" name="subjectExpertise">
                    <?php foreach ($subjectArray as $subject) { ?>
                      <option value="<?php echo $subject; ?>"><?php echo $subject; ?></option>
                    <?php } ?>
                  </select>
                <?php } ?>
              </div>
             
              <!-- <div class="mb-3">
                            <label for="sessionDate">Select Date:</label>
                            <input type="date" class="form-control" id="sessionDate" name="sessionDate">
                        </div> -->

              <div class="mb-3">
                <label for="sessionDate">Select Date:</label>
                <input type="date" class="form-control" id="sessionDate" name="sessionDate" required>
              </div>


              <div class="mb-3">
                <label for="startTime">Start Time:</label>
                <input type="time" class="form-control" id="startTime" name="startTime" onchange="calculateDuration()">
              </div>
              <div class="mb-3">
                <label for="endTime">End Time:</label>
                <input type="time" class="form-control" id="endTime" name="endTime" onchange="calculateDuration()">
              </div>
              <div class="mb-3">
                <label for="duration">Duration (hours):</label>
                <input type="number" class="form-control" id="duration" name="duration" readonly>
              </div>
              <div class="mb-3">
                <label for="need">What do you need?</label>
                <textarea class="form-control" id="need" name="need" rows="4"></textarea>
              </div>
              <div class="mb-3">
                <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                <!-- This button now only opens the modal and doesn't submit the form -->
<button type="button" class="btn btn-outline-custom1" data-toggle="modal" data-target="#detailsModal" id="modalButton">Submit</button>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal with the actual submit button -->
<div class='modal fade' id='detailsModal' tabindex='-1' role='dialog' aria-hidden='true'>
  <div class='modal-dialog modal-dialog-centered' role='document'>
    <div class='modal-content'>
      <div class='modal-header'>
        <h5 class='modal-title' id='detailsModalLabel'>Terms and Conditions</h5>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body'>
      <div style="max-height: 400px; overflow-y: auto;">
          <p style="text-align: justify;"><strong>1. Agreement to Terms</strong></p>
          <p style="text-align: justify;">By booking an appointment, you agree to these terms. If you disagree, do not proceed with booking.</p>
          
          <p style="text-align: justify;"><strong>2. Eligibility and Booking Process</strong></p>
          <p style="text-align: justify;">Only registered students can book appointments. Limit of one appointment per student per day. Full payment required before tutor confirmation.</p>
          
          <p style="text-align: justify;"><strong>3. Attendance Requirements</strong></p>
          <p style="text-align: justify;">Arrive at the meeting place 10 minutes before the session starts. Late arrivals over 10 minutes may result in a canceled or shortened session.</p>
          
          <p style="text-align: justify;"><strong>4. Cancellations and Changes</strong></p>
          <p style="text-align: justify;">Appointments must be rescheduled at least 24 hours in advance. No cancellations or refunds are allowed after payment is completed.</p>
          
          <p style="text-align: justify;"><strong>5. Conduct and Feedback</strong></p>
          <p style="text-align: justify;">Maintain respect towards all participants during the session. Provide feedback after your session to help improve the service.</p>
          
          <p style="text-align: justify;"><strong>6. Data Privacy</strong></p>
          <p style="text-align: justify;">Personal information is collected solely for booking purposes and is kept confidential.</p>
          
          <p style="text-align: justify;"><strong>7. Liability</strong></p>
          <p style="text-align: justify;">We are not responsible for interruptions or availability issues of the booking service. Decisions and actions post-appointment are your responsibility.</p>
          
          <p style="text-align: justify;"><strong>No Sharing of Meeting Links</strong></p>
          <p style="text-align: justify;">Meeting links are for the sole use of the registered student. Sharing or distributing the meeting link to others is strictly prohibited. Any breach of this policy may result in immediate cancellation of the session and potential restriction from future bookings.</p>
          
          <p style="text-align: justify;"><strong>Modifications to Terms</strong></p>
          <p style="text-align: justify;">These terms may be updated periodically, and continued use of the booking system after updates constitutes acceptance.</p>
          
          <p style="text-align: justify;">For any concerns, please contact [email ng FEUR tertiary].</p>
        </div>
      </div>
      <div class='modal-footer'>
        <!-- This is the button that will trigger the form submission -->
        <button type="button" class="btn btn-primary" id="finalSubmitButton">Submit</button>
      </div>
    </div>
  </div>
</div>


  <!-- jQuery, Popper.js, and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


  <script src="js/sessionDuration.js"></script>

  <script>
    $(document).ready(function () {
      $('.form-select').select2();
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Assuming availableDays is a PHP variable
      const availableDays = <?php echo json_encode($availableDays); ?>;

      const sessionDateInput = document.getElementById('sessionDate');

      sessionDateInput.addEventListener('change', function () {
        const selectedDate = new Date(this.value);
        const selectedDay = selectedDate.toLocaleString('en-US', { weekday: 'long' });

        if (!availableDays.includes(selectedDay)) {
          alert('Please select a valid date. This tutor is only available on: ' + availableDays.join(', '));
          this.value = ''; // Clear the input if the selected day is invalid
        }
      });
    });
  </script>

<script>
  // Function to check if all fields are filled
  function validateForm() {
    const subjectExpertise = document.getElementById('subjectExpertise').value;
    const sessionDate = document.getElementById('sessionDate').value;
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;
    const need = document.getElementById('need').value;

    // Check if all fields have values (not just undefined or empty strings)
    if (subjectExpertise.trim() && sessionDate && startTime && endTime && need.trim()) {
      document.getElementById('modalButton').disabled = false;  // Enable button if all fields are filled
    } else {
      document.getElementById('modalButton').disabled = true;  // Disable button if any field is empty
    }
  }

  // Attach event listeners to input fields to check validation on input
  document.addEventListener('DOMContentLoaded', function() {
    const subjectExpertiseInput = document.getElementById('subjectExpertise');
    const sessionDateInput = document.getElementById('sessionDate');
    const startTimeInput = document.getElementById('startTime');
    const endTimeInput = document.getElementById('endTime');
    const needInput = document.getElementById('need');
    
    // Initially disable the button
    document.getElementById('modalButton').disabled = true;

    // Add event listeners to trigger form validation when inputs change
    subjectExpertiseInput.addEventListener('input', validateForm);
    sessionDateInput.addEventListener('input', validateForm);
    startTimeInput.addEventListener('input', validateForm);
    endTimeInput.addEventListener('input', validateForm);
    needInput.addEventListener('input', validateForm);
  });
</script>

<script>
  // Wait until the DOM is fully loaded
  document.addEventListener('DOMContentLoaded', function () {
    // Attach event listener to the button inside the modal
    document.getElementById('finalSubmitButton').addEventListener('click', function () {
      // Submit the form programmatically
      document.getElementById('sessionForm').submit();
    });
  });
</script>









</body>

</html>
