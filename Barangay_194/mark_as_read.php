<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo 'error';
    exit();
}

if (isset($_POST['id'])) {
    $notification_id = $_POST['id'];
    $user_id = $_SESSION['user_id'];

    // Update the notification status to 'read' in the database
    $update_sql = "UPDATE Notifications SET status = 'read' WHERE notification_id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $notification_id, $user_id);

    if ($update_stmt->execute()) {
        echo 'success';  // Return success response
    } else {
        echo 'error';    // Return error response
    }

    $update_stmt->close();
}
$conn->close();
?>
