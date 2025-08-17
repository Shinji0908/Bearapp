
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay System</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Navbar styles */
        .navbar {
            background-color: #2c3e50;
            overflow: hidden;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            float: right;
            font-size: 16px;
            border-radius: 4px;
        }

        .navbar a:hover {
            background-color: #34495e;
        }

        .navbar a.login {
            margin-right: 20px;
        }

        /* Main content styles */
        .main-content {
            margin-top: 80px;
            text-align: center;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 100px auto;
        }

        .main-content h1 {
            color: #2c3e50;
            font-size: 36px;
        }

        .main-content p {
            font-size: 18px;
            color: #7f8c8d;
        }

        .btn {
            padding: 12px 20px;
            margin-top: 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <a href="login.php" class="login">Login</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome to Bear App</h1>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 Barangay System. All Rights Reserved.</p>
    </div>

</body>
</html>
