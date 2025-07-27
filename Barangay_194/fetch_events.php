<?php
include 'db_connection.php';

// Fetch all events from the database
$sql = "SELECT id, title, start_date as start, end_date as end, description FROM events ORDER BY start_date";
$result = $conn->query($sql);

$events = [];

while ($row = $result->fetch_assoc()) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end'],
        'description' => $row['description']
    ];
}

// Return the events in JSON format
echo json_encode($events);
?>
