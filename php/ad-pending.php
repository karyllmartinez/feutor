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

        $_SESSION['message'] = $update_result ? "Tutor approved successfully" : "Failed to approve tutor";
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

        $_SESSION['message'] = $update_result ? "Tutor declined successfully" : "Failed to decline tutor";
    }
}

// Retrieve pending student tutors from the database
$query = "SELECT * FROM tutor WHERE approvalStatus = 'pending'";
$result = mysqli_query($conn, $query);

?>

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
                        </form>
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
