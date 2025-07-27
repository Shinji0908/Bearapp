<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: index.php"); // Redirect to user login
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // Hash the password before saving it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new admin into the database
    $sql = "INSERT INTO Admins (email, password, first_name, last_name) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $email, $hashed_password, $first_name, $last_name);

    if ($stmt->execute()) {
        echo "<p>New admin created successfully!</p>";
    } else {
        echo "<p>Error creating admin. Please try again.</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: "Outfit", serif;
        background-color: #F9F6F0;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
        width: 400px;
        transition: transform 0.3s ease-in-out;
    }

    .container:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        color: #2c3e50;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-size: 16px;
        margin-bottom: 5px;
        display: block;
        color: #34495e;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s ease-in-out;
    }

    .form-group input:focus {
        border-color: #2c3e50;
        outline: none;
    }

    .form-group input[type="submit"] {
        background-color: #2c3e50;
        color: white;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease-in-out;
    }

    .form-group input[type="submit"]:hover {
        background-color: #34495e;
    }
</style>
</head>
<body>

    <div class="container">
        <h2>Create New Admin</h2>
        <form action="create_admin.php" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Create Admin">
            </div>
        </form>
        <div class="back-btn">
            <a href="admin_dashboard.php">Back to Admin Dashboard</a>
        </div>
    </div>

</body>
</html>
