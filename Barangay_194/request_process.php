<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in the session
    $document_type = $_POST['document_type'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $purpose = $_POST['purpose'];

    // Prepare and bind the document request insertion
    $stmt = $conn->prepare("INSERT INTO DocumentRequests (user_id, document_type, name, age, address, purpose, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("isssss", $user_id, $document_type, $name, $age, $address, $purpose);

    if ($stmt->execute()) {
        // Insert a notification for the user
        $message = "Your document request has been submitted."; // Notification message
        $notification_status = 'unread'; // Mark the notification as unread initially
        $notification_stmt = $conn->prepare("INSERT INTO Notifications (user_id, message, status) VALUES (?, ?, ?)");
        $notification_stmt->bind_param("iss", $user_id, $message, $notification_status);
        $notification_stmt->execute();
        $notification_stmt->close();

        // Success message
        echo "Document request submitted successfully. Returning to Dashboard.<br>";

        // Redirect to dashboard after 3 seconds
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'dashboard.php'; // Redirect to dashboard
                }, 2000); // 2 seconds delay
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>