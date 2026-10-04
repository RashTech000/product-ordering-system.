<?php
include('user_check.php');
include('connection.php');

$user_id = (int)$_SESSION['user_id'];

$query = mysqli_query($connection, "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC");
$count = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('user_nav.php'); ?>

    <div class="container">

        <?php include('message.php'); ?>

        <h1>My Orders</h1>

        <?php if ($count == 0) { ?>

            <p>You have no orders yet.</p>

        <?php } else { ?>

        <table>
            <tr>
                <th>Order</th>
                <th>Items</th>
                <th>Total</th>
                <th>Date</th>
                <th>Status</th>
            </tr>

            <?php while ($order = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td>#<?php echo $order['id']; ?></td>
                <td>
                    <?php
                    $order_id = $order['id'];
                    $sql = "SELECT products.name, order_items.quantity
                            FROM order_items
                            JOIN products ON order_items.product_id = products.id
                            WHERE order_items.order_id = '$order_id'";
                    $items = mysqli_query($connection, $sql);

                    while ($item = mysqli_fetch_assoc($items)) {
                        echo htmlspecialchars($item['name']) . " x " . $item['quantity'] . "<br>";
                    }
                    ?>
                </td>
                <td>TZS <?php echo number_format($order['total_amount'], 2); ?></td>
                <td><?php echo $order['created_at']; ?></td>
                <td><span class="status"><?php echo $order['status']; ?></span></td>
            </tr>
            <?php } ?>

        </table>

        <?php } ?>

    </div>

</body>
</html>
