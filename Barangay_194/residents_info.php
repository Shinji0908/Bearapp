<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: index.php"); // Redirect to login if unauthorized
    exit();
}

// Fetch all users with resident profile information, sorted alphabetically
$sql = "SELECT u.user_id, CONCAT(u.first_name, ' ', u.last_name) AS fullname, u.email, u.account_status, u.password
        FROM users u
        LEFT JOIN residentprofiles rp ON u.user_id = rp.user_id
        ORDER BY fullname ASC";

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }
         .App img {
        width: 150px; 
        height: auto; 
        margin-left: 30px;
         }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background-color: #f3e9db;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 20px 0;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
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

        .sidebar ul li:hover {
            background-color: #34495e;
        }

        /* Main content styles */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }

        /* Logout button at the bottom */
        .logout-btn {
            position: absolute;
            bottom: 50px;
            left: 20px;
            padding: 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            cursor: pointer;
            width: 200px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        .view-btn, .edit-btn, .delete-btn {
            padding: 5px 10px;
            color: white;
            text-decoration: none;
        }

        .view-btn {
            background-color: #3498db;
        }

        .edit-btn {
            background-color: #f1c40f;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .view-btn:hover, .edit-btn:hover, .delete-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
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

    <!-- Main Content -->
    <div class="main-content">
        <h1>All Users</h1>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Status</th>
                    <th>Edit Password</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['user_id']) ?></td>
                            <td><?= htmlspecialchars($row['fullname']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['password']) ?></td>
                            <td><?= htmlspecialchars($row['account_status'] ?? 'Inactive') ?></td>
                            <td>
                            <a href="edit_user_password.php?id=<?= $row['user_id'] ?>" class="edit-password-btn">Edit Password</a>
                            </td>
                            <td>
                                <a href="view_user_details.php?id=<?= $row['user_id'] ?>" class="view-btn">View</a>
                                <a href="edit_user_profile.php?id=<?= $row['user_id'] ?>" class="edit-btn">Edit</a>
                                <a href="delete_user_profile.php?id=<?= $row['user_id'] ?>" class="delete-btn" 
                                   onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
