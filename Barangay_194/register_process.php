<?php
include 'db_connection.php';

// Retrieve form data
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];

// Insert into Users table
$sql = "INSERT INTO Users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $email, $password, $first_name, $last_name);

if ($stmt->execute()) {
    // Registration successful
    $message = "Registration successful! Please log in to complete your profile.";
    $link = "login.php";
} else {
    $message = "Error: " . $conn->error;
    $link = "register.php"; // Redirect back to registration page on error
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ecf0f1;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #2c3e50;
        }

        .container {
            text-align: center;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 90%;
        }

        .message {
            font-size: 18px;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            text-decoration: none;
            background-color: #2c3e50;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #1a252f;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="message"><?= htmlspecialchars($message) ?></p>
        <a href="<?= htmlspecialchars($link) ?>">Go back</a>
    </div>
</body>
</html>