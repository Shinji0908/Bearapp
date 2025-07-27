<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: index.php"); // Redirect to user login
    exit();
}

// Check if the ID and action are set
if (isset($_GET['id']) && isset($_GET['action'])) {
    $request_id = $_GET['id'];
    $action = $_GET['action'];
    
    // Determine the status based on the action
    if ($action === 'approve') {
        $status = 'Approved';
        $message = "Your document request has been approved.";
        $alertMessage = "Document request approved successfully!";
    } elseif ($action === 'reject') {
        $status = 'Rejected';
        $message = "Your document request has been rejected.";
        $alertMessage = "Document request rejected successfully!";
    } else {
        // Invalid action
        header("Location: request_documents_admin.php");
        exit();
    }

    // Fetch the user ID associated with the document request
    $sql = "SELECT user_id, document_type, name FROM DocumentRequests WHERE request_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        $document_type = $row['document_type'];
        $user_name = $row['name'];
        
        // Update the status of the document request
        $update_sql = "UPDATE DocumentRequests SET status = ? WHERE request_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $status, $request_id);
        $update_stmt->execute();
        
        // Insert a notification for the user in the Notifications table
        $notification_sql = "INSERT INTO Notifications (user_id, message) VALUES (?, ?)";
        $notification_stmt = $conn->prepare($notification_sql);
        $notification_stmt->bind_param("is", $user_id, $message);
        $notification_stmt->execute();

        // Redirect to admin page with success alert message
        header("Location: request_documents_admin.php?status=$alertMessage");
        exit();
    } else {
        // If the request ID is invalid
        echo "Invalid request.";
    }
}
?>
