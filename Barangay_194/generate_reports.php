<?php
include 'db_connection.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: index.php"); // Redirect to user login
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .Barangay img {
        width: 150px; 
        height: auto; 
        margin-left: 30px;
    }
        .sidebar {
            width: 250px;
            background-color:#f3e9db;
            height: 100vh;
            position: fixed;
            padding: 20px 0;
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

        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
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
            width: 200px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

    <div class="sidebar">
    <div class="Barangay">
            <a href="admin_dashboard.php">
            <img src="Applogo.png" alt="Barangay Logo">
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
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="main-content">
    </div>
</body>
</html>
