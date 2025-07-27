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
            background-color: #F9F6F0;
        }
        .grid-container{
    display: grid;
    grid-template-columns: 1fr;
    grid-template-rows:  3fr;
    grid-template-areas:
    " header "
    " main"
    "footer";
    height: 100vh;
}
        /* Navbar styles */
        .navbar {
            background-color: #0C2D48;
            overflow: hidden;
            padding: 10px;
            position: fixed;
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
            background-color: #2F5061;
        }

        .navbar a.login {
            margin-right: 20px;
        }

        /* Main content styles */
        .main-container{
            position:relative;
            width:auto; 
            height: 100%;
        } 
       

        .first {
            position: absolute; /* Ensure it's above the .main */
            top: 10%; /* Adjust positioning as needed */
            left: 50%;
            transform: translateX(-50%);
            padding: 19px;
            text-align: center;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 800px;
            z-index: 10;
        }

        .first h1 {
            color: #0C2D48;
            font-size: 36px;
        }

        .first p {
            font-size: 18px;
            color: #7f8c8d;
            
        }
        .main{
            background-image: url('backg.jpg'); /* Set the background image */
            background-size: cover; /* Scale image to cover the element */
            background-position: top; /* Show the top part of the image */
            background-repeat: no-repeat; /* Prevent tiling */
            width: 100%;
            height: 93%; /* Full viewport height */
            position: relative;
            overflow: hidden;
        }
       
        
    .main-1 {
    padding: 20px;
    background-color: #F9F6F0;
    width: 97%;
    height: 97%; /* Adjust height as per content */ 
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
            background-color: #0C2D48;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>
<body>
<div class="grid-container">
    <!-- Navbar -->
    <div class="navbar">
        <a href="login.php" class="login">Login</a>
    </div>

    <!-- Main Content -->
     <main class="main-container">
        <div class="main">
    <div class="first">
        <h1>Welcome to the Barangay System</h1>
        <p>Manage your profile, request documents, and access official services with ease.</p>
    </div></div>
    <div class=main-1>
    <div class="second"></div>
    </div>
    </main>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Barangay System. All Rights Reserved.</p>
    </div>

</body>
</html>