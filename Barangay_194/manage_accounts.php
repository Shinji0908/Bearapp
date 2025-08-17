<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Fetch pending profiles
$sql = "
    SELECT rp.profile_id, rp.user_id, rp.first_name, rp.last_name, rp.address, rp.birthplace, rp.contact_number, 
           vd.document_path
    FROM ResidentProfiles rp
    INNER JOIN VerificationDocuments vd ON rp.user_id = vd.user_id
    WHERE vd.status = 'Pending' AND rp.profile_status = 'Pending';
";

$result = $conn->query($sql);

if (!$result) {
    die("Error fetching pending profiles: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Accounts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh; /* Full-screen height */
            box-sizing: border-box;
        }

        .sidebar {
            width: 250px;
            background-color: #f3e9db;
            color: white;
            height: 100%;
            position: fixed;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            padding: 15px 20px;
            cursor: pointer;
        }

        .sidebar ul li a {
            color: black;
            text-decoration: none;
            display: block;
        }
        .sidebar ul li a:hover {
            color: white;
        }
        .sidebar ul li:hover {
            background-color: #34495e;
        }

        .logout-btn {
            position: absolute;
            bottom: 50px;
            left: 20px;
            padding: 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            cursor: pointer;
            width: calc(100% - 40px); /* Full width minus padding */
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .main-content {
            flex-grow: 1;
            margin-left: 250px; /* Matches sidebar width */
            padding: 20px;
            background-color: #f4f4f4;
            overflow-y: auto; /* Allows scrolling if content overflows */
            height: 100%;
        }

        .main-content h2 {
            margin-top: 0;
        }

        .profile {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .profile button {
            padding: 10px 15px;
            margin-right: 10px;
            border: none;
            border-radius: 3px;
            background-color: #3498db;
            color: white;
            cursor: pointer;
        }

        .profile button:hover {
            background-color: #2980b9;
        }

        .profile a {
            text-decoration: none;
        }
        .view-document-btn {
    padding: 10px 15px;
    color: white;
    background-color: #3498db;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    text-transform: uppercase;
    font-size: 14px;
}

.view-document-btn:hover {
    background-color: #2980b9;
}
.App img {
        width: 150px; 
        height: auto; 
        margin-left: 30px;}

    </style>
</head>
<body>
    <div class="sidebar">
    <div class="App">
            <a href="admin_dashboard.php">
            <img src="Applogo.png" alt="Applogo">
        </div>
        <ul>
            <li><a href="manage_accounts.php">Manage Accounts</a></li> 
            <li><a href="verify_accounts.php">Verify Accounts</a></li> 
            <li><a href="residents_info.php">Residents Info</a></li> 
            <li><a href="responders_info.php">Responders Info</a></li> 
            <li><a href="incidents_data.php">Incidents Data</a></li> 
            <li><a href="activity_logs.php">Activity Logs</a></li>
            <li><a href="generate_reports.php">Generate Reports</a></li>
        </ul>
        <form action="logout.php" method="POST">
            <button class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="main-content">
        <h1>Pending Profiles</h1>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='profile'>";
                echo "Resident ID: " . htmlspecialchars($row['user_id']) . "<br>";
                echo "Name: " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['middle_name']) . " " . htmlspecialchars($row['last_name']) . "<br>";
                echo "Address: " . htmlspecialchars($row['address']) . "<br>";
                echo "Birthplace: " . htmlspecialchars($row['birthplace']) . "<br>";
                echo "Contact: " . htmlspecialchars($row['contact_number']) . "<br>";
                echo "<button class='view-document-btn' onclick=\"window.open('" . htmlspecialchars($row['document_path']) . "', '_blank')\">View Document</button>";
                echo "<button class='view-details-btn' data-user-id='" . $row['user_id'] . "'>View Details</button>";
                echo "<a href='process_approval.php?profile_id=" . $row['profile_id'] . "&action=approve'><button>Approve</button></a>";
                echo "<a href='process_approval.php?profile_id=" . $row['profile_id'] . "&action=reject'><button>Reject</button></a>";
                echo "</div>";
            }
        } else {
            echo "<p>No pending profiles found.</p>";
        }
        ?>
    </div>

    <!-- Modal -->
    <div class="modal" id="detailsModal">
        <div class="modal-header">
            <span class="modal-close">&times;</span>
        </div>
        <div class="modal-content">
            <!-- Details will be dynamically populated here -->
        </div>
    </div>
    <div class="overlay"></div>

    <script>
        const modal = document.getElementById('detailsModal');
        const overlay = document.querySelector('.overlay');
        const closeModal = document.querySelector('.modal-close');

        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const userId = button.getAttribute('data-user-id');
                fetch(`view_user_details.php?id=${userId}`)
                    .then(response => response.text())
                    .then(data => {
                        modal.querySelector('.modal-content').innerHTML = data;
                        modal.classList.add('active');
                        overlay.classList.add('active');
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        closeModal.addEventListener('click', () => {
            modal.classList.remove('active');
            overlay.classList.remove('active');
        });

        overlay.addEventListener('click', () => {
            modal.classList.remove('active');
            overlay.classList.remove('active');
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>