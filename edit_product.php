<?php
include('admin_check.php');
include('connection.php');

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = (int)$_GET['id'];

$query = mysqli_query($connection, "SELECT * FROM products WHERE id = '$id'");
$row = mysqli_fetch_assoc($query);

if (!$row) {
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('admin_nav.php'); ?>

    <div class="container">

        <?php include('message.php'); ?>

        <h1>Edit Product</h1>

        <form action="action.php" method="POST" enctype="multipart/form-data" class="form-box">

            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($row['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" min="0.01" value="<?php echo $row['price']; ?>" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" min="0" value="<?php echo $row['stock']; ?>" required>
            </div>

            <div class="form-group">
                <label for="image">Image (leave empty to keep the current image)</label>
                <?php if ($row['image'] != "") { ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" class="thumb" alt="">
                <?php } ?>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" name="update_product">Update Product</button>

        </form>

    </div>

</body>
</html>
