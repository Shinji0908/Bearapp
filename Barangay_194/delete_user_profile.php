<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete from ResidentProfiles table first
    $sql = "DELETE FROM ResidentProfiles WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        // Now delete from Users table
        $sql2 = "DELETE FROM Users WHERE user_id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $user_id);
        if ($stmt2->execute()) {
            echo "<script>alert('Profile deleted successfully.'); window.location.href = 'view_all_users.php';</script>";
        } else {
            echo "<script>alert('Failed to delete user.'); window.location.href = 'view_all_users.php';</script>";
        }
    } else {
        echo "<script>alert('Failed to delete profile.'); window.location.href = 'view_all_users.php';</script>";
    }

    $stmt->close();
    $stmt2->close();
} else {
    echo "<script>alert('No user selected for deletion.'); window.location.href = 'view_all_users.php';</script>";
}

$conn->close();
?>
