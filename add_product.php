<?php
include('admin_check.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('admin_nav.php'); ?>

    <div class="container">

        <?php include('message.php'); ?>

        <h1>Add Product</h1>

        <form action="action.php" method="POST" enctype="multipart/form-data" class="form-box">

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" min="0.01" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" min="0" value="10" required>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" name="add_product">Save Product</button>

        </form>

    </div>

</body>
</html>
