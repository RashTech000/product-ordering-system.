# My Shop - Online Shopping System

A simple online shop built with **PHP**, **MySQL**, **HTML** and **CSS**.
It has two sides: an **Admin** side to manage the shop, and a **User** side to browse products, add to cart, checkout and track orders.

---

## Features

### User
- Register and login
- Dashboard showing total spend and items in cart
- Browse products (image, description, price, stock)
- **Add to Cart** under every product, with a quantity selector
- Cart page: update quantity, remove items, see subtotals and total
- **Checkout** to place an order
- **My Orders**: see all orders, their items, total, date and status
- Profile page

### Admin
- Dashboard with total products and total revenue (cancelled orders are not counted)
- Add, edit and delete products (with image upload)
- View users, edit them (name, email, role, password) and delete them
- View all orders and change status: `pending`, `paid`, `shipped`, `delivered`, `cancelled`

---

## How the shopping flow works

```
Products  ->  Add to Cart  ->  Cart (check the totals)  ->  Checkout  ->  My Orders
```

1. The user opens **Products** and presses **Add to Cart** under a product.
2. In **Cart** the user checks the calculation (Price x Quantity) and the total.
3. Pressing **Checkout** creates the order, saves the items, reduces the stock and empties the cart.
4. The user is taken to **My Orders**, where the new order appears as `pending`.
5. The admin updates the order status from the **Orders** page.

Checkout runs inside a database transaction, so two people cannot buy the same last item, and an order is never saved half-done.

---

## Requirements

- XAMPP (Apache + MySQL + PHP 7.4 or newer), or any similar server
- A web browser

---

## Installation

1. **Copy the project** into your server folder, for example:
   `C:\xampp\htdocs\shopp`

2. **Start** Apache and MySQL from the XAMPP Control Panel.

3. **Create the database**
   - Open `http://localhost/phpmyadmin`
   - Create a new database (for example `shop_db`)
   - Open the database, go to **Import** (or **SQL**) and run the file `database.sql`

4. **Set up the connection** in `connection.php`:

   ```php
   <?php
   $connection = mysqli_connect("localhost", "root", "", "shop_db");

   if (!$connection) {
       die("Database connection failed: " . mysqli_connect_error());
   }
   ?>
   ```

   Change the database name, username and password if yours are different.

5. **Create the uploads folder**: make a folder named `uploads` inside the project folder. Product images are saved there.

6. **Open the site**: `http://localhost/shopp/login.php`

---

## Creating the first admin

New accounts are created with the role `user`. To make the first admin:

1. Register a normal account on `register.php`.
2. In phpMyAdmin, open the `users` table.
3. Change that account's `role` from `user` to `admin`.
4. Login again. You will now see the admin pages.

After that, an admin can change other users' roles from the **Users** page.

---

## Database tables

| Table         | Purpose                                              |
|---------------|------------------------------------------------------|
| `users`       | Accounts (name, email, password, role)               |
| `products`    | Products (name, description, price, image, stock)    |
| `cart`        | Items a user has added but not yet ordered           |
| `orders`      | One row per order (user, total amount, status, date) |
| `order_items` | Products inside each order, with quantity and price  |

---

## Project structure

```
shopp/
├── action.php            # Handles all form actions (login, cart, checkout, etc.)
├── connection.php        # Database connection
├── database.sql          # Database tables
├── style.css             # Styling for all pages
├── message.php           # Shows success / error messages
├── login.php, register.php, logout.php
│
├── admin_check.php       # Allows admins only
├── admin_nav.php         # Admin menu
├── admin_dashboard.php
├── products.php, add_product.php, edit_product.php
├── users.php, edit_user.php
├── orders.php
│
├── user_check.php        # Allows users only
├── user_nav.php          # User menu
├── dashboard.php
├── user_product.php      # Product list with Add to Cart
├── cart.php              # Cart and Checkout
├── my_orders.php
├── profile.php
│
└── uploads/              # Product images
```

---

## Common problems

| Problem | Solution |
|---------|----------|
| No **Add to Cart** button, product says "Out of stock" | The product stock is 0. Edit the product and set stock above 0. |
| Product image does not show | Make sure the `uploads` folder exists and the image file is inside it. |
| "Database connection failed" | Check the database name, username and password in `connection.php`, and that MySQL is running. |
| Redirected back to login page | You are logged in with the wrong role. Admin pages need `admin`, shop pages need `user`. |

---

## Currency

All prices are shown in **TZS** (Tanzanian Shillings).

---

## Author
rashtech.

Created as a final school project.
