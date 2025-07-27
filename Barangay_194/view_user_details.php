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
$sql = "SELECT rp.profile_id, rp.first_name, rp.middle_name, rp.last_name, rp.address, rp.birthplace, rp.contact_number, 
               rp.age, rp.birthday, rp.gender, rp.district, rp.citizenship, rp.civil_status, rp.work, rp.precinct_number, 
               rp.spouse_name, rp.spouse_age, rp.spouse_birthday, rp.spouse_address, rp.spouse_work, 
               rp.child_name, rp.child_age, rp.child_grade_degree, rp.user_id
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
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Outfit", serif;
            background-color: #F9F6F0;
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

        .main-content {
            margin-left: 300px;
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

        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
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
            width: 260px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Dashboard</h2>
            <i class="fas fa-bars"></i>
        </div>
        <ul>
            <li><a href="pending_profiles.php">Approve Profiles</a></li>
            <li><a href="request_documents_admin.php">Request Documents</a></li>
            <li><a href="create_admin.php">Create Another Admin</a></li>
            <li><a href="view_all_users.php">View All Users</a></li>
            <li><a href="admin_logs.php">Admin Logs</a></li>
        </ul>
        <form action="logout.php" method="POST">
            <button class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="main-content">
        <div class="container">
            <h1>Resident Profile</h1>
            <table>
                <tr><th>Profile ID:</th><td><?= htmlspecialchars($resident['profile_id']) ?></td></tr>
                <tr><th>First Name:</th><td><?= htmlspecialchars($resident['first_name']) ?></td></tr>
                <tr><th>Middle Name:</th><td><?= htmlspecialchars($resident['middle_name']) ?></td></tr>
                <tr><th>Last Name:</th><td><?= htmlspecialchars($resident['last_name']) ?></td></tr>
                <tr><th>Address:</th><td><?= htmlspecialchars($resident['address']) ?></td></tr>
                <tr><th>Contact Number:</th><td><?= htmlspecialchars($resident['contact_number']) ?></td></tr>
                <tr><th>Birthplace:</th><td><?= htmlspecialchars($resident['birthplace']) ?></td></tr>
                <tr><th>Age:</th><td><?= htmlspecialchars($resident['age']) ?></td></tr>
                <tr><th>Birthday:</th><td><?= htmlspecialchars($resident['birthday']) ?></td></tr>
                <tr><th>Gender:</th><td><?= htmlspecialchars($resident['gender']) ?></td></tr>
                <tr><th>District:</th><td><?= htmlspecialchars($resident['district']) ?></td></tr>
                <tr><th>Citizenship:</th><td><?= htmlspecialchars($resident['citizenship']) ?></td></tr>
                <tr><th>Civil Status:</th><td><?= htmlspecialchars($resident['civil_status']) ?></td></tr>
                <tr><th>Work:</th><td><?= htmlspecialchars($resident['work'] ?? 'N/A') ?></td></tr>
                <tr><th>Precinct Number:</th><td><?= htmlspecialchars($resident['precinct_number'] ?? 'N/A') ?></td></tr>
                <tr><th>Spouse Name:</th><td><?= htmlspecialchars($resident['spouse_name'] ?? 'N/A') ?></td></tr>
                <tr><th>Spouse Age:</th><td><?= htmlspecialchars($resident['spouse_age'] ?? 'N/A') ?></td></tr>
                <tr><th>Spouse Birthday:</th><td><?= htmlspecialchars($resident['spouse_birthday'] ?? 'N/A') ?></td></tr>
                <tr><th>Spouse Address:</th><td><?= htmlspecialchars($resident['spouse_address'] ?? 'N/A') ?></td></tr>
                <tr><th>Spouse Work:</th><td><?= htmlspecialchars($resident['spouse_work'] ?? 'N/A') ?></td></tr>
                <tr><th>Child Name:</th><td><?= htmlspecialchars($resident['child_name'] ?? 'N/A') ?></td></tr>
                <tr><th>Child Age:</th><td><?= htmlspecialchars($resident['child_age'] ?? 'N/A') ?></td></tr>
                <tr><th>Child Grade/Degree:</th><td><?= htmlspecialchars($resident['child_grade_degree'] ?? 'N/A') ?></td></tr>
            </table>
            <a href="edit_user_profile.php?id=<?= $resident['user_id'] ?>" class="edit-btn">Edit</a>
            <a href="delete_user_profile.php?id=<?= $resident['user_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this profile?');">Delete</a>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>