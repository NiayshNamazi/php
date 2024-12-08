<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimal Shopping</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar">
    <a href="index.php">Home</a>
    <a href="view_products.php">Products</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="upload_product.php">Upload Product</a>
        <a href="selected_products.php">Cart</a>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</nav>
