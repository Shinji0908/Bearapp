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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .status {
            font-size: 1.5em;
            margin: 20px 0;
        }
        .status.pending {
            color: orange;
        }
        .status.approved {
            color: green;
        }
        .status.rejected {
            color: red;
        }
        .icon {
            font-size: 3em;
            margin-bottom: 10px;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <?php if ($document_status == 'Approved'): ?>
                <i class="fas fa-check-circle" style="color: green;"></i>
            <?php elseif ($document_status == 'Rejected'): ?>
                <i class="fas fa-times-circle" style="color: red;"></i>
            <?php else: ?>
                <i class="fas fa-hourglass-half" style="color: orange;"></i>
            <?php endif; ?>
        </div>
        <div class="status <?php echo strtolower($document_status); ?>">
            <?php echo $document_status; ?>
        </div>
        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>