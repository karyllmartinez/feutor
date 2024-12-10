<?php
session_start();
include('connection/dbconfig.php');

// Handle mark as claimed action (optional)
if (isset($_POST['claim_btn'])) {
    $refundID = $_POST['refundID'];

    // Update the claimed status to 'Claimed'
    $update_query = "UPDATE student_refunds SET refundStatus='Claimed' WHERE refundID='$refundID'";
    $update_result = mysqli_query($conn, $update_query);
}

// Retrieve refund data from the database
$sql = "
    SELECT 
        sr.refundID, 
        sr.studentID, 
        sr.sessionID, 
        sr.ratePerHour, 
        sr.duration, 
        sr.totalRefund, 
        sr.refundStatus, 
        sr.created_at,
        st.firstname AS studentFirstName, 
        st.lastname AS studentLastName,
        t.firstName AS tutorFirstName, 
        t.lastName AS tutorLastName,
        s.subject AS sessionSubject
    FROM student_refunds sr
    JOIN session s ON sr.sessionID = s.sessionID
    JOIN student st ON sr.studentID = st.studentID
    JOIN tutor t ON s.tutorID = t.tutorID
";
$result = mysqli_query($conn, $sql);

// Separate the records into claimed and not claimed
$claimedRefunds = [];
$notClaimedRefunds = [];

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['refundStatus'] === 'Claimed') {
        $claimedRefunds[] = $row;
    } else {
        $notClaimedRefunds[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refunds</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .button {
            padding: 5px 10px;
            color: white;
            background-color: blue;
            border: none;
            cursor: pointer;
        }

        .button:hover {
            background-color: darkblue;
        }

        /* Modal Background */
        .custom-modal {
            display: none;
            /* Hide modal by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            /* Semi-transparent background */
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Modal Content Box */
        .custom-modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .close-btn {
            position: absolute;
            top: 10px;
            /* Position 10px from the top */
            right: 10px;
            /* Position 10px from the right */
            font-size: 24px;
            font-weight: bold;
            color: black;
            cursor: pointer;
            visibility: hidden;
        }

        /* Modal Buttons */
        .modal-buttons {
            margin-top: 20px;
        }

        /* "Mark as Claimed" Button */
        .button {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: darkgreen;
        }

        /* "Cancel" Button */
        .cancel-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .cancel-btn:hover {
            background-color: darkred;
        }

        /* Trigger Button */
        .open-modal-btn {
            background-color: #f1c232;
            color: black;
            padding: 10px 50px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            align-items: center;
            display: flex;
            align-items: center;
        }

        .open-modal-btn:hover {
            background-color: #f1c232;
        }

        <style>

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: 'Arial', sans-serif;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            /* Center align the text */
            border: 1px solid green;
            font-size: 14px;
        }

        th {
            background-color: green;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            vertical-align: middle;
        }
    </style>
</head>

<body>

    <div>

        <?php if (isset($_SESSION['message'])): ?>
            <div style="color: green;">
                <?php echo $_SESSION['message'];
                unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Display Not Claimed Refunds -->
        <h3>Not Claimed Refunds</h3>
        <table>
            <thead>
                <tr>
                    <th>Refund ID</th>
                    <th>Student Name</th>
                    <th>Tutor Name</th>
                    <th>Session Subject</th>
                    <th>Session ID</th>
                    <th>Rate Per Hour</th>
                    <th>Total Refund</th>
                    <th>Refund Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($notClaimedRefunds)) {
                    foreach ($notClaimedRefunds as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row['refundID']; ?></td>
                            <td><?php echo htmlspecialchars($row['studentFirstName']) . " " . htmlspecialchars($row['studentLastName']); ?></td>
                            <td><?php echo htmlspecialchars($row['tutorFirstName']) . " " . htmlspecialchars($row['tutorLastName']); ?></td>
                            <td><?php echo htmlspecialchars($row['sessionSubject']); ?></td>
                            <td><?php echo htmlspecialchars($row['sessionID']); ?></td>
                            <td><?php echo number_format($row['ratePerHour'], 2); ?></td>
                            <td><?php echo number_format($row['totalRefund'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['refundStatus']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="refundID" value="<?php echo $row['refundID']; ?>">
                                    <button type="button" class="open-modal-btn" data-refund-id="<?php echo $row['refundID']; ?>">Mark as Claimed</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal for Marking as Claimed -->
                        <div class="custom-modal" id="myModal">
                            <div class="custom-modal-content">
                                <span class="close-btn">&times;</span>
                                <p>Are you sure you want to mark this refund as claimed?</p>
                                <form method="POST">
                                    <input type="hidden" id="modal-refund-id" name="refundID">
                                    <div class="modal-buttons">
                                        <button type="submit" class="button" name="claim_btn">Mark as Claimed</button>
                                        <button type="button" class="cancel-btn">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php
                    }
                } else {
                    echo "<tr><td colspan='9'>No not claimed refund records found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Display Claimed Refunds -->
        <h3>Claimed Refunds</h3>
        <table>
            <thead>
                <tr>
                    <th>Refund ID</th>
                    <th>Student Name</th>
                    <th>Tutor Name</th>
                    <th>Session Subject</th>
                    <th>Session ID</th>
                    <th>Rate Per Hour</th>
                    <th>Total Refund</th>
                    <th>Refund Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($claimedRefunds)) {
                    foreach ($claimedRefunds as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row['refundID']; ?></td>
                            <td><?php echo htmlspecialchars($row['studentFirstName']) . " " . htmlspecialchars($row['studentLastName']); ?></td>
                            <td><?php echo htmlspecialchars($row['tutorFirstName']) . " " . htmlspecialchars($row['tutorLastName']); ?></td>
                            <td><?php echo htmlspecialchars($row['sessionSubject']); ?></td>
                            <td><?php echo htmlspecialchars($row['sessionID']); ?></td>
                            <td><?php echo number_format($row['ratePerHour'], 2); ?></td>
                            <td><?php echo number_format($row['totalRefund'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['refundStatus']); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='8'>No claimed refund records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // JavaScript for modal functionality
        const modal = document.getElementById('myModal');
        const closeBtn = document.querySelector('.close-btn');
        const cancelBtn = document.querySelector('.cancel-btn');
        const modalRefundIdInput = document.getElementById('modal-refund-id');
        const openModalBtns = document.querySelectorAll('.open-modal-btn');

        // Open modal and set refundID dynamically
        openModalBtns.forEach(button => {
            button.addEventListener('click', () => {
                const refundID = button.getAttribute('data-refund-id');
                modalRefundIdInput.value = refundID;
                modal.style.display = 'block';
            });
        });

        // Close modal on 'X' or 'Cancel'
        closeBtn.addEventListener('click', () => modal.style.display = 'none');
        cancelBtn.addEventListener('click', () => modal.style.display = 'none');
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>

    <?php
    // Close the database connection
    mysqli_close($conn);
    ?>

</body>
</html>
