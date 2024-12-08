<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فروشگاه مینیمال</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .navbar {
        background-color: #007bff;
        color: white;
        padding: 1em;
        display: flex;
        justify-content: space-around;
        }

        .navbar a {
        color: white;
        text-decoration: none;
        margin: 0 10px;
        }
    </style>
</head>
<body dir="rtl">
<nav class="navbar">
    <a href="index.php">خانه</a>
    <a href="view_products.php">محصولات</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="upload_product.php">اپلود محصول</a>
        <a href="selected_products.php">سبدخرید</a>
        <a href="logout.php">خروج</a>
    <?php else: ?>
        <a href="login.php">ورود</a>
        <a href="register.php">ثبت‌نام</a>
    <?php endif; ?>
</nav>
