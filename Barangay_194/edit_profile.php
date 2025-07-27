<?php
include 'db_connection.php';
session_start();

if (isset($_SESSION['success_message'])) {
    echo "<p style='color: green;'>" . $_SESSION['success_message'] . "</p>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<p style='color: red;'>" . $_SESSION['error_message'] . "</p>";
    unset($_SESSION['error_message']);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve the current profile data
$sql = "SELECT * FROM ResidentProfiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
$stmt->close();

// Extract existing data or set defaults
$first_name = $profile['first_name'] ?? '';
$middle_name = $profile['middle_name'] ?? '';
$last_name = $profile['last_name'] ?? '';
$age = $profile['age'] ?? '';
$gender = $profile['gender'] ?? '';
$birthday = $profile['birthday'] ?? '';
$birthplace = $profile['birthplace'] ?? '';
$address = $profile['address'] ?? '';
$district = $profile['district'] ?? '';
$precinct_number = $profile['precinct_number'] ?? '';
$citizenship = $profile['citizenship'] ?? '';
$civil_status = $profile['civil_status'] ?? '';
$contact_number = $profile['contact_number'] ?? '';
$work = $profile['work'] ?? '';
$spouse_name = $profile['spouse_name'] ?? '';
$spouse_age = $profile['spouse_age'] ?? '';
$spouse_birthday = $profile['spouse_birthday'] ?? '';
$spouse_address = $profile['spouse_address'] ?? '';
$spouse_work = $profile['spouse_work'] ?? '';
$child_name = $profile['child_name'] ?? '';
$child_age = $profile['child_age'] ?? '';
$child_grade_degree = $profile['child_grade_degree'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 700px;
            margin: 60px auto;
            padding: 40px;
            background-color: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        input[type="text"], input[type="number"], input[type="date"], select, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #2c3e50;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #A9A9A9;
        }
        .section-title {
            background-color: #f1f1f1;
            padding: 10px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .form-group input[type="file"] {
            border: none;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Profile</h1>
        <form action="edit_profile_process.php" method="POST" enctype="multipart/form-data">
            <!-- Personal Information -->
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($first_name) ?>" required>
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name:</label>
                <input type="text" name="middle_name" value="<?= htmlspecialchars($middle_name) ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($last_name) ?>" required>
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" name="age" value="<?= htmlspecialchars($age) ?>" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender:</label>
                <select name="gender" required>
                    <option value="Male" <?= $gender === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $gender === 'Female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="birthday">Birthday:</label>
                <input type="date" name="birthday" value="<?= htmlspecialchars($birthday) ?>" required>
            </div>

            <div class="form-group">
                <label for="birthplace">Birthplace:</label>
                <input type="text" name="birthplace" value="<?= htmlspecialchars($birthplace) ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" name="address" value="<?= htmlspecialchars($address) ?>" required>
            </div>

            <div class="form-group">
                <label for="district">District:</label>
                <input type="text" name="district" value="<?= htmlspecialchars($district) ?>" required>
            </div>

            <div class="form-group">
                <label for="precinct_number">Precinct Number:</label>
                <input type="text" name="precinct_number" value="<?= htmlspecialchars($precinct_number) ?>" required>
            </div>

            <div class="form-group">
                <label for="citizenship">Citizenship:</label>
                <input type="text" name="citizenship" value="<?= htmlspecialchars($citizenship) ?>" required>
            </div>

            <div class="form-group">
                <label for="civil_status">Civil Status:</label>
                <input type="text" name="civil_status" value="<?= htmlspecialchars($civil_status) ?>" required>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" name="contact_number" value="<?= htmlspecialchars($contact_number) ?>" required>
            </div>

            <div class="form-group">
                <label for="work">Work:</label>
                <input type="text" name="work" value="<?= htmlspecialchars($work) ?>">
            </div>

            <!-- Spouse Details -->
            <div class="section-title">
                <h3>Spouse Details</h3>
            </div>
            <div class="form-group">
                <label for="spouse_name">Name:</label>
                <input type="text" name="spouse_name" value="<?= htmlspecialchars($spouse_name) ?>">
            </div>

            <div class="form-group">
                <label for="spouse_age">Age:</label>
                <input type="number" name="spouse_age" value="<?= htmlspecialchars($spouse_age) ?>">
            </div>

            <div class="form-group">
                <label for="spouse_birthday">Birthday:</label>
                <input type="date" name="spouse_birthday" value="<?= htmlspecialchars($spouse_birthday) ?>">
            </div>

            <div class="form-group">
                <label for="spouse_address">Address:</label>
                <input type="text" name="spouse_address" value="<?= htmlspecialchars($spouse_address) ?>">
            </div>

            <div class="form-group">
                <label for="spouse_work">Work:</label>
                <input type="text" name="spouse_work" value="<?= htmlspecialchars($spouse_work) ?>">
            </div>

            <!-- Child Details -->
            <div class="section-title">
                <h3>Child Details</h3>
            </div>
            <div class="form-group">
                <label for="child_name">Child's Name:</label>
                <input type="text" name="child_name" value="<?= htmlspecialchars($child_name) ?>">
            </div>

            <div class="form-group">
                <label for="child_age">Age:</label>
                <input type="number" name="child_age" value="<?= htmlspecialchars($child_age) ?>">
            </div>

            <div class="form-group">
                <label for="child_grade_degree">Grade/Degree:</label>
                <input type="text" name="child_grade_degree" value="<?= htmlspecialchars($child_grade_degree) ?>">
            </div>

            <!-- File Upload for Verification -->
            <div class="section-title">
                <h3>Verification Document</h3>
            </div>
            <div class="form-group">
                <label for="document_type">Document Type:</label>
                <select name="document_type" required>
                    <option value="Valid ID">Valid ID</option>
                    <option value="Birth Certificate">Birth Certificate</option>
                </select>
            </div>

            <div class="form-group">
                <label for="verification_doc">Upload Document:</label>
                <input type="file" name="verification_doc" required>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>