<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Validate and sanitize form inputs
$first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
$middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : '';
$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
$age = isset($_POST['age']) ? (int)$_POST['age'] : 0;
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';
$birthplace = isset($_POST['birthplace']) ? $_POST['birthplace'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$district = isset($_POST['district']) ? $_POST['district'] : '';
$precinct_number = isset($_POST['precinct_number']) ? $_POST['precinct_number'] : '';
$citizenship = isset($_POST['citizenship']) ? $_POST['citizenship'] : '';
$civil_status = isset($_POST['civil_status']) ? $_POST['civil_status'] : '';
$contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : '';
$work = isset($_POST['work']) ? $_POST['work'] : '';
$spouse_name = isset($_POST['spouse_name']) ? $_POST['spouse_name'] : '';
$spouse_age = isset($_POST['spouse_age']) ? (int)$_POST['spouse_age'] : 0;
$spouse_birthday = isset($_POST['spouse_birthday']) ? $_POST['spouse_birthday'] : '';
$spouse_address = isset($_POST['spouse_address']) ? $_POST['spouse_address'] : '';
$spouse_work = isset($_POST['spouse_work']) ? $_POST['spouse_work'] : '';
$child_name = isset($_POST['child_name']) ? $_POST['child_name'] : '';
$child_age = isset($_POST['child_age']) ? (int)$_POST['child_age'] : 0;
$child_grade_degree = isset($_POST['child_grade_degree']) ? $_POST['child_grade_degree'] : '';
$profile_status = 'Pending'; // Set profile status to Pending

// Update the user's profile in ResidentProfiles
$sql_check = "SELECT * FROM ResidentProfiles WHERE user_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Update existing profile
    $sql_update = "
        UPDATE ResidentProfiles
        SET first_name = ?, middle_name = ?, last_name = ?, age = ?, gender = ?, 
            birthday = ?, address = ?, district = ?, precinct_number = ?, 
            citizenship = ?, civil_status = ?, contact_number = ?, work = ?, 
            spouse_name = ?, spouse_age = ?, spouse_birthday = ?, 
            spouse_address = ?, spouse_work = ?, child_name = ?, 
            child_age = ?, child_grade_degree = ?, birthplace = ?, 
            profile_status = ?
        WHERE user_id = ?
    ";
    $stmt_update = $conn->prepare($sql_update);
    // Debugging: Print the SQL query and variables before executing
    echo "SQL Update: $sql_update<br>";
    $stmt_update->bind_param(
        "sssisssssissssisisssssi",
        $first_name, $middle_name, $last_name, $age, $gender, $birthday, $address, 
        $district, $precinct_number, $citizenship, $civil_status, $contact_number, 
        $work, $spouse_name, $spouse_age, $spouse_birthday, $spouse_address, 
        $spouse_work, $child_name, $child_age, $child_grade_degree, 
        $birthplace, $profile_status, $user_id
    );
    $stmt_update->execute();
} else {
    // Insert new profile
    $sql_insert = "
        INSERT INTO ResidentProfiles (user_id, first_name, middle_name, last_name, age, gender, 
            birthday, address, district, precinct_number, citizenship, civil_status, contact_number, 
            work, spouse_name, spouse_age, spouse_birthday, spouse_address, spouse_work, 
            child_name, child_age, child_grade_degree, birthplace, profile_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt_insert = $conn->prepare($sql_insert);
    // Debugging: Print the SQL query and variables before executing
    echo "SQL Insert: $sql_insert<br>";
    $sql_insert = "
    INSERT INTO residentprofiles (
        user_id, address, birthplace, contact_number, age, birthday, gender, district, citizenship, 
        civil_status, work, precinct_number, spouse_name, spouse_age, spouse_birthday, 
        spouse_address, spouse_work, child_name, child_age, child_grade_degree, first_name, 
        middle_name, last_name, profile_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt_insert = $conn->prepare($sql_insert);

$stmt_insert->bind_param(
    "isssisssssssissssssissss",
    $user_id, $address, $birthplace, $contact_number, $age, $birthday, $gender, 
    $district, $citizenship, $civil_status, $work, $precinct_number, $spouse_name, 
    $spouse_age, $spouse_birthday, $spouse_address, $spouse_work, $child_name, 
    $child_age, $child_grade_degree, $first_name, $middle_name, $last_name, 
    $profile_status
);

$stmt_insert->execute();
}

// Handle file upload for verification document
if (isset($_FILES['verification_doc']) && $_FILES['verification_doc']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    $file_type = $_FILES['verification_doc']['type'];
    $max_size = 5 * 1024 * 1024; // Max size 5MB

    if (in_array($file_type, $allowed_types) && $_FILES['verification_doc']['size'] <= $max_size) {
        $file_name = $_FILES['verification_doc']['name'];
        $file_tmp = $_FILES['verification_doc']['tmp_name'];
        $upload_dir = "uploads/";
        $file_path = $upload_dir . basename($file_name);

        if (move_uploaded_file($file_tmp, $file_path)) {
            // Insert document information into VerificationDocuments
            $document_status = 'Pending'; // Default status
            $stmt_doc = $conn->prepare("INSERT INTO VerificationDocuments (user_id, document_type, document_path, status) VALUES (?, ?, ?, ?)");
            $document_type = $_POST['document_type']; // Capture document type from form input
            $stmt_doc->bind_param("isss", $user_id, $document_type, $file_path, $document_status);
            if (!$stmt_doc->execute()) {
                $_SESSION['error_message'] = "Error saving document to database: " . $stmt_doc->error;
            }
            $stmt_doc->close();
        } else {
            $_SESSION['error_message'] = "Error uploading the document.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid file type or file too large.";
    }
}

// Update account_status in Users table to Pending if profile status is Pending
$stmt_user = $conn->prepare("UPDATE Users SET account_status = 'Pending' WHERE user_id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$stmt_user->close();

$_SESSION['success_message'] = "Upload complete. Please wait for admin approval.";
header("Location: dashboard.php"); // Redirect to dashboard
exit();
?>
