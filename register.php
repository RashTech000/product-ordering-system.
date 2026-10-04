<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-page">

    <div class="form-box">

        <h1>Register</h1>
        <p class="subtitle">Please fill in your details below</p>

        <?php include('message.php'); ?>

        <form action="action.php" method="POST">

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="example@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password (at least 6 characters)</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="form-group">
                <label for="confirm">Confirm Password</label>
                <input type="password" id="confirm" name="confirm" placeholder="Enter the password again" required>
            </div>

            <button type="submit" name="register">Register</button>

        </form>

        <p class="link">Already have an account? <a href="login.php">Login</a></p>

    </div>

</body>
</html>
