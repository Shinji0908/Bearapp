<?php
include 'db_connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch approval status of the profile
$sql = "
    SELECT vd.status AS document_status
    FROM VerificationDocuments vd
    WHERE vd.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$status_data = $result->fetch_assoc();

$document_status = $status_data ? $status_data['document_status'] : 'Pending'; // Default to 'Pending' if no status

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin-left: 250px;
            padding: 20px;
        }
        h1 {
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <h1>Your Profile Status</h1>
    <p>Your profile approval status: <strong><?php echo htmlspecialchars($document_status); ?></strong></p>

    <a href="dashboard.html">Back to Dashboard</a>
</body>
</html>
