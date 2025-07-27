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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="fullcalendar/css/fullcalendar.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
 

    <style>
        body {
            font-family: "Outfit", serif;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidebar {
            width: 300px;
            background-color: #163e66;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 20px 0;
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between; /* Aligns text and icon to opposite sides */
            align-items: center;
            padding: 0 20px;
        }

        .sidebar-header h2 {
            margin: 0;
            font-size: 24px;
        }

        .sidebar-header i {
            cursor: pointer;
            font-size: 24px;
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
            color: white;
            text-decoration: none;
            display: block;
        }

        .sidebar ul li:hover {
            background-color: #34495e;
        }

        .notifications {
            margin-top: 20px;
            padding: 10px;
            background-color: #1c4e88;
            border-radius: 5px;
        }

        .notifications h3 {
            margin: 0 0 10px;
            font-size: 18px;
            text-align: center;
            color: #f1f1f1;
        }

        .notification-item {
            background-color: #f8f8f8;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
        }

        .notification-item.unread {
            font-weight: bold;
        }

        .main-content {
            margin-left: 300px;
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
            width: 260px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

    </style>
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2 style="text-align: center;">Admin Dashboard</h2>
        <ul>
            <li><a href="view_all_users.php">Manage Accounts</a></li>
            <li><a href="request_documents_admin.php">Verify Accounts</a></li>
            <li><a href="create_admin.php">Residents Info</a></li>
            <li><a href="view_all_users.php">Responders Info</a></li>
            <li><a href="view_all_users.php">Incidents Data</a></li>
            <li><a href="admin_logs.php">Admin Logs</a></li>
            <li><a href="manageEventsLink">Activity Logs</a></li>
        </ul>
        <!-- Logout button -->
        <form action="logout.php" method="POST">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome to the Admin Dashboard</h1>
        <p>Select an option from the menu to manage the system.</p>
    </div>
</body>
</html>
