<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: index.php");
    exit();
}

// Check if the required parameters are set
if (isset($_GET['profile_id']) && isset($_GET['action'])) {
    $profile_id = intval($_GET['profile_id']);
    $action = $_GET['action'];

    // Validate the action parameter
    if (!in_array($action, ['approve', 'reject'])) {
        die("Invalid action. Action must be either 'approve' or 'reject'.");
    }

    // Fetch the user_id associated with the profile_id
    $stmt = $conn->prepare("SELECT user_id FROM ResidentProfiles WHERE profile_id = ?");
    $stmt->bind_param("i", $profile_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Error: Profile not found for profile_id = $profile_id.");
    }

    // Fetch user_id
    $user = $result->fetch_assoc();
    $user_id = $user['user_id'];
    $stmt->close();

    // Ensure user_id is valid
    if (empty($user_id)) {
        die("Error: Could not retrieve user_id for profile_id = $profile_id.");
    }

    // Check if a document exists for the user
    $stmt_check = $conn->prepare("SELECT document_id FROM VerificationDocuments WHERE user_id = ?");
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $document_found = $result_check->num_rows > 0;
    $stmt_check->close();

    // Determine the status based on the action
    $account_status = $action === 'approve' ? 'Approved' : 'Rejected';
    $profile_status = $action === 'approve' ? 'Approved' : 'Rejected';
    $remarks = $action === 'approve' ? 'Profile approved' : 'Profile rejected';

    // Update account_status in Users table
    $stmt = $conn->prepare("UPDATE Users SET account_status = ? WHERE user_id = ?");
    $stmt->bind_param("si", $account_status, $user_id);
    if (!$stmt->execute()) {
        die("Error updating Users table: " . $stmt->error);
    }
    $stmt->close();

    // Update profile_status in ResidentProfiles table
    $stmt = $conn->prepare("UPDATE ResidentProfiles SET profile_status = ? WHERE user_id = ?");
    $stmt->bind_param("si", $profile_status, $user_id);
    if (!$stmt->execute()) {
        die("Error updating ResidentProfiles table: " . $stmt->error);
    }
    $stmt->close();

    // If a document exists, update its status
    if ($document_found) {
        $stmt = $conn->prepare("UPDATE VerificationDocuments SET status = ? WHERE user_id = ?");
        $stmt->bind_param("si", $account_status, $user_id);
        if (!$stmt->execute()) {
            die("Error updating VerificationDocuments table: " . $stmt->error);
        }
        $stmt->close();
    } else {
        echo "No document found for user_id = $user_id. Skipping document updates.";
    }

    // Log the admin action in AdminLogs
    $admin_id = $_SESSION['user_id'];
    $stmt_log = $conn->prepare("INSERT INTO AdminLogs (admin_id, user_id, action, remarks) VALUES (?, ?, ?, ?)");
    $stmt_log->bind_param("iiis", $admin_id, $user_id, $action, $remarks);
    if (!$stmt_log->execute()) {
        die("Error inserting into AdminLogs: " . $stmt_log->error);
    }
    $stmt_log->close();

    // Redirect back with a success message
    $_SESSION['message'] = $action === 'approve' ? "Profile approved successfully." : "Profile rejected successfully.";
    header("Location: pending_profiles.php");
    exit();
} else {
    die("Invalid request. Ensure both 'profile_id' and 'action' parameters are set.");
}
?>
