<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check in the 'Users' table
    $sql = "SELECT user_id, password FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found in 'Users' table
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session variables for user
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_type'] = 'User'; // Specify the user type as 'User'
            header("Location: dashboard.html"); // Redirect to user dashboard
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        // Check in the 'Admins' table
        $sql = "SELECT user_id, password FROM Admins WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Admin found in 'Admins' table
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                // Set session variables for admin
                $_SESSION['user_id'] = $admin['user_id'];
                $_SESSION['user_type'] = 'Admin'; // Specify the user type as 'Admin'
                header("Location: admin_dashboard.php"); // Redirect to admin dashboard
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No account found with that email.";
        }
    }

    $stmt->close();
}
$conn->close();
?>
