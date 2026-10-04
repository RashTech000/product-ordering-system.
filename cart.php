<?php
include('user_check.php');
include('connection.php');

$user_id = (int)$_SESSION['user_id'];

$sql = "SELECT cart.id, cart.quantity, products.name, products.price, products.stock
        FROM cart
        JOIN products ON cart.product_id = products.id
        WHERE cart.user_id = '$user_id'
        ORDER BY cart.id DESC";
$query = mysqli_query($connection, $sql);

$count = mysqli_num_rows($query);
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('user_nav.php'); ?>

    <div class="container">

        <?php include('message.php'); ?>

        <h1>My Cart</h1>

        <?php if ($count == 0) { ?>

            <p>Your cart is empty. <a href="user_product.php">Go to products</a></p>

        <?php } else { ?>

        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal (Price x Quantity)</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($query)) {
                $subtotal = $row['price'] * $row['quantity'];
                $total = $total + $subtotal;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>TZS <?php echo number_format($row['price'], 2); ?></td>
                <td>
                    <form action="action.php" method="POST" class="inline">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="update_cart" value="1">
                        <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1" max="<?php echo $row['stock']; ?>" class="qty" onchange="this.form.submit()">
                        <button type="submit" class="small">Update</button>
                    </form>
                </td>
                <td>TZS <?php echo number_format($subtotal, 2); ?></td>
                <td>
                    <form action="action.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="remove_cart" class="small danger">Remove</button>
                    </form>
                </td>
            </tr>
            <?php } ?>

            <tr class="total-row">
                <td colspan="3"><strong>Total</strong></td>
                <td colspan="2"><strong>TZS <?php echo number_format($total, 2); ?></strong></td>
            </tr>
        </table>

        <form action="action.php" method="POST" onsubmit="return confirm('Place this order?');">
            <button type="submit" name="checkout">Checkout</button>
        </form>

        <?php } ?>

    </div>

</body>
</html>