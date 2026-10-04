<?php
include('admin_check.php');
include('connection.php');

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$id = (int)$_GET['id'];

$query = mysqli_query($connection, "SELECT * FROM users WHERE id = '$id'");
$row = mysqli_fetch_assoc($query);

if (!$row) {
    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('admin_nav.php'); ?>

    <div class="container">

        <?php include('message.php'); ?>

        <h1>Edit User</h1>

        <form action="action.php" method="POST" class="form-box">

            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role">
                    <option value="user" <?php if ($row['role'] == 'user') { echo "selected"; } ?>>user</option>
                    <option value="admin" <?php if ($row['role'] == 'admin') { echo "selected"; } ?>>admin</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">New Password (leave empty to keep the old one)</label>
                <input type="password" id="password" name="password">
            </div>

            <button type="submit" name="update_user">Update User</button>

        </form>

    </div>

</body>
</html>
