<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '0'; // If no user is logged in, return 0
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to get the count of unread notifications
$notification_sql = "SELECT COUNT(*) AS unread_count FROM Notifications WHERE user_id = ? AND status = 'unread'";
$notification_stmt = $conn->prepare($notification_sql);
$notification_stmt->bind_param("i", $user_id);
$notification_stmt->execute();
$notification_result = $notification_stmt->get_result();
$row = $notification_result->fetch_assoc();

// Output the unread notification count
echo $row['unread_count'];

$notification_stmt->close();
$conn->close();
?>
