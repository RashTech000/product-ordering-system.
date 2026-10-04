<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-page">

    <div class="form-box">

        <h1>Login</h1>
        <p class="subtitle">Please login to continue</p>

        <?php include('message.php'); ?>

        <form action="action.php" method="POST">

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="example@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" name="login">Login</button>

        </form>

        <p class="link">No account? <a href="register.php">Register</a></p>

    </div>

</body>
</html>
