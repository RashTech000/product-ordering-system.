<?php
include('user_check.php');
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

    <?php include('user_nav.php'); ?>

    <div class="container">

        <?php include('message.php'); ?>

        <h1>Products</h1>

        <div class="grid">

            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <div class="product">

                <?php if ($row['image'] != "") { ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="">
                <?php } else { ?>
                    <div class="no-image">No image</div>
                <?php } ?>

                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p class="desc"><?php echo htmlspecialchars($row['description']); ?></p>
                <p class="price">TZS <?php echo number_format($row['price'], 2); ?></p>

                <?php if ($row['stock'] > 0) { ?>

                    <p class="stock"><?php echo $row['stock']; ?> in stock</p>

                    <form action="action.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>

                <?php } else { ?>

                    <p class="out">Out of stock</p>

                <?php } ?>

            </div>
            <?php } ?>

        </div>

    </div>

</body>
</html>