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
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT u.user_id, rp.first_name, rp.middle_name, rp.last_name, rp.address, rp.contact_number, rp.birthplace, rp.birthday AS date_of_birth, u.email,
                   rp.district, rp.citizenship, rp.civil_status, rp.work, rp.precinct_number, 
                   rp.spouse_name, rp.spouse_age, rp.spouse_birthday, rp.spouse_address, rp.spouse_work,
                   rp.child_name, rp.child_age, rp.child_grade_degree
            FROM Users u
            JOIN ResidentProfiles rp ON u.user_id = rp.user_id
            WHERE u.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if user exists
    if ($result->num_rows === 0) {
        echo "User not found.";
        exit();
    }

    $user = $result->fetch_assoc();
}

// Update user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $birthplace = $_POST['birthplace'];
    $date_of_birth = $_POST['date_of_birth'];
    $email = $_POST['email'];
    $district = $_POST['district'];
    $citizenship = $_POST['citizenship'];
    $civil_status = $_POST['civil_status'];
    $work = $_POST['work'];
    $precinct_number = $_POST['precinct_number'];
    $spouse_name = $_POST['spouse_name'];
    $spouse_age = $_POST['spouse_age'];
    $spouse_birthday = $_POST['spouse_birthday'];
    $spouse_address = $_POST['spouse_address'];
    $spouse_work = $_POST['spouse_work'];
    $child_name = $_POST['child_name'];
    $child_age = $_POST['child_age'];
    $child_grade_degree = $_POST['child_grade_degree'];

    // Update query
    $update_sql = "UPDATE ResidentProfiles rp
                   JOIN Users u ON rp.user_id = u.user_id
                   SET rp.first_name = ?, rp.middle_name = ?, rp.last_name = ?, rp.address = ?, rp.contact_number = ?, rp.birthplace = ?, rp.birthday = ?, u.email = ?, 
                       rp.district = ?, rp.citizenship = ?, rp.civil_status = ?, rp.work = ?, rp.precinct_number = ?, 
                       rp.spouse_name = ?, rp.spouse_age = ?, rp.spouse_birthday = ?, rp.spouse_address = ?, rp.spouse_work = ?, 
                       rp.child_name = ?, rp.child_age = ?, rp.child_grade_degree = ?
                   WHERE u.user_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssssssssssssisssi", 
                      $first_name, $middle_name, $last_name, $address, $contact_number, $birthplace, $date_of_birth, $email, 
                      $district, $citizenship, $civil_status, $work, $precinct_number, 
                      $spouse_name, $spouse_age, $spouse_birthday, $spouse_address, $spouse_work, 
                      $child_name, $child_age, $child_grade_degree, $user_id);

    if ($stmt->execute()) {
        header("Location: view_all_users.php?message=User updated successfully");
        exit();
    } else {
        echo "Error updating user: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        .form-container {
            width: 50%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px #aaa;
        }

        h2 {
            text-align: center;
            color: #34495e;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            color: #2c3e50;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        button {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2ecc71;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit User Profile</h2>
    <form method="POST">
        <div>
            <label>First Name</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
        </div>
        <div>
            <label>Middle Name</label>
            <input type="text" name="middle_name" value="<?php echo htmlspecialchars($user['middle_name']); ?>" required>
        </div>
        <div>
            <label>Last Name</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        </div>
        <div>
            <label>Address</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
        </div>
        <div>
            <label>Contact Number</label>
            <input type="tel" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>">
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div>
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($user['date_of_birth']); ?>" required>
        </div>
        <div>
            <label>Birthplace</label>
            <input type="text" name="birthplace" value="<?php echo htmlspecialchars($user['birthplace']); ?>" required>
        </div>
        <div>
            <label>District</label>
            <input type="text" name="district" value="<?php echo htmlspecialchars($user['district']); ?>">
        </div>
        <div>
            <label>Citizenship</label>
            <input type="text" name="citizenship" value="<?php echo htmlspecialchars($user['citizenship']); ?>">
        </div>
        <div>
            <label>Civil Status</label>
            <input type="text" name="civil_status" value="<?php echo htmlspecialchars($user['civil_status']); ?>">
        </div>
        <div>
            <label>Work</label>
            <input type="text" name="work" value="<?php echo htmlspecialchars($user['work']); ?>">
        </div>
        <div>
            <label>Precinct Number</label>
            <input type="text" name="precinct_number" value="<?php echo htmlspecialchars($user['precinct_number']); ?>">
        </div>
        <div>
            <label>Spouse Name</label>
            <input type="text" name="spouse_name" value="<?php echo htmlspecialchars($user['spouse_name']); ?>">
        </div>
        <div>
            <label>Spouse Age</label>
            <input type="number" name="spouse_age" value="<?php echo htmlspecialchars($user['spouse_age']); ?>">
        </div>
        <div>
            <label>Spouse Birthday</label>
            <input type="date" name="spouse_birthday" value="<?php echo htmlspecialchars($user['spouse_birthday']); ?>">
        </div>
        <div>
            <label>Spouse Address</label>
            <input type="text" name="spouse_address" value="<?php echo htmlspecialchars($user['spouse_address']); ?>">
        </div>
        <div>
            <label>Spouse Work</label>
            <input type="text" name="spouse_work" value="<?php echo htmlspecialchars($user['spouse_work']); ?>">
        </div>
        <div>
            <label>Child Name</label>
            <input type="text" name="child_name" value="<?php echo htmlspecialchars($user['child_age']); ?>">
        </div>
        <div>
            <label>Child Grade/Degree</label>
            <input type="text" name="child_grade_degree" value="<?php echo htmlspecialchars($user['child_grade_degree']); ?>">
        </div>
        <button type="submit">Update</button>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
