<?php
include 'db_connection.php';
$data = json_decode(file_get_contents('php://input'), true);

$event_id = $data['id']; // Get the event ID for deletion

// Delete the event from the database
$query = "DELETE FROM events WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
