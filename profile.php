<?php
include('user_check.php');
include('connection.php');

$user_id = (int)$_SESSION['user_id'];

$query = mysqli_query($connection, "SELECT * FROM users WHERE id = '$user_id'");
$row = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('user_nav.php'); ?>

    <div class="container">

        <?php include('message.php'); ?>

        <h1>My Profile</h1>

        <form action="action.php" method="POST" class="form-box">

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">New Password (leave empty to keep the old one)</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="confirm">Confirm New Password</label>
                <input type="password" id="confirm" name="confirm">
            </div>

            <button type="submit" name="update_profile">Save Profile</button>

        </form>

    </div>

</body>
</html>
