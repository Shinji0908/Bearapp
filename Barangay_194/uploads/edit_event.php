<?php
include 'db_connection.php';
$data = json_decode(file_get_contents('php://input'), true);

$event_id = $data['id'];
$title = $data['title']; // Title is updated

// Update the event in the database
$query = "UPDATE events SET title = ? WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $title, $event_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
