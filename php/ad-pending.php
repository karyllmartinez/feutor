<?php
session_start();
include('connection/dbconfig.php');

if (isset($_POST['approve_btn'])) {
    // Handle approve action
    if (isset($_POST['tutorID']) && isset($_POST['subjectExpertise'])) {
        $tutorID = $_POST['tutorID'];
        $subjectExpertise = implode(",", $_POST['subjectExpertise']); // Convert array to comma-separated string

        // Update approval status and subject expertise
        $update_query = "UPDATE tutor SET approvalStatus='Approved', subjectExpertise='$subjectExpertise' WHERE tutorID='$tutorID'";
        $update_result = mysqli_query($conn, $update_query);

        $_SESSION['message'] = $update_result ? "Tutor approved successfully" : "Failed to approve tutor";
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
                <th style="width: 10%;">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Check if there are any pending tutors
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
                    <td><?php echo $row['gdriveLink']; ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="tutorID" value="<?php echo $row['tutorID']; ?>">
                            <div class="mb-3">
                            <select name="subjectExpertise[]" required class="form-select" multiple style="min-width: 150px; resize: both;">
                                    <option value="" disabled>Select Subject Expertise</option>
                                    <?php include('php/t-subj.php'); ?>
                                </select>
                            </div>
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
