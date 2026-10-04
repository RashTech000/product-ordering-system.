<?php
session_start();
include('connection.php');




//buttons are for the admin 
if (isset($_POST['add_product']) || isset($_POST['update_product']) || isset($_POST['delete_product'])
    || isset($_POST['update_user']) || isset($_POST['delete_user']) || isset($_POST['update_order'])) {

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        $_SESSION['message'] = "Please login first";
        header("Location: login.php");
        exit();
    }
}

// buttons are for the user 
if (isset($_POST['add_to_cart']) || isset($_POST['update_cart']) || isset($_POST['remove_cart'])
    || isset($_POST['checkout']) || isset($_POST['update_profile'])) {

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
        $_SESSION['message'] = "Please login first";
        header("Location: login.php");
        exit();
    }
}





if (isset($_POST['register'])) {

    $name = mysqli_real_escape_string($connection, trim($_POST['name']));
    $email = mysqli_real_escape_string($connection, trim($_POST['email']));
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($name == "" || $email == "" || $password == "") {
        $_SESSION['message'] = "All fields are required";
        header("Location: register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Email is not valid";
        header("Location: register.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['message'] = "Password must be at least 6 characters";
        header("Location: register.php");
        exit();
    }

    if ($password != $confirm) {
        $_SESSION['message'] = "Passwords do not match";
        header("Location: register.php");
        exit();
    }

    // check if the email is already 
    $check = mysqli_query($connection, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['message'] = "This email is already registered";
        header("Location: register.php");
        exit();
    }

    


    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password)
            VALUES ('$name', '$email', '$hashed')";

    if (mysqli_query($connection, $sql)) {
        $_SESSION['message'] = "Registration successful. Please login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['message'] = "Registration failed";
        header("Location: register.php");
        exit();
    }
}





if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($connection, trim($_POST['email']));
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $query = mysqli_query($connection, $sql);
    $user = mysqli_fetch_assoc($query);

    // password_verify 
    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();

    } else {
        $_SESSION['message'] = "Wrong email or password";
        header("Location: login.php");
        exit();
    }
}



if (isset($_POST['add_product'])) {

    $name = mysqli_real_escape_string($connection, trim($_POST['name']));
    $description = mysqli_real_escape_string($connection, trim($_POST['description']));
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $image = "";

    if ($name == "" || $price <= 0 || $stock < 0) {
        $_SESSION['message'] = "Enter a valid name, price and stock";
        header("Location: add_product.php");
        exit();
    }

    


    if ($_FILES['image']['name'] != "") {

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if ($ext != "jpg" && $ext != "jpeg" && $ext != "png" && $ext != "gif" && $ext != "webp") {
            $_SESSION['message'] = "Image must be jpg, png, gif or webp";
            header("Location: add_product.php");
            exit();
        }    




        $image = time() . "_" . rand(100, 999) . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
    }



    $sql = "INSERT INTO products (name, description, price, image, stock)
            VALUES ('$name', '$description', '$price', '$image', '$stock')";

    if (mysqli_query($connection, $sql)) {
        $_SESSION['message'] = "Product added successfully";
        header("Location: products.php");
        exit();
    } else {
        $_SESSION['message'] = "Failed to add product";
        header("Location: add_product.php");
        exit();
    }
}


if (isset($_POST['update_product'])) {

    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($connection, trim($_POST['name']));
    $description = mysqli_real_escape_string($connection, trim($_POST['description']));
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];

    if ($name == "" || $price <= 0 || $stock < 0) {
        $_SESSION['message'] = "Enter a valid name, price and stock";
        header("Location: edit_product.php?id=$id");
        exit();
    }

    if ($_FILES['image']['name'] != "") {

        // a new image was chosen, so update the image too
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if ($ext != "jpg" && $ext != "jpeg" && $ext != "png" && $ext != "gif" && $ext != "webp") {
            $_SESSION['message'] = "Image must be jpg, png, gif or webp";
            header("Location: edit_product.php?id=$id");
            exit();
        }

        $image = time() . "_" . rand(100, 999) . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);

        $sql = "UPDATE products SET
                name='$name',
                description='$description',
                price='$price',
                stock='$stock',
                image='$image'
                WHERE id='$id'";

    } else {

        // no new image, keep the old one
        $sql = "UPDATE products SET
                name='$name',
                description='$description',
                price='$price',
                stock='$stock'
                WHERE id='$id'";
    }

    if (mysqli_query($connection, $sql)) {
        $_SESSION['message'] = "Product updated successfully";
        header("Location: products.php");
        exit();
    } else {
        $_SESSION['message'] = "Update failed: " . mysqli_error($connection);
        header("Location: edit_product.php?id=$id");
        exit();
    }
}


if (isset($_POST['delete_product'])) {

    $id = (int)$_POST['id'];

    // a product that is already in an order cannot be deleted
    $check = mysqli_query($connection, "SELECT * FROM order_items WHERE product_id = '$id'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['message'] = "This product is in orders, so it cannot be deleted. Set its stock to 0 instead.";
        header("Location: products.php");
        exit();
    }

    mysqli_query($connection, "DELETE FROM products WHERE id = '$id'");

    $_SESSION['message'] = "Product deleted";
    header("Location: products.php");
    exit();
}


if (isset($_POST['update_user'])) {

    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($connection, trim($_POST['name']));
    $email = mysqli_real_escape_string($connection, trim($_POST['email']));
    $role = $_POST['role'];
    $password = $_POST['password'];

    if ($role != 'admin' && $role != 'user') {
        $role = 'user';
    }

    // the admin cannot remove his own admin role
    if ($id == $_SESSION['user_id']) {
        $role = 'admin';
    }

    if ($name == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Enter a valid name and email";
        header("Location: edit_user.php?id=$id");
        exit();
    }

    // check if another user already uses this email
    $check = mysqli_query($connection, "SELECT * FROM users WHERE email = '$email' AND id != '$id'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['message'] = "This email is used by another user";
        header("Location: edit_user.php?id=$id");
        exit();
    }

    if ($password != "") {

        if (strlen($password) < 6) {
            $_SESSION['message'] = "Password must be at least 6 characters";
            header("Location: edit_user.php?id=$id");
            exit();
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET
                name='$name',
                email='$email',
                role='$role',
                password='$hashed'
                WHERE id='$id'";

    } else {

        $sql = "UPDATE users SET
                name='$name',
                email='$email',
                role='$role'
                WHERE id='$id'";
    }

    if (mysqli_query($connection, $sql)) {

        // if the admin edited himself, update the name in the session
        if ($id == $_SESSION['user_id']) {
            $_SESSION['name'] = trim($_POST['name']);
        }

        $_SESSION['message'] = "User updated successfully";
        header("Location: users.php");
        exit();

    } else {
        $_SESSION['message'] = "Update failed: " . mysqli_error($connection);
        header("Location: edit_user.php?id=$id");
        exit();
    }
}


if (isset($_POST['delete_user'])) {

    $id = (int)$_POST['id'];

    if ($id == $_SESSION['user_id']) {
        $_SESSION['message'] = "You cannot delete your own account";
        header("Location: users.php");
        exit();
    }

    mysqli_query($connection, "DELETE FROM users WHERE id = '$id'");

    $_SESSION['message'] = "User deleted";
    header("Location: users.php");
    exit();
}


if (isset($_POST['update_order'])) {

    $id = (int)$_POST['id'];
    $status = $_POST['status'];

    if ($status != "pending" && $status != "paid" && $status != "shipped" && $status != "delivered" && $status != "cancelled") {
        $_SESSION['message'] = "Invalid status";
        header("Location: orders.php");
        exit();
    }

    mysqli_query($connection, "UPDATE orders SET status = '$status' WHERE id = '$id'");

    $_SESSION['message'] = "Order status updated";
    header("Location: orders.php");
    exit();
}


if (isset($_POST['add_to_cart'])) {

    $user_id = (int)$_SESSION['user_id'];
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity < 1) {
        $quantity = 1;
    }

    // find the product
    $query = mysqli_query($connection, "SELECT * FROM products WHERE id = '$product_id'");
    $product = mysqli_fetch_assoc($query);

    if (!$product || $product['stock'] < 1) {
        $_SESSION['message'] = "This product is out of stock";
        header("Location: user_product.php");
        exit();
    }

    // check if the product is already in the cart
    $query = mysqli_query($connection, "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'");
    $item = mysqli_fetch_assoc($query);

    if ($item) {


    $new_quantity = $item['quantity'] + $quantity;

        if ($new_quantity > $product['stock']) {
            $_SESSION['message'] = "Only " . $product['stock'] . " in stock";
            header("Location: user_product.php");
            exit();
        }

        mysqli_query($connection, "UPDATE cart SET quantity = '$new_quantity' WHERE id = '" . $item['id'] . "'");

    } else {

        // not in the cart yet, so add it
        if ($quantity > $product['stock']) {
            $_SESSION['message'] = "Only " . $product['stock'] . " in stock";
            header("Location: user_product.php");
            exit();
        }

        mysqli_query($connection, "INSERT INTO cart (user_id, product_id, quantity)
                                   VALUES ('$user_id', '$product_id', '$quantity')");
    }

    $_SESSION['message'] = "Product added to cart. Choose the quantity below, then press Checkout.";
    header("Location: cart.php");
    exit();
}


if (isset($_POST['update_cart'])) {

    $user_id = (int)$_SESSION['user_id'];
    $id = (int)$_POST['id'];
    $quantity = (int)$_POST['quantity'];

    $sql = "SELECT cart.id, products.stock
            FROM cart
            JOIN products ON cart.product_id = products.id
            WHERE cart.id = '$id' AND cart.user_id = '$user_id'";
    $query = mysqli_query($connection, $sql);
    $item = mysqli_fetch_assoc($query);

    if (!$item) {
        $_SESSION['message'] = "Cart item not found";
        header("Location: cart.php");
        exit();
    }

    // quantity 0 means remove the item
    if ($quantity < 1) {
        mysqli_query($connection, "DELETE FROM cart WHERE id = '$id'");
        $_SESSION['message'] = "Item removed from cart";
        header("Location: cart.php");
        exit();
    }

    if ($quantity > $item['stock']) {
        $_SESSION['message'] = "Only " . $item['stock'] . " in stock";
        header("Location: cart.php");
        exit();
    }

    mysqli_query($connection, "UPDATE cart SET quantity = '$quantity' WHERE id = '$id'");

    $_SESSION['message'] = "Cart updated";
    header("Location: cart.php");
    exit();
}


if (isset($_POST['remove_cart'])) {

    $user_id = (int)$_SESSION['user_id'];
    $id = (int)$_POST['id'];

    mysqli_query($connection, "DELETE FROM cart WHERE id = '$id' AND user_id = '$user_id'");

    $_SESSION['message'] = "Item removed from cart";
    header("Location: cart.php");
    exit();
}



if (isset($_POST['checkout'])) {

    $user_id = (int)$_SESSION['user_id'];

    // get everything in the cart together with the product details
    $sql = "SELECT products.id AS product_id, products.name, products.price, products.stock, cart.quantity
            FROM cart
            JOIN products ON cart.product_id = products.id
            WHERE cart.user_id = '$user_id'";
    $query = mysqli_query($connection, $sql);

    if (mysqli_num_rows($query) == 0) {
        $_SESSION['message'] = "Your cart is empty";
        header("Location: cart.php");
        exit();
    }

    // step 1: check the stock and calculate the total
    $total = 0;
    $items = array();

    while ($row = mysqli_fetch_assoc($query)) {

        if ($row['quantity'] > $row['stock']) {
            $_SESSION['message'] = "Not enough stock for " . $row['name'];
            header("Location: cart.php");
            exit();
        }

        $total = $total + ($row['price'] * $row['quantity']);
        $items[] = $row;
    }

    $total = round($total, 2);

    // step 2: create the order
    mysqli_query($connection, "INSERT INTO orders (user_id, total_amount) VALUES ('$user_id', '$total')");
    $order_id = mysqli_insert_id($connection);

    // step 3: save each item and reduce the stock
    foreach ($items as $item) {

        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        mysqli_query($connection, "INSERT INTO order_items (order_id, product_id, quantity, price)
                                   VALUES ('$order_id', '$product_id', '$quantity', '$price')");

        mysqli_query($connection, "UPDATE products SET stock = stock - $quantity WHERE id = '$product_id'");
    }



    mysqli_query($connection, "DELETE FROM cart WHERE user_id = '$user_id'");

    $_SESSION['message'] = "Order placed successfully";
    header("Location: my_orders.php");
    exit();
}




if (isset($_POST['update_profile'])) {

    $user_id = (int)$_SESSION['user_id'];
    $name = mysqli_real_escape_string($connection, trim($_POST['name']));
    $email = mysqli_real_escape_string($connection, trim($_POST['email']));
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($name == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Enter a valid name and email";
        header("Location: profile.php");
        exit();
    }

    $check = mysqli_query($connection, "SELECT * FROM users WHERE email = '$email' AND id != '$user_id'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['message'] = "This email is used by another user";
        header("Location: profile.php");
        exit();
    }

    if ($password != "") {

        if (strlen($password) < 6) {
            $_SESSION['message'] = "Password must be at least 6 characters";
            header("Location: profile.php");
            exit();
        }

        if ($password != $confirm) {
            $_SESSION['message'] = "Passwords do not match";
            header("Location: profile.php");
            exit();
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET name='$name', email='$email', password='$hashed' WHERE id='$user_id'";

    } else {

        $sql = "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'";
    }

    if (mysqli_query($connection, $sql)) {
        $_SESSION['name'] = trim($_POST['name']);
        $_SESSION['message'] = "Profile updated successfully";
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['message'] = "Update failed: " . mysqli_error($connection);
        header("Location: profile.php");
        exit();
    }
}


header("Location: login.php");
exit();
?>