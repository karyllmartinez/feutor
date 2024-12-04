<?php
// Include database connection
include('connection/dbconfig.php');

// Initialize search variables
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Check if "View All" button is clicked
if(isset($_GET['view_all'])) {
    // Reset search variable
    $search = "";
}

// SQL query to fetch subjects
$sql = "SELECT * FROM subjects";

// Add search condition if search term is provided
if(!empty($search)) {
    $sql .= " WHERE subject_name LIKE '%$search%'";
}

$result = $conn->query($sql);

// Display search form
echo '<form action="" method="GET">';
echo '<input type="text" name="search" placeholder="Search by subject name" value="' . $search . '">';
echo '<button type="submit" class="search-btn">Search</button>'; // Apply the CSS class here
echo '<a href="?view_all" class="view-all-btn">View All</a>';
echo '</form>';


// Display subjects in a table
if ($result->num_rows > 0) {
    echo '<table class="table">';
    echo '<thead><tr><th>Subject Name</th><th>Action</th></tr></thead>';
    echo '<tbody>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['subject_name'] . '</td>';
    
        // Delete button triggering modal
        echo '<td>';
        echo '<button type="button" class="btn btn-danger ml-2 open-modal" data-target="#deleteModal' . $row['id'] . '">Delete</button>';
        echo '</td>';
        echo '</tr>';

        // Modal for confirmation
        echo '
        <div class="custom-modal" id="deleteModal' . $row['id'] . '">
            <div class="custom-modal-content">
                <span class="close-btn" data-dismiss="modal" aria-label="Close">&times;</span>
                <h5>Confirm Deletion</h5>
                <p>Are you sure you want to delete the subject "' . $row['subject_name'] . '"?</p>
                <form action="deletesubject.php" method="POST">
                    <input type="hidden" name="subjectId" value="' . $row['id'] . '">
                    <div class="modal-buttons">
                     
                        <button type="submit" name="deleteSubject" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo "No subjects found";
}

// Close connection
$conn->close();
?>

<!-- Add Bootstrap and jQuery for Modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Open Modal when the delete button is clicked
    $(document).on('click', '.open-modal', function() {
        var target = $(this).data('target');
        $(target).css('display', 'flex').css('opacity', '1'); // Show modal
    });

    // Close Modal when the close button is clicked
    $(document).on('click', '.close-btn', function() {
        $(this).closest('.custom-modal').css('display', 'none').css('opacity', '0'); // Hide modal
    });

    // Close Modal when clicking anywhere outside the modal content
    $(document).on('click', '.custom-modal', function(e) {
        if ($(e.target).closest('.custom-modal-content').length === 0) {
            $(this).css('display', 'none').css('opacity', '0'); // Hide modal
        }
    });
</script>

<style>
    /* Modal Background */
    .custom-modal {
        display: none; /* Hide modal by default */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
        align-items: center;
        justify-content: center;
        text-align: center;
        opacity: 0; /* Start with opacity 0 */
        animation: fadeIn 0.3s forwards; /* Animation for fade-in */
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
        transform: translateY(-50px); /* Start above the screen */
        opacity: 0; /* Start with opacity 0 */
        animation: slideIn 0.3s forwards, fadeInContent 0.3s forwards; /* Animation for slide-in and fade-in */
    }

    /* Fade-in Animation */
    @keyframes fadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    /* Slide-in Animation for Modal Content */
    @keyframes slideIn {
        0% {
            transform: translateY(-50px);
        }
        100% {
            transform: translateY(0);
        }
    }

    /* Fade-in Animation for Modal Content */
    @keyframes fadeInContent {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    /* Close button */
    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
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

    .search-btn {
        background-color: #007bff; /* Blue background */
        color: white;              /* White text */
        border: none;              /* No border */
        padding: 8px 16px;         /* Padding around the text */
        font-size: 16px;           /* Font size */
        cursor: pointer;          /* Pointer cursor on hover */
        border-radius: 5px;        /* Rounded corners */
        transition: background-color 0.3s ease; /* Smooth background color transition */
        margin-left: 1%;
    }

    .search-btn:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }

    /* Style for the "View All" link */
    .view-all-btn {
        float: right;               /* Float the link to the right */
        margin-left: 10px;           /* Space between the button and the search input */
        background-color: #007bff;   /* Blue background */
        color: white;                /* White text */
        padding: 8px 16px;           /* Padding */
        font-size: 16px;             /* Font size */
        border-radius: 5px;          /* Rounded corners */
        text-decoration: none;       /* Remove underline */
        transition: background-color 0.3s ease; /* Smooth background color transition */
    }

    .view-all-btn:hover {
        background-color: #0056b3;   /* Darker blue on hover */
    }
</style>
