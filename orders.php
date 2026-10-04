<?php
include('admin_check.php');
include('connection.php');

$sql = "SELECT orders.*, users.name AS user_name
        FROM orders
        JOIN users ON orders.user_id = users.id
        ORDER BY orders.id DESC";
$query = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('admin_nav.php'); ?>

    <div class="container">

        <?php include('message.php'); ?>

        <h1>Orders</h1>

        <table>
            <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Date</th>
                <th>Status</th>
            </tr>

            <?php while ($order = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td>#<?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['user_name']); ?></td>
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
                <td>
                    <form action="action.php" method="POST" class="inline">
                        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                        <select name="status">
                            <option value="pending" <?php if ($order['status'] == 'pending') { echo "selected"; } ?>>pending</option>
                            <option value="paid" <?php if ($order['status'] == 'paid') { echo "selected"; } ?>>paid</option>
                            <option value="shipped" <?php if ($order['status'] == 'shipped') { echo "selected"; } ?>>shipped</option>
                            <option value="delivered" <?php if ($order['status'] == 'delivered') { echo "selected"; } ?>>delivered</option>
                            <option value="cancelled" <?php if ($order['status'] == 'cancelled') { echo "selected"; } ?>>cancelled</option>
                        </select>
                        <button type="submit" name="update_order" class="small">Save</button>
                    </form>
                </td>
            </tr>
            <?php } ?>

        </table>

    </div>

</body>
</html>
