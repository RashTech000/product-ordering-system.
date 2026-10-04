<?php
include('admin_check.php');
include('connection.php');

$query = mysqli_query($connection, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('admin_nav.php'); ?>

    <div class="container">

        <?php include('message.php'); ?>

        <h1>Products</h1>
        <a href="add_product.php" class="btn">+ Add Product</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td>
                    <?php if ($row['image'] != "") { ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" class="thumb" alt="">
                    <?php } ?>
                </td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>TZS <?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo $row['stock']; ?></td>
                <td class="actions">
                    <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn small">Edit</a>

                    <form action="action.php" method="POST" onsubmit="return confirm('Delete this product?');">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_product" class="small danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>

        </table>

    </div>

</body>
</html>
