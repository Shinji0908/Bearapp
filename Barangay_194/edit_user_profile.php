<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: index.php");
    exit();
}

// Get user ID
$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    echo "User ID not provided.";
    exit();
}

// Fetch user details
$sql = "SELECT * FROM ResidentProfiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Resident profile not found.";
    exit();
}

$resident = $result->fetch_assoc();

// Update user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $birthplace = $_POST['birthplace'];
    $contact_number = $_POST['contact_number'];
    $age = $_POST['age'];
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];

    $update_sql = "UPDATE ResidentProfiles 
                   SET first_name=?, last_name=?, address=?, birthplace=?, contact_number=?, 
                       age=?, birthday=?, gender=?
                   WHERE user_id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssssi", $first_name, $last_name, $address, $birthplace, 
                              $contact_number, $age, $birthday, $gender, $user_id);
   if ($stmt->execute()) {
    echo "<script>alert('Profile updated successfully!');</script>";
} else {
    echo "<script>alert('Error updating profile: " . addslashes($stmt->error) . "');</script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
            color: white;
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
<div class="container">
    <h2>Edit Resident Profile</h2>
    <form method="POST">
        <label>First Name :</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($resident['first_name']) ?>" required><br>
        <label>Last Name :</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($resident['last_name']) ?>" required><br>

        <label>Address :</label>
        <input type="text" name="address" value="<?= htmlspecialchars($resident['address']) ?>" required><br>

        <label>Birthplace :</label>
        <input type="text" name="birthplace" value="<?= htmlspecialchars($resident['birthplace']) ?>" required><br>

        <label>Contact Number :</label>
        <input type="text" name="contact_number" value="<?= htmlspecialchars($resident['contact_number']) ?>" required><br>

        <label>Age :</label>
        <input type="number" name="age" value="<?= htmlspecialchars($resident['age']) ?>" required><br>

        <label>Birthday :</label>
        <input type="date" name="birthday" value="<?= htmlspecialchars($resident['birthday']) ?>" required><br>

        <label>Gender :</label>
        <select name="gender" required><br>
            <option value="Male" <?= $resident['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $resident['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= $resident['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
        <br><br>
        <button type="submit">Update Profile</button>
    </form>
</div>
</div>
</body>
</html>

<?php
$conn->close();
?>
