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
            <form action="login_process.php" method="post">
                <div class="form-group">
                    <label for="email">Username</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Log In</button>
            </form>
        </div>
        <div class="App">
            <img src="Applogo.png" alt="App logo">
        </div>
    </div>
</body>
</html>
