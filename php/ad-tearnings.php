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
        th, td {
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
    </style>
</head>
<body>

<div>

    <?php if (isset($_SESSION['message'])): ?>
        <div style="color: green;">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
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
                            <button type="submit" class="button" name="claim_btn">Mark as Claimed</button>
                        </form>
                    </td>
                </tr>
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

<?php
// Close the database connection
mysqli_close($conn);
?>
