<?php
session_start();
include('connection/dbconfig.php');

if (isset($_POST['approve_btn'])) {
    // Handle approve action
    if (isset($_POST['tutorID']) && isset($_POST['subjectExpertise']) && isset($_POST['ratePerHour'])) {
        $tutorID = $_POST['tutorID'];
        $subjectExpertise = implode(",", $_POST['subjectExpertise']); // Convert array to comma-separated string
        $ratePerHour = $_POST['ratePerHour'];

        // Update approval status, subject expertise, and rate per hour
        $update_query = "UPDATE tutor SET approvalStatus='Approved', subjectExpertise='$subjectExpertise', ratePerHour='$ratePerHour' WHERE tutorID='$tutorID'";
        $update_result = mysqli_query($conn, $update_query);

        // Send a notification after approval
        if ($update_result) {
            $message = "Your tutor application has been approved.";
            $notify_query = "INSERT INTO notifications (tutorID, message, status) VALUES ('$tutorID', '$message', 'unread')";
            mysqli_query($conn, $notify_query);
            $_SESSION['message'] = "Tutor approved successfully";
        } else {
            $_SESSION['message'] = "Failed to approve tutor";
        }
    } else {
        $_SESSION['message'] = "Required fields missing for approval";
    }
}

if (isset($_POST['decline_btn'])) {
    // Handle decline action
    if (isset($_POST['tutorID'])) {
        $tutorID = $_POST['tutorID'];

        // Update approval status to "Declined"
        $update_query = "UPDATE tutor SET approvalStatus='Declined' WHERE tutorID='$tutorID'";
        $update_result = mysqli_query($conn, $update_query);

        // Send a notification after decline
        if ($update_result) {
            $message = "Your tutor application has been declined.";
            $notify_query = "INSERT INTO notifications (tutorID, message, status) VALUES ('$tutorID', '$message', 'unread')";
            mysqli_query($conn, $notify_query);
            $_SESSION['message'] = "Tutor declined successfully";
        } else {
            $_SESSION['message'] = "Failed to decline tutor";
        }
    }
}

// Retrieve pending student tutors from the database
$query = "SELECT * FROM tutor WHERE approvalStatus = 'pending'";
$result = mysqli_query($conn, $query);

?>

<style>

    /* Table styling */
.table {
    width: 100%;
    border-collapse: collapse;
    background-color: #f9f9f9;
}

.table th, .table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

/* Header Styling */
.table th {
    background-color: green;
    color: white;
    font-weight: bold;
}

/* Row hover effect */
.table tr:hover {
    background-color: #f1f1f1;
}

/* Link styling for G-Drive Link */
.table a {
    color: #007bff;
    text-decoration: none;
}

.table a:hover {
    text-decoration: underline;
}

/* Input and select box styling */
.table input[type="number"],
.table select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.table input[type="number"]:focus,
.table select:focus {
    border-color: #007bff;
    outline: none;
}

/* Ensure buttons have the same width */
.table .btn {
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 4px;
    border: none;
    width: 100%; /* Make buttons take up the full available space */
}

.table td button {
    width: 48%; /* Make the buttons almost equal in width, with some spacing in between */
    margin-right: 4%; /* Add some space between the buttons */
}

.table td button:last-child {
    margin-right: 0; /* Remove the margin from the last button */
}


.table .btn-success {
    background-color: #28a745;
    color: white;
}

.table .btn-success:hover {
    background-color: #218838;
}

.table .btn-danger {
    background-color: #dc3545;
    color: white;
}

.table .btn-danger:hover {
    background-color: #c82333;
}

/* Center the table when there are no pending tutors */
.table td[colspan="9"] {
    text-align: center;
    color: #999;
    font-style: italic;
}

</style>

<table class="table">
    <thead>
        <tr>
            <th style="width: 10%;">Tutor ID</th>
            <th style="width: 15%;">First Name</th>
            <th style="width: 15%;">Last Name</th>
            <th style="width: 20%;">Email</th>
            <th style="width: 15%;">Degree Program</th>
            <th style="width: 5%;">Year</th>
            <th style="width: 10%;">G-Drive Link</th>
            <th style="width: 15%;">Subject Expertise</th>
            <th style="width: 10%;">Rate Per Hour</th>
            <th style="width: 10%;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $row['tutorID']; ?></td>
                    <td><?php echo $row['firstName']; ?></td>
                    <td><?php echo $row['lastName']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['degreeProgram']; ?></td>
                    <td><?php echo $row['year']; ?></td>
                    <td><a href="<?php echo $row['gdriveLink']; ?>" target="_blank">View</a></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="tutorID" value="<?php echo $row['tutorID']; ?>">
                            <div class="mb-3">
                                <select name="subjectExpertise[]" required class="form-select" multiple style="min-width: 150px;">
                                    <?php
                                    $subjectExpertise = explode(",", $row['subjectExpertise']); // Get current expertise as array
                                    foreach ($subjectExpertise as $subject) {
                                        ?>
                                        <option value="<?php echo htmlspecialchars(trim($subject)); ?>" selected>
                                            <?php echo htmlspecialchars(trim($subject)); ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                    </td>
                    <td>
                        <input 
                            type="number" 
                            name="ratePerHour" 
                            required 
                            placeholder="Enter Rate Per Hour" 
                            class="form-control form-control-lg" 
                            style="min-width: 150px;" 
                            step="0.01">
                    </td>

                    <td>
                        <button type="submit" class="btn btn-success" name="approve_btn">Approve</button>
                        <button type="submit" class="btn btn-danger" name="decline_btn">Decline</button>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='9'>No pending tutors</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Initialize Select2 -->
<script>
    $(document).ready(function() {
        $('select[name="subjectExpertise[]"]').select2();
    });
</script>

<?php
// Close the database connection
mysqli_close($conn);
?>
