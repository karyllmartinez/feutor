<?php
session_start();
include('connection/dbconfig.php');

// Handle mark as claimed action
if (isset($_POST['claim_btn'])) {
    $earningsID = $_POST['earningsID'];

    // Update the claimed status to 'Claimed'
    $update_query = "UPDATE tutor_earnings SET claimedStatus='Claimed' WHERE earningsID='$earningsID'";
    $update_result = mysqli_query($conn, $update_query);

    //$_SESSION['message'] = $update_result ? "Earnings marked as claimed successfully." : "Failed to update claimed status.";
}

// Retrieve tutor earnings from the database, joining with the tutor table
$query = "
    SELECT 
        te.earningsID, 
        t.tutorID, 
        CONCAT(t.firstName, ' ', t.lastName) AS tutorName, 
        te.sessionID, 
        te.ratePerHour, 
        te.duration, 
        te.totalEarnings, 
        te.claimedStatus 
    FROM 
        tutor_earnings te
    JOIN 
        tutor t ON te.tutorID = t.tutorID
";
$result = mysqli_query($conn, $query);

// Separate the records into claimed and not claimed
$claimedEarnings = [];
$notClaimedEarnings = [];

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['claimedStatus'] === 'Claimed') {
        $claimedEarnings[] = $row;
    } else {
        $notClaimedEarnings[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Earnings</title>
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

        <h3>Not Claimed Earnings</h3>
        <table>
            <thead>
                <tr>
                    <th>Earnings ID</th>
                    <th>Tutor Name</th>
                    <th>Session ID</th>
                    <th>Rate Per Hour</th>
                    <th>Duration</th>
                    <th>Total Earnings</th>
                    <th>Claimed Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($notClaimedEarnings)) {
                    foreach ($notClaimedEarnings as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row['earningsID']; ?></td>
                            <td><?php echo htmlspecialchars($row['tutorName']); ?></td>
                            <td><?php echo htmlspecialchars($row['sessionID']); ?></td>
                            <td><?php echo number_format($row['ratePerHour'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['duration']); ?></td>
                            <td><?php echo number_format($row['totalEarnings'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['claimedStatus']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="earningsID" value="<?php echo $row['earningsID']; ?>">
                                    <!-- <button type="submit" class="button" name="claim_btn">Mark as Claimed</button> -->
                                    <button type="button" class="open-modal-btn"
                                        data-earnings-id="<?php echo $row['earningsID']; ?>">Mark as Claimed</button>
                                </form>
                            </td>
                        </tr>

                        <div class="custom-modal" id="myModal">
                            <div class="custom-modal-content">
                                <span class="close-btn">&times;</span>
                                <p>Are you sure you want to mark this as claimed?</p>
                                <form method="POST">
                                    <input type="hidden" id="modal-earnings-id" name="earningsID">
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
                    echo "<tr><td colspan='8'>No not claimed earnings records found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Claimed Earnings</h3>
        <table>
            <thead>
                <tr>
                    <th>Earnings ID</th>
                    <th>Tutor Name</th>
                    <th>Session ID</th>
                    <th>Rate Per Hour</th>
                    <th>Duration</th>
                    <th>Total Earnings</th>
                    <th>Claimed Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($claimedEarnings)) {
                    foreach ($claimedEarnings as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row['earningsID']; ?></td>
                            <td><?php echo htmlspecialchars($row['tutorName']); ?></td>
                            <td><?php echo htmlspecialchars($row['sessionID']); ?></td>
                            <td><?php echo number_format($row['ratePerHour'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['duration']); ?></td>
                            <td><?php echo number_format($row['totalEarnings'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['claimedStatus']); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7'>No claimed earnings records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Get modal elements
        const modal = document.getElementById('myModal');
        const closeBtn = document.querySelector('.close-btn');
        const cancelBtn = document.querySelector('.cancel-btn');
        const modalEarningsIdInput = document.getElementById('modal-earnings-id');
        const openModalBtns = document.querySelectorAll('.open-modal-btn');

        // Open modal and set earningsID dynamically
        openModalBtns.forEach(button => {
            button.addEventListener('click', () => {
                const earningsID = button.getAttribute('data-earnings-id');
                modalEarningsIdInput.value = earningsID;
                modal.style.display = 'block';
            });
        });

        // Close modal on 'X'
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Close modal on 'Cancel'
        cancelBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Close modal on click outside the content box
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