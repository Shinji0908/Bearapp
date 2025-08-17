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
    echo "Registration successful! Please log in to complete your profile.";
    echo "<br><a href='login.php'>Go back to login page</a>";  // Button to go back to login
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
