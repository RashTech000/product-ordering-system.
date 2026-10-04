<?php
include('admin_check.php');
include('connection.php');

$query = mysqli_query($connection, "SELECT COUNT(*) AS total FROM products");
$row = mysqli_fetch_assoc($query);
$total_products = $row['total'];

$query = mysqli_query($connection, "SELECT SUM(total_amount) AS revenue FROM orders WHERE status != 'cancelled'");
$row = mysqli_fetch_assoc($query);
$total_revenue = $row['revenue'];

if ($total_revenue == "") {
    $total_revenue = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include('admin_nav.php'); ?>

    <div class="container">

        <?php include('message.php'); ?>

        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>

        <div class="cards">

            <div class="card">
                <h3>Total Products</h3>
                <p><?php echo $total_products; ?></p>
            </div>

            <div class="card">
                <h3>Total Revenue</h3>
                <p>TZS <?php echo number_format($total_revenue, 2); ?></p>
            </div>

        </div>

    </div>

</body>
</html>
