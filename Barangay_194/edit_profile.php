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
        .child-form { margin-bottom: 10px; }
        .child-details { margin-bottom: 20px; }
        button { margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Edit Profile</h1>
    <form action="edit_profile_process.php" method="POST" enctype="multipart/form-data">
        <!-- Personal Information -->
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($first_name) ?>" required><br><br>

        <label for="middle_name">Middle Name:</label>
        <input type="text" name="middle_name" value="<?= htmlspecialchars($middle_name) ?>" required><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($last_name) ?>" required><br><br>

        <label for="age">Age:</label>
        <input type="number" name="age" value="<?= htmlspecialchars($age) ?>" required><br><br>

        <label for="gender">Gender:</label>
        <select name="gender" required>
            <option value="Male" <?= $gender === 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $gender === 'Female' ? 'selected' : '' ?>>Female</option>
        </select><br><br>

        <label for="birthday">Birthday:</label>
        <input type="date" name="birthday" value="<?= htmlspecialchars($birthday) ?>" required><br><br>

        <label for="birthplace">Birthplace:</label>
        <input type="text" name="birthplace" value="<?= htmlspecialchars($birthplace) ?>" required><br><br>

        <label for="address">Address:</label>
        <input type="text" name="address" value="<?= htmlspecialchars($address) ?>" required><br><br>

        <label for="district">District:</label>
        <input type="text" name="district" value="<?= htmlspecialchars($district) ?>" required><br><br>

        <label for="precinct_number">Precinct Number:</label>
        <input type="text" name="precinct_number" value="<?= htmlspecialchars($precinct_number) ?>" required><br><br>

        <label for="citizenship">Citizenship:</label>
        <input type="text" name="citizenship" value="<?= htmlspecialchars($citizenship) ?>" required><br><br>

        <label for="civil_status">Civil Status:</label>
        <select name="gender" required>
            <option value="Single" <?= $civil_status === 'Single' ? 'selected' : '' ?>>Single</option>
            <option value="Marrried" <?= $civil_status === 'Marrried' ? 'selected' : '' ?>>Marrried</option>
            <option value="Widowed" <?= $civil_status === 'Widowed' ? 'selected' : '' ?>>Widowed</option>
        </select><br><br>

        <label for="contact_number">Contact Number:</label>
        <input type="text" name="contact_number" value="<?= htmlspecialchars($contact_number) ?>" required><br><br>

        <label for="work">Work:</label>
        <input type="text" name="work" value="<?= htmlspecialchars($work) ?>"><br><br>

        <!-- Spouse Details -->
        <h3>Spouse Details</h3>
        <label for="spouse_name">Name:</label>
        <input type="text" name="spouse_name" value="<?= htmlspecialchars($spouse_name) ?>"><br><br>

        <label for="spouse_age">Age:</label>
        <input type="number" name="spouse_age" value="<?= htmlspecialchars($spouse_age) ?>"><br><br>

        <label for="spouse_birthday">Birthday:</label>
        <input type="date" name="spouse_birthday" value="<?= htmlspecialchars($spouse_birthday) ?>"><br><br>

        <label for="spouse_address">Address:</label>
        <input type="text" name="spouse_address" value="<?= htmlspecialchars($spouse_address) ?>"><br><br>

        <label for="spouse_work">Work:</label>
        <input type="text" name="spouse_work" value="<?= htmlspecialchars($spouse_work) ?>"><br><br>

        <h3>Child Details </h3>
        <form id="surveyForm">
            <div id="childContainer">
                <div class="child-form" id="child-1">
                <h3>Child 1</h3>
                <label for="child_name">Child's Name:</label>
                <input type="text" id="child_name-${childCount}" name="child_name" value="<?= htmlspecialchars($child_name) ?>"><br><br>

                <label for= "child_age">Age:</label>
                <input type="number" id="child_age-${childCount}" name="child_age" value="<?= htmlspecialchars($child_age) ?>"><br><br>
                
                <label for="child_grade_degree">Grade/Degree:</label>
                <input type="text" id="child_grade_degree-${childCount}" name="child_grade_degree" value="<?= htmlspecialchars($child_grade_degree) ?>"><br><br>
                
                <button type="button" class="remove-button" data-id="child-1">Remove</button>
                </div>
            </div>
            <button type="button" id="addChildButton">Add Another Child</button>
            <br><br>
        </form>

    <script>
        let childCount = 1;

        document.getElementById('addChildButton').addEventListener('click', () => {
        childCount++;
        const container = document.getElementById('childContainer');

        // Child form creation
        const newChildForm = document.createElement('div');
        newChildForm.classList.add('child-form');
        newChildForm.id = `child-${childCount}`;
        newChildForm.innerHTML = `
            <h3>Child ${childCount}</h3>
            <label for="child_name-${childCount}">Name:</label>
            <input type="text" id="child_name-${childCount}" name="child_name[]" required><br><br>
            
            <label for="child_age-${childCount}">Age:</label>
            <input type="number" id="child_age-${childCount}" name="child_age[]" required><br><br>
            
            <label for="child_grade_degree-${childCount}">Grade/Degree:</label>
            <input type="text" id="child_grade_degree-${childCount}" name="child_grade_degree[]" required><br><br>
            
            <button type="button" class="remove-button" data-id="child-${childCount}">Remove</button>
        `;
        container.appendChild(newChildForm);

        // Attach remove event to the new button
        attachRemoveEvent();
    });

        // Attach remove event to all buttons
        function attachRemoveEvent() {
            document.querySelectorAll('.remove-button').forEach(button => {
                button.onclick = () => {
                    const childId = button.getAttribute('data-id');
                    const childElement = document.getElementById(childId);
                    if (childElement) {
                        childElement.remove();
                        renumberChildren();
                    }
                };
            });
        }

        // Renumber children after removal
            function renumberChildren() {
                const childForms = document.querySelectorAll('.child-form');
                childForms.forEach((childForm, index) => {
                    const childNumber = index + 1;
                    childForm.id = `child-${childNumber}`;
                    childForm.querySelector('h3').textContent = `Child ${childNumber}`;
                    childForm.querySelector('[for^="child_name"]').setAttribute('for', `child_name-${childNumber}`);
                    childForm.querySelector('[id^="child_name"]').id = `child_name-${childNumber}`;
                
                    childForm.querySelector('[for^="child_age"]').setAttribute('for', `child_age-${childNumber}`);
                    childForm.querySelector('[id^="child_age"]').id = `child_age-${childNumber}`;

                    childForm.querySelector('[for^="child_grade_degree"]').setAttribute('for', `child_grade_degree-${childNumber}`);
                    childForm.querySelector('[id^="child_grade_degree"]').id = `child_grade_degree-${childNumber}`;

                    childForm.querySelector('.remove-button').setAttribute('data-id', `child-${childNumber}`);
                });

        // Update childCount to reflect the current number of children
            childCount = childForms.length;
            }

            attachRemoveEvent();

    </script>

        <!-- File Upload for Verification -->
        <h3>Verification Document</h3>
        <label for="document_type">Document Type:</label>
        <select name="document_type" required>
            <option value="Valid ID">Valid ID</option>
            <option value="Birth Certificate">Birth Certificate</option>
        </select><br><br>

        <label for="verification_doc">Upload Document:</label>
        <input type="file" name="verification_doc" required><br><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
