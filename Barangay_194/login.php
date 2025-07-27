<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay 194</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h1>Welcome to Barangay 194</h1>
            <form action="login_process.php" method="post">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Sign in</button>
                <p class="signup">Don’t have an account? <a href="register.html">Sign up</a></p>
            </form>
        </div>
        <div class="Barangay">
            <img src="Barangay Logo.png" alt="Barangay Logo">
        </div>
    </div>
</body>
</html>
