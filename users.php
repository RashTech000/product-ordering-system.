<?php
include('admin_check.php');
include('connection.php');

$query = mysqli_query($connection, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('admin_nav.php'); ?>

    <div class="container">

        <?php include('message.php'); ?>

        <h1>Users</h1>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo $row['role']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td class="actions">
                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn small">Edit</a>

                    <?php if ($row['id'] != $_SESSION['user_id']) { ?>
                    <form action="action.php" method="POST" onsubmit="return confirm('Delete this user and all his orders?');">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_user" class="small danger">Delete</button>
                    </form>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>

        </table>

    </div>

</body>
</html>
