<?php
include('user_check.php');
include('connection.php');

$user_id = (int)$_SESSION['user_id'];

$sql = "SELECT SUM(total_amount) AS spent FROM orders WHERE user_id = '$user_id' AND status != 'cancelled'";
$query = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($query);
$total_spent = $row['spent'];

if ($total_spent == "") {
    $total_spent = 0;
}

$query = mysqli_query($connection, "SELECT COUNT(*) AS items FROM cart WHERE user_id = '$user_id'");
$row = mysqli_fetch_assoc($query);
$cart_items = $row['items'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('user_nav.php'); ?>

    <div class="container">

        <?php include('message.php'); ?>

        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>

        <div class="cards">

            <div class="card">
                <h3>Total Spend</h3>
                <p>TZS <?php echo number_format($total_spent, 2); ?></p>
            </div>

            <div class="card">
                <h3>Products in Cart</h3>
                <p><?php echo $cart_items; ?></p>
            </div>

        </div>

        <div class="buttons">
            <a href="user_product.php" class="btn">Browse Products</a>
            <a href="cart.php" class="btn">Go to Cart and Place Order</a>
        </div>

    </div>

</body>
</html>