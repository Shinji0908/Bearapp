<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: index.php"); // Redirect to login if unauthorized
    exit();
}

// Validate and fetch user details
$user_id = $_GET['id'] ?? '';
if (empty($user_id) || !is_numeric($user_id)) {
    echo "Invalid user ID.";
    exit();
}
$sql = "SELECT rp.profile_id, rp.first_name, rp.last_name, rp.address, rp.birthplace, rp.contact_number, 
               rp.age, rp.birthday, rp.gender, rp.user_id
        FROM ResidentProfiles rp
        WHERE rp.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Resident profile not found.";
    exit();
}
$resident = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Resident Profile</title>
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

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 1000px;
            transition: transform 0.3s ease-in-out;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .edit-btn, .delete-btn {
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
        }

        .edit-btn {
            background-color: #f1c40f;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .edit-btn:hover {
            background-color: #d4ac0d;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        a {
            text-decoration: none;
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
        <h1>Resident Profile</h1>
        <table>
            <tr><th>Profile ID:</th><td><?= htmlspecialchars($resident['profile_id']) ?></td></tr>
            <tr><th>First Name:</th><td><?= htmlspecialchars($resident['first_name']) ?></td></tr>
            <tr><th>Last Name:</th><td><?= htmlspecialchars($resident['last_name']) ?></td></tr>
            <tr><th>Address:</th><td><?= htmlspecialchars($resident['address']) ?></td></tr>
            <tr><th>Contact Number:</th><td><?= htmlspecialchars($resident['contact_number']) ?></td></tr>
            <tr><th>Birthplace:</th><td><?= htmlspecialchars($resident['birthplace']) ?></td></tr>
            <tr><th>Age:</th><td><?= htmlspecialchars($resident['age']) ?></td></tr>
            <tr><th>Birthday:</th><td><?= htmlspecialchars($resident['birthday']) ?></td></tr>
            <tr><th>Gender:</th><td><?= htmlspecialchars($resident['gender']) ?></td></tr>
        </table>
        <a href="edit_user_profile.php?id=<?= $resident['user_id'] ?>" class="edit-btn">Edit</a>
        <a href="delete_user_profile.php?id=<?= $row['user_id'] ?>" class="delete-btn" " class="delete-btn" onclick="return confirm('Are you sure you want to delete this profile?');">Delete</a>
    </div>
</body>
</html>
<?php $conn->close(); ?>
