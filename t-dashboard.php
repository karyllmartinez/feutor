<?php
session_start();

include('php/t-auth.php');
include('connection/dbconfig.php'); // Include your database connection file
error_reporting(E_ALL);
ini_set('display_errors', 1);

$query = "SELECT `tutorID`, `firstName`, `lastName`, `email`, `degreeProgram`, `year`, `gdriveLink`, `approvalStatus`, `created_at`, `profilePicture`, `subjectExpertise`, `teachingMode`, `ratePerHour`, `bio` FROM `tutor` WHERE `tutorID` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $tutorID); // Assuming tutorID is an integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  // Fetch data
  $tutorData = $result->fetch_assoc();
} else {
  $message = "No data found for the tutor.";
}

$profilePicture = !empty($tutorData['profilePicture']) ? $tutorData['profilePicture'] : 'icons/default.png';

// Fetch notifications for the tutor
$notificationQuery = "SELECT notificationID, message, created_at, status FROM notifications WHERE tutorID = ? ORDER BY created_at DESC";
$notificationStmt = $conn->prepare($notificationQuery);
$notificationStmt->bind_param("i", $tutorID);
$notificationStmt->execute();
$notificationStmt->bind_result($notificationID, $message, $created_at, $status);
$notifications = [];

while ($notificationStmt->fetch()) {
  $notifications[] = [
    'notificationID' => $notificationID,
    'message' => $message,
    'created_at' => $created_at,
    'status' => $status  // Include status here
  ];
}


// Fetch unread notifications
$unreadQuery = "SELECT COUNT(*) FROM notifications WHERE tutorID = ? AND status = 'unread'";
$unreadStmt = $conn->prepare($unreadQuery);
$unreadStmt->bind_param("i", $tutorID);
$unreadStmt->execute();
$unreadStmt->bind_result($unreadCount);
$unreadStmt->fetch();
$unreadStmt->close();


// Get the count of notifications
$notificationCount = count($notifications);

$notificationStmt->close();



?>


<!DOCTYPE html>
<html>

<head>
  <title>FEUTOR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">

  <!-- Custom CSS for Notifications Modal -->
  <style>
    /* Custom Notifications Modal Styles */
    #notificationsModal {
      display: none;
      position: fixed;
      z-index: 1050;
      /* Ensure it is above other elements */
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
      /* Dark background overlay */
      padding-top: 60px;
      /* Add space at the top */
    }

    #notificationsModal .modal-content {
      background-color: #fff;
      margin: 5% auto;
      padding: 20px;
      border: 1px solid #ccc;
      width: 80%;
      max-width: 500px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    #notificationsModal .modal-header {
      font-weight: bold;
      font-size: 20px;
      text-align: center;
      margin-bottom: 10px;
    }

    #notificationsModal .modal-body {
      max-height: 300px;
      overflow-y: auto;
    }

    #notificationsModal .notification-item {
      padding: 10px;
      border-bottom: 1px solid #ddd;
      margin-bottom: 10px;
    }

    #notificationsModal .notification-item:hover {
      background-color: #f1f1f1;
    }

    #notificationsModal .notification-time {
      font-size: 12px;
      color: #777;
    }

    #notificationsModal .close {
      color: #aaa;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
      position: absolute;
      top: 10px;
      right: 20px;
    }

    #notificationsModal .close:hover,
    #notificationsModal .close:focus {
      color: black;
      text-decoration: none;
    }

    #notificationsModal .mark-read-btn {
      background-color: #4caf50;
      color: white;
      border: none;
      padding: 5px 10px;
      font-size: 12px;
      cursor: pointer;
      border-radius: 5px;
      margin-top: 5px;
    }

    #notificationsModal .mark-read-btn:hover {
      background-color: #45a049;
    }

    .notification-count {
      position: absolute;
      top: 0;
      right: -10px;
      background-color: red;
      color: white;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      text-align: center;
      font-size: 12px;
      line-height: 20px;
      font-weight: bold;
      display: inline-block;
      /* Ensure it's visible */
    }



    /* Position adjustments for the notification link */
    .nav-item.notifications {
      position: relative;
    }

    .notification-item.read {
      background-color: #f1f1f1;
      text-decoration: line-through;
      /* Strikethrough for read notifications */
    }

    /* Mark as Read Button */
    .mark-read-btn {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 5px 10px;
      font-size: 12px;
      cursor: pointer;
      border-radius: 4px;
      margin-top: 5px;
    }

    .mark-read-btn:hover {
      background-color: #45a049;
    }

    .mark-read-btn:focus {
      outline: none;
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
            <a class="nav-link" href="t-dashboard.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="appointmentsDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Appointments
            </a>
            <div class="dropdown-menu" aria-labelledby="appointmentsDropdown">
              <a class="dropdown-item" href="t-approved.php">Accepted</a>
              <a class="dropdown-item" href="t-declined.php">Declined</a>
              <a class="dropdown-item" href="t-finished.php">Finished</a>
            </div>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="#">Notifications</a>
          </li>
          <li class="nav-item user-dropdown" style="position: relative; display: inline-block;">
            <!-- Profile Picture and Inverted Triangle as trigger -->
            <a href="#" id="userDropdown" style="text-decoration: none;" onclick="toggleDropdown(event)">
              <img src="<?php echo $profilePicture; ?>" alt="Profile Picture"
                style="width: 40px; height: 40px; border-radius: 50%; margin-right: 5px;">
              <!-- White Inverted Triangle Indicator -->
              <span
                style="border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 5px solid #fff; display: inline-block; vertical-align: middle;"></span>
            </a>

            <!-- Dropdown menu -->
            <ul id="dropdownMenu"
              style="display: none; position: absolute; top: 50px; right: 0; background-color: #fff; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); border-radius: 4px; list-style-type: none; padding: 10px; min-width: 150px; z-index: 9999;"> <!-- BAGO NILAGYAN INDEX LANG -->
              <li style="margin-bottom: 5px;">
                <strong><?php echo $tutor_firstname; ?></strong>
              </li>
              <li style="border-bottom: 1px solid #ddd; margin-bottom: 5px;"></li> <!-- Divider -->
              <li style="margin-bottom: 5px;">
                <a href="t-profile.php" style="text-decoration: none; color: #000;">Edit Profile</a>
              </li>
              <li>
                <a href="t-logout.php" style="text-decoration: none; color: #000;">Logout</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>


  <!-- Content area to display tutor data -->
  <div class="container mt-3" style=" align-contents: center;">
    <div class="row justify-content-center">
      <h1 class="s-header">Pending Sessions</h1>
      <?php include('php/studentselection.php'); ?>
    </div>
  </div>

  <!-- Modal for Notifications -->
  <div id="notificationsModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <span class="close" onclick="closeNotificationsModal()">&times;</span>
        <h2>Notifications</h2>
      </div>
      <div class="modal-body">
        <?php foreach ($notifications as $notification): ?>
          <div class="notification-item" id="notification-<?php echo $notification['notificationID']; ?>">
            <p><?php echo $notification['message']; ?></p>
            <div class="notification-time"><?php echo date('M d, Y h:i A', strtotime($notification['created_at'])); ?>
            </div>
            <!-- Mark as Read Button -->
            <?php if ($notification['status'] !== 'read'): ?>
              <button class="btn btn-sm btn-success mark-read-btn"
                onclick="markAsRead(<?php echo $notification['notificationID']; ?>)">Mark as Read</button>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>




  <script>
    function markAsRead(notificationId) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'mark_as_read.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onload = function () {
        if (xhr.status === 200) {
          // Mark notification as read visually (add 'read' class)
          var notificationItem = document.querySelector(`#notification-${notificationId}`);
          notificationItem.classList.add('read');

          // Hide the "Mark as Read" button after it's clicked
          var button = notificationItem.querySelector('.mark-read-btn');
          button.style.display = 'none';

          // Update notification count if necessary
          var notificationCountElement = document.getElementById('notificationCount');
          var currentCount = parseInt(notificationCountElement.textContent);
          notificationCountElement.textContent = currentCount - 1;

          // If no more unread notifications, hide the notification count
          if (currentCount - 1 <= 0) {
            notificationCountElement.style.display = 'none';
          }
        } else {
          console.error('Error marking notification as read');
        }
      };

      xhr.send('notification_id=' + notificationId + '&action=read');
    }

  </script>


  <script>

    function openNotificationsModal() {
      document.getElementById('notificationsModal').style.display = 'block';
      document.getElementById('notificationCount').style.display = 'none';
    }

    function closeNotificationsModal() {
      document.getElementById('notificationsModal').style.display = 'none';
      document.getElementById('notificationCount').style.display = 'none';
    }
  </script>

  <script>
    function toggleDropdown(event) {
      event.preventDefault(); // Prevent default anchor behavior
      const dropdownMenu = document.getElementById('dropdownMenu');
      // Toggle dropdown visibility
      dropdownMenu.style.display = (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') ? 'block' : 'none';
    }

    // Close dropdown if clicked outside
    window.onclick = function (event) {
      const dropdownMenu = document.getElementById('dropdownMenu');
      const userDropdown = document.getElementById('userDropdown');

      if (!userDropdown.contains(event.target)) {
        // Close the dropdown if the click is outside the dropdown area
        if (dropdownMenu.style.display === 'block') {
          dropdownMenu.style.display = 'none';
        }
      }
    }
  </script>

  <!-- jQuery, Popper.js, and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


  <script src="disableBackButton.js"></script>

</body>

</html>